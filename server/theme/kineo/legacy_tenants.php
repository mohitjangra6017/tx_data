<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon Adams <simon.adams@kineo.com>
 */

use totara_tenant\entity\tenant;

require_once (dirname(dirname(__DIR__)) . '/config.php');
global $CFG, $PAGE, $OUTPUT;
require_once $CFG->libdir . '/adminlib.php';

// Page definition.
admin_externalpage_setup('theme_kineo_custom/legacy_tenants');
$url = new moodle_url('/theme/kineo/legacy_tenants.php');
$context = context_system::instance();
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_title(get_string('theme_settings:legacy:title', 'theme_kineo'));

$theme_config = theme_config::load('kineo');
$tenants = tenant::repository()->select(['idnumber', 'name', 'id'])->get()->to_array();
$data = [];
$settingsUrl = new moodle_url('/theme/kineo/legacy_settings.php');

foreach ($tenants as $tenant) {
    $context = context_tenant::instance($tenant['id']);
    if (!has_capability('totara/tui:themesettings', $context)) {
        continue;
    }
    $theme_settings = new \core\theme\settings($theme_config, $tenant['id']);
    $branding = $theme_settings->is_tenant_branding_enabled() ? get_string('custom', 'totara_tui') : get_string('site', 'totara_tui');
    $tenantSettingsUrl = clone $settingsUrl;
    $tenantSettingsUrl->param('tenant_id', $tenant['id']);
    $data[] = [
        $tenant['name'],
        $tenant['idnumber'],
        $branding,
        html_writer::link(
            $tenantSettingsUrl,
            $OUTPUT->flex_icon('settings')
        ),
    ];
}

$table = new html_table();
$table->head = ['Tenant', 'Tenant identifier', 'Branding', 'Actions'];
$table->data = $data;
$table = $table->export_for_template($OUTPUT);

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('pluginname', 'theme_kineo'));
echo $OUTPUT->heading(get_string('sitebranding', 'totara_tui'), 3);
echo $OUTPUT->box(get_string('sitebrandinginformation', 'totara_tui'));
echo $OUTPUT->single_button($settingsUrl, get_string('editsitebranding', 'totara_tui'));
echo $OUTPUT->heading(get_string('tenantbranding', 'totara_tui'), 3);;
echo $OUTPUT->render_from_template('core/table', $table);
echo $OUTPUT->footer();