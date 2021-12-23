<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Michael Geering <michael.geering@kineo.com>
 */

defined('MOODLE_INTERNAL') || die();

/** @var admin_root $ADMIN */

if ($ADMIN->locate('kineo_custom_theme') !== null) {
    return;
}

$context = context_system::instance();
if (!empty($USER->tenantid)) {
    $tenant = core\record\tenant::fetch($USER->tenantid);
    $context = context_coursecat::instance($tenant->categoryid);
}

$category = new admin_category(
    'kineo_custom_theme',
    new lang_string('pluginname', 'theme_kineo')
);

$category->add(
    'kineo_custom_theme',
    new admin_externalpage(
        'kineo_editor',
        get_string('theme_settings:custom:title', 'theme_kineo'),
        "{$CFG->wwwroot}/theme/kineo/index.php",
        'totara/tui:themesettings',
        false,
        $context
    )
);

$category->add(
    'kineo_custom_theme',
    new admin_externalpage(
        'theme_kineo_custom/legacy_tenants',
        get_string('theme_settings:legacy:title', 'theme_kineo'),
        "{$CFG->wwwroot}/theme/kineo/legacy_tenants.php",
        'totara/tui:themesettings',
        false,
        $context
    )
);

$ADMIN->add($context instanceof context_system ? 'themes' : 'appearance', $category);
