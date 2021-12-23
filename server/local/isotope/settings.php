<?php
/**
 * @copyright City & Guilds Kineo 2018
 * @author Michael Geering <michael.geering@kineo.com>
 */

/** @var admin_root $ADMIN */

if ($hassiteconfig) {
    global $CFG;

    $settings = new admin_settingpage('local_isotope', get_string('pluginname', 'local_isotope'));

    $isotope = new \local_isotope\Isotope();
    foreach ($isotope->getProviders() as $provider) {
        $settings->add(
            new admin_setting_configcheckbox(
                'local_isotope/provider_' . $provider->getShortName(),
                $provider->getDisplayName(),
                get_string('show_isotope_provider', 'local_isotope'),
                1
            )
        );
    }
    $ADMIN->add('localplugins', $settings);
}

// Provider settings
$category = new admin_category('isotopeproviders', get_string('subplugintype_isotopeprovider_plural', 'local_isotope'));
$ADMIN->add('modules', $category);

foreach (core_plugin_manager::instance()
                            ->get_plugins_of_type('isotopeprovider') as $plugin) {
    $plugin->load_settings($ADMIN, $category->name, $hassiteconfig);
}