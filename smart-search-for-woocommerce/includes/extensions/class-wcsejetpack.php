<?php
/**
 * Searchanise Jetpack integration
 *
 * @package Searchanise/Jetpack
 */

namespace Searchanise\Extensions;

defined( 'SE_ABSPATH' ) || exit;

use Searchanise\SmartWoocommerceSearch\Abstract_Extension;
use Searchanise\SmartWoocommerceSearch\Api;

/**
 * Searchanise Jetpac extension class
 */
class WcSeJetpack extends Abstract_Extension {

	const SORT_MAPPING = array(
		'sku'            => 'product_code',
		'stock_quantity' => 'quantity',
	);

	/**
	 * Current currency
	 *
	 * @var string
	 */
	private static $current_currency;

	/**
	 * Returns plugin basename
	 *
	 * @return string
	 */
	public function getBaseName() {
		return defined( 'WCJ_PLUGIN_FILE' ) ? plugin_basename( WCJ_PLUGIN_FILE ) : '';
	}

	/**
	 * {@inheritdoc}
	 *
	 * @return string[]
	 */
	protected function get_filters() {
		return array(
			'se_addon_options',
			'se_get_currency_rate',
			'se_product_usergroup_ids',
			'se_is_usergroup_prices_available',
			'se_get_sortable_attributes',
			'se_get_sort_mapping',
			'se_is_hide_empty_price',
			'se_is_use_usergroups',
		);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @return string[]
	 */
	protected function get_hooks() {
		return array(
			'se_generate_usergroup_prices_pre',
			'se_generate_usergroup_prices_post',
			'update_option',
		);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @return boolean
	 */
	public function is_active() {
		return is_plugin_active( $this->getBaseName() );
	}

	/**
	 * Checks is plugin active needed for hook
	 *
	 * @return boolean
	 */
	public function seIsUseUsergroups() {
		return $this->is_active();
	}

	/**
	 * Checks if multicurrency module is active
	 *
	 * @return boolean
	 */
	public function isMulticurrencyActive() {
		return $this->is_active() && get_option( 'wcj_multicurrency_enabled' ) == 'yes';
	}

	/**
	 * Checks if products by user role module is active
	 *
	 * @return boolean
	 */
	public function isProductsByUserRoleActive() {
		return $this->is_active() && get_option( 'wcj_product_by_user_role_enabled' ) == 'yes';
	}

	/**
	 * Check if price by user role module is active
	 *
	 * @return boolean
	 */
	public function isPriceByUserRoleActive() {
		return $this->is_active() && get_option( 'wcj_price_by_user_role_enabled' ) == 'yes';
	}

	/**
	 * Check if additional sortings are available
	 *
	 * @return boolean
	 */
	public function isSortingAvailable() {
		return 'yes' === get_option( 'wcj_sorting_enabled', 'yes' )
			&& 'yes' === get_option( 'wcj_more_sorting_enabled', 'yes' );
	}

	/**
	 * Addon options hook
	 *
	 * @param array $options Addon options.
	 *
	 * @return array
	 */
	public function seAddonOptions( $options ) {
		if ( $this->is_active() ) {
			$options['plugins']['woocommerce-jetpack/woocommerce-jetpack.php']['IsSupported'] = 'Y';
		}

		return $options;
	}

	/**
	 * Is usergroup role hook
	 *
	 * @param boolean $is_usergroup_prices_available Usergroup flag.
	 *
	 * @return boolean
	 */
	public function seIsUsergroupPricesAvailable( $is_usergroup_prices_available ) {
		if ( $this->isPriceByUserRoleActive() ) {
			$is_usergroup_prices_available = true;
		}

		return $is_usergroup_prices_available;
	}

	/**
	 * Returns true if hide price option enabled
	 *
	 * @return bool
	 */
	public function seIsHideEmptyPrice() {
		return $this->isOptionEmptyPriceForRoleEnabled() == 'yes' ? true : false;
	}

	/**
	 * Returns true if hide price option for role enabled
	 *
	 * @return true
	 */
	public function isOptionEmptyPriceForRoleEnabled() {
		$current_user = wp_get_current_user();

		if ( ! empty( $current_user->roles ) ) {
			return ( get_option( 'wcj_price_by_user_role_empty_price_' . $current_user->roles[0] ) );
		} else {
			return false;
		}
	}

	/**
	 * Get currency exchange rate
	 *
	 * @param string $currency_code          Currency code.
	 * @return float $currency_exchange_rate Currency rate.
	 */
	private function getCurrencyExchangeRate( $currency_code ) {
		$currency_exchange_rate = 1.0;

		/**
		 * Gets multicurrency total number
		 *
		 * @since 1.0.0
		 */
		$total_number = apply_filters( 'booster_option', 2, get_option( 'wcj_multicurrency_total_number', 2 ) );

		for ( $i = 1; $i <= $total_number; $i++ ) {
			if ( get_option( 'wcj_multicurrency_currency_' . $i ) === $currency_code ) {
				$currency_exchange_rate = (float) get_option( 'wcj_multicurrency_exchange_rate_' . $i );
				break;
			}
		}

		return $currency_exchange_rate;
	}

	/**
	 * Return active currency rate
	 *
	 * @param float $currency_rate Currency rate.
	 *
	 * @return float
	 */
	public function seGetCurrencyRate( $currency_rate ) {
		if ( $this->isMulticurrencyActive() ) {
			$currency = wcj_session_get( 'wcj-currency' );
			$currency_rate = 1.0 / $this->getCurrencyExchangeRate( $currency );
		}

		return $currency_rate;
	}

	/**
	 * Get product usergroup ids hook
	 *
	 * @param array      $usergroup_ids Usergroup ids.
	 * @param WC_Product $product_data  Product data.
	 * @param string     $lang_code     Lang code.
	 *
	 * @return array
	 */
	public function seProductUsergroupIds( $usergroup_ids, $product_data, $lang_code ) {
		if ( $this->isProductsByUserRoleActive() ) {
			if ( get_option( 'wcj_product_by_user_role_visibility' ) != 'yes' ) {
				$usergroup_ids = get_post_meta( $product_data->get_id(), '_wcj_product_by_user_role_visible', true );
			}
		}

		return $usergroup_ids;
	}

	/**
	 * Actions before prices generation
	 */
	public function seGenerateUsergroupPricesPre() {
		// Reset current currency value to generate price in base currency.
		if ( $this->isMulticurrencyActive() ) {
			self::$current_currency = wcj_session_get( 'wcj-currency' );
			wcj_session_set( 'wcj-currency', '' );
		}
	}

	/**
	 * Actions after prices generation
	 */
	public function seGenerateUsergroupPricesPost() {
		// Restore original currency if needed.
		if ( $this->isMulticurrencyActive() && ! empty( self::$current_currency ) ) {
			wcj_session_set( 'wcj-currency', self::$current_currency );
			self::$current_currency = '';
		}
	}

	/**
	 * Returns sortable attributes
	 *
	 * @param array $sortable_attributes List of available sortable attributes.
	 *
	 * @return array
	 */
	public function seGetSortableAttributes( $sortable_attributes ) {
		if ( $this->isSortingAvailable() ) {
			// TODO: The sorting can be configured in backend
			// So we have to read real sorting from wcj_sorting_rearrange option here.
			$sortable_attributes = array_merge( $sortable_attributes, array_values( self::SORT_MAPPING ) );
		}

		return $sortable_attributes;
	}

	/**
	 * Update option
	 *
	 * @param string $option_id Option name.
	 * @param string $old_value Old value.
	 * @param string $value     New value.
	 */
	public function updateOption( $option_id, $old_value, $value ) {
		if ( $old_value != $value ) {
			if ( in_array(
				$option_id,
				array(
					'wcj_sorting_enabled',
					'wcj_more_sorting_enabled',
				)
			) ) {
				// Need reindexation.
				Api::get_instance()->queue_import();
			}
		}
	}

	/**
	 * Returns available sorting mapping
	 *
	 * @param array $mapping All sorting mapping.
	 *
	 * @return array
	 */
	public function seGetSortMapping( $mapping ) {
		if ( $this->isSortingAvailable() ) {
			$mapping = array_merge( $mapping, self::SORT_MAPPING );
		}

		return $mapping;
	}
}
