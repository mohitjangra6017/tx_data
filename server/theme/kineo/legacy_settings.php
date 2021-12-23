<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon Adams <simon.adams@kineo.com>
 */

use core\files\file_helper;
use theme_kineo\Form\LegacySettings;
use theme_kineo\SettingsResolver;

require_once(dirname(dirname(__DIR__)) . '/config.php');
global $CFG, $PAGE, $OUTPUT, $DB, $USER;
require_once($CFG->libdir . '/filelib.php');

require_login();
$context = context_system::instance();
require_capability('totara/tui:themesettings', $context);

$tenantId = optional_param('tenant_id', 0, PARAM_INT);
$itemId = $DB->get_field('config_plugins', 'id', ['plugin' => 'theme_kineo', 'name' => "tenant_{$tenantId}_settings"]);

// Page definition.
$url = new moodle_url('/theme/kineo/legacy_settings.php', ['tenant_id' => $tenantId]);
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_title(get_string('theme_settings:legacy:title', 'theme_kineo'));
$PAGE->requires->js_call_amd('theme_kineo/copy_custom_image_path', 'init', []);

$fontDraftItemId = file_get_submitted_draft_itemid('custom_fonts');
$imageDraftItemId = file_get_submitted_draft_itemid('custom_images');

//Item ID needs to match core behaviour i.e the id of the config_plugins row for that tenant
file_prepare_draft_area(
    $fontDraftItemId,
    $context->id,
    'theme_kineo',
    'custom_fonts',
    $itemId,
    ['subdirs' => false, 'maxfiles' => 50]
);

file_prepare_draft_area(
    $imageDraftItemId,
    $context->id,
    'theme_kineo',
    'custom_images',
    $itemId,
    ['subdirs' => false, 'maxfiles' => 50]
);

$fontEntry = new stdClass;
$fontEntry->id = $itemId;
$fontEntry->custom_fonts = $fontDraftItemId;

$imageEntry = new stdClass;
$imageEntry->id = $itemId;
$imageEntry->custom_images = $imageDraftItemId;

$customFontCss = SettingsResolver::getInstance()->getLegacySetting('custom_fonts_css', $tenantId);

$form = new LegacySettings(
    $url,
    [
        'custom_fonts_css' => $customFontCss->value ?? null,
        'custom_fonts' => $fontEntry,
        'custom_images' => $imageEntry,
    ]
);


if ($data = $form->get_data()) {
    if (isset($data->custom_fonts)) {
        file_save_draft_area_files(
            $data->custom_fonts,
            $context->id,
            'theme_kineo',
            'custom_fonts',
            $itemId,
            ['subdirs' => false, 'maxfiles' => 50]
        );
    }

    if (!empty($data->custom_images)) {
        file_save_draft_area_files(
            $data->custom_images,
            $context->id,
            'theme_kineo',
            'custom_images',
            $itemId,
            ['subdirs' => false, 'maxfiles' => 50]
        );
    }

    if (!empty($data->custom_fonts_css)) {
        SettingsResolver::getInstance()->storeLegacySetting('custom_fonts_css', $data->custom_fonts_css, $tenantId);
    }

    redirect($url, get_string('settings_success_save', 'totara_tui'), null, \core\output\notification::NOTIFY_SUCCESS);
}

echo $OUTPUT->header();
$form->display();
echo $OUTPUT->footer();