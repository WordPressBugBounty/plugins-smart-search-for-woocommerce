<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit00b8aef39d84f830bd28abb44332cebe
{
    public static $prefixLengthsPsr4 = array (
        'C' => 
        array (
            'Composer\\Installers\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Composer\\Installers\\' => 
        array (
            0 => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Searchanise\\Extensions\\WcSeJetpack' => __DIR__ . '/../..' . '/includes/extensions/class-wcsejetpack.php',
        'Searchanise\\Extensions\\WcWeglot' => __DIR__ . '/../..' . '/includes/extensions/class-wcweglot.php',
        'Searchanise\\SmartWoocommerceSearch\\Abstract_Extension' => __DIR__ . '/../..' . '/includes/abstract/class-abstract-extension.php',
        'Searchanise\\SmartWoocommerceSearch\\Admin' => __DIR__ . '/../..' . '/includes/admin/class-admin.php',
        'Searchanise\\SmartWoocommerceSearch\\Admin_Dashboard' => __DIR__ . '/../..' . '/includes/admin/class-admin-dashboard.php',
        'Searchanise\\SmartWoocommerceSearch\\Admin_Setting' => __DIR__ . '/../..' . '/includes/admin/class-admin-setting.php',
        'Searchanise\\SmartWoocommerceSearch\\Api' => __DIR__ . '/../..' . '/includes/helpers/class-api.php',
        'Searchanise\\SmartWoocommerceSearch\\Async' => __DIR__ . '/../..' . '/includes/queue/class-async.php',
        'Searchanise\\SmartWoocommerceSearch\\Bootstrap' => __DIR__ . '/../..' . '/includes/class-bootstrap.php',
        'Searchanise\\SmartWoocommerceSearch\\Cli_Commands' => __DIR__ . '/../..' . '/includes/cli/class-cli-commands.php',
        'Searchanise\\SmartWoocommerceSearch\\Cron' => __DIR__ . '/../..' . '/includes/queue/class-cron.php',
        'Searchanise\\SmartWoocommerceSearch\\Fulltext_Search' => __DIR__ . '/../..' . '/includes/class-fulltext-search.php',
        'Searchanise\\SmartWoocommerceSearch\\Hooks' => __DIR__ . '/../..' . '/includes/class-hooks.php',
        'Searchanise\\SmartWoocommerceSearch\\Info' => __DIR__ . '/../..' . '/includes/class-info.php',
        'Searchanise\\SmartWoocommerceSearch\\Installer' => __DIR__ . '/../..' . '/includes/install/class-installer.php',
        'Searchanise\\SmartWoocommerceSearch\\Locales' => __DIR__ . '/../..' . '/includes/helpers/class-locales.php',
        'Searchanise\\SmartWoocommerceSearch\\Logger' => __DIR__ . '/../..' . '/includes/class-logger.php',
        'Searchanise\\SmartWoocommerceSearch\\Navigation' => __DIR__ . '/../..' . '/includes/widgets/class-navigation.php',
        'Searchanise\\SmartWoocommerceSearch\\Profiler' => __DIR__ . '/../..' . '/includes/helpers/class-profiler.php',
        'Searchanise\\SmartWoocommerceSearch\\Queue' => __DIR__ . '/../..' . '/includes/queue/class-queue.php',
        'Searchanise\\SmartWoocommerceSearch\\Recommendations' => __DIR__ . '/../..' . '/includes/widgets/class-recommendations.php',
        'Searchanise\\SmartWoocommerceSearch\\Search_Results' => __DIR__ . '/../..' . '/includes/widgets/class-search-results.php',
        'Searchanise\\SmartWoocommerceSearch\\Searchanise_Exception' => __DIR__ . '/../..' . '/includes/exceptions/class-searchanise-exception.php',
        'Searchanise\\SmartWoocommerceSearch\\Upgrade' => __DIR__ . '/../..' . '/includes/install/class-upgrade.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit00b8aef39d84f830bd28abb44332cebe::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit00b8aef39d84f830bd28abb44332cebe::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit00b8aef39d84f830bd28abb44332cebe::$classMap;

        }, null, ClassLoader::class);
    }
}
