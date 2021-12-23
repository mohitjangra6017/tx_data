<?php

defined('MOODLE_INTERNAL') || die();

/**
 * This file is included in server/admin/settings/appearance.php and defines user access
 * required for the Kineo Theme customisation page for a specific tenancy.
 */

/** @var core_config $CFG */
/** @var core\record\tenant $tenant */
/** @var context_coursecat $categorycontext */

$settings = new admin_externalpage(
    'kineo_editor',
    new lang_string('pluginname','theme_kineo'),
    "$CFG->wwwroot/theme/kineo/theme_settings.php?theme_name=kineo&tenant_id=$tenant->id",
    'totara/tui:themesettings',
    false,
    $categorycontext
);