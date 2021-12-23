<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Michael Geering <michael.geering@kineo.com>
 */

use core\theme\settings;
use theme_kineo\Settings\Image;
use theme_kineo\SettingsResolver;

/**
 * @private Only to be used in this lib file due to its requirement on some dodgy core behaviour.
 * @return int|null
 */
function theme_kineo_get_tenant_id()
{
    // This is a horrible piece of code. At this point, we cannot rely on the global $USER object
    // (we could be on a login page) so we can only rely on the URL params, but those are inconsistent.
    // So we instead make use of the fact the theme/styles.php and theme/styles_debug.php declare a cleaned tenantId,
    // plus a quirk of PHP that the variable is also global, so we can use it here.
    // It's this or 3 CCMs to get this to work ;)
    global $tenant;

    return $tenant;
}

/**
 * Output any SCSS we want to add in _before_ any other SCSS code.
 * @param theme_config $config
 * @return string
 */
function theme_kineo_pre_scss_compile(theme_config $config): string
{
    // We need to find and output any of our settings which we need SCSS variables for.
    // There isn't many: most are CSS variables. But some are needed in SCSS logical flows and so are needed here.
    $variables = SettingsResolver::getInstance()->getScssThemeVariables(theme_kineo_get_tenant_id());

    if (empty($variables)) {
        return '';
    }

    // This must be returned as a valid SCSS string.
    // This is going into the compiler with no checks, so make sure it is correct!
    $scss = '';
    foreach ($variables as $key => $value) {
        $variable = 'resolved' . str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $key)));

        $isColour = preg_match('/^(rgb|hsl|#)[^;]*?$/', $value) === 1;
        if (is_bool($value) || $value === 'true' || $value === 'false') {
            // Special handling to make sure booleans are output as actual booleans.
            $value = var_export(filter_var($value, FILTER_VALIDATE_BOOLEAN), true);
        } else if (is_null($value)) {
            // Special handling for null, treated the same as booleans.
            $value = 'null';
        } else if (!$isColour && preg_match('/[$:;()\[\]@]+/', $value) === 1) {
            // Everything else, excluding anything that is a special colour (rgb, hsl, hex), must be wrapped in a string for safety.
            $value = "'$value'";
        }

        $scss .= '$' . $variable . ': ' . $value . ';' . PHP_EOL;
    }

    return $scss;
}

/**
 * Output any SCSS we want to add in _after_ all other SCSS code.
 * @param theme_config $config
 * @return string
 */
function theme_kineo_extra_scss_compile(theme_config $config): string
{
    global $CFG;

    // All of the plugin types, with local at the end.
    // Local plugins should always come last as they are the most likely to actually modify the theme.
    $pluginTypes = core_component::get_plugin_types();
    if ($pluginTypes === null) {
        return '';
    }

    unset($pluginTypes['theme']);

    $scssFiles = [];
    foreach ($pluginTypes as $type => $dir) {
        $plugins = core_component::get_plugin_list_with_file($type, 'kineo.scss');
        foreach ($plugins as $plugin => $file) {
            // Remove the SCSS extension, and make the path relative rather than absolute.
            $scssFiles[$type . '_' . $plugin] = str_replace([$CFG->dirroot, '.scss'], ['../../..', ''], $file);
        }
    }

    if (empty($scssFiles)) {
        return '';
    }

    // Add an @import for every single file we've found.
    $imports = PHP_EOL . '/** Custom Kineo Plugin SCSS Files */' . PHP_EOL;
    $imports .= implode(
        PHP_EOL,
        array_map(
            function ($file, $plugin) {
                return "/** Import for $plugin */\n@import \"$file\";";
            },
            $scssFiles,
            array_keys($scssFiles)
        )
    );

    return $imports;
}

/**
 * @param string $css
 * @param theme_config $config
 * @return string
 */
function theme_kineo_css_post_process(string $css, theme_config $config): string
{
    $css .= PHP_EOL . SettingsResolver::getInstance()->getResolvedVariableValue('custom-css', theme_kineo_get_tenant_id());
    $css = theme_kineo_add_custom_fonts_css($css);
    $css = theme_kineo_resolve_file_urls_to_css($css, 'custom_images', '@img');

    return $css;
}

/**
 * @return string
 */
function theme_kineo_before_standard_html_head()
{
    global $USER, $PAGE;

    if ($PAGE->theme->name !== 'kineo') {
        return '';
    }

    $googleFontUrl = SettingsResolver::getInstance()->getResolvedVariableValue('google-fonts', $USER->tenantid ?? 0);
    if (empty($googleFontUrl)) {
        return '';
    }
    $preConnect = html_writer::empty_tag('link', ['rel' => 'preconnect', 'href' => 'https://fonts.gstatic.com']);
    $fontFamilies = html_writer::empty_tag('link', ['href' => $googleFontUrl, 'rel' => 'stylesheet']);

    return $preConnect . $fontFamilies;
}

/**
 * Adds theme appearance links to category nav.
 *
 * @param navigation_node $navigation The navigation node to extend
 * @param context $context The context of the course
 * @return void|null return null if we don't want to display the node.
 */
function theme_kineo_extend_navigation_category_settings($navigation, $context)
{
    global $PAGE, $CFG, $DB;

    if (empty($CFG->tenantsenabled)) {
        return null;
    }

    if (!$context->tenantid) {
        return null;
    }

    if (!($context instanceof context_coursecat)) {
        return;
    }

    $tenant = $DB->get_record('tenant', ['categoryid' => $context->instanceid]);
    if (!$tenant) {
        return null;
    }

    // Leave when user does not have the right capabilities.
    $categoryContext = context_coursecat::instance($tenant->categoryid);
    if (!has_capability('totara/tui:themesettings', $categoryContext)) {
        return null;
    }

    $url = new moodle_url(
        '/theme/kineo/theme_settings.php',
        [
            'theme_name' => 'kineo',
            'tenant_id' => $tenant->id,
        ]
    );
    $node = navigation_node::create(
        get_string('pluginname', 'theme_kineo'),
        $url,
        navigation_node::NODETYPE_LEAF,
        null,
        'kineo_editor',
        new pix_icon('i/settings', '')
    );

    $appearance = $navigation->find('category_appearance', navigation_node::TYPE_CONTAINER);
    if (!$appearance) {
        $appearance = $navigation->add(
            get_string('appearance', 'admin'),
            null,
            navigation_node::TYPE_CONTAINER,
            null,
            'category_appearance'
        );
    }
    $appearance->add_node($node);

    if ($PAGE->url->compare($url, URL_MATCH_EXACT)) {
        $appearance->force_open();
        $node->make_active();
    }
}

/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool
 */
function theme_kineo_pluginfile($course, $cm, $context, $fileArea, $args, $forceDownload, array $options = array())
{
    //Custom images and fonts use legacy file manager and are always public
    if (in_array($fileArea, ['custom_images', 'custom_fonts'])) {
        sendFile($context, $fileArea, $args, $forceDownload, $options);
        exit();
    }

    $themeSettings = SettingsResolver::getInstance()->getThemeSettings();

    foreach($themeSettings->getSettings() as $themeSetting) {
        if ($themeSetting->getIdentifier() != $fileArea) {
            continue;
        }

        if (!empty($themeSetting->getOptions()[Image::IS_PUBLIC_KEY])) {
            sendFile($context, $fileArea, $args, $forceDownload, $options);
            exit();
        }
    }

    try {
        require_login($course, false, $cm, false, true);
    } catch (Exception $e) {
        send_file_not_found();
        return false;
    }

    sendFile($context, $fileArea, $args, $forceDownload, $options);

    return true;
}

function sendFile($context, $fileArea, $args, $forceDownload, $options)
{
    $fs = get_file_storage();
    $fullPath = "/{$context->id}/theme_kineo/$fileArea/$args[0]/$args[1]";
    if (!$file = $fs->get_file_by_hash(sha1($fullPath)) or $file->is_directory()) {
        send_file_not_found();
    }
    send_stored_file($file, 86400, 0, $forceDownload, $options); // download MUST be forced - security!
}

/**
 * @return string HTML fragment
 */
function theme_kineo_render_navbar_output()
{

    global $PAGE;

    if (!isloggedin() || $PAGE->theme->name !== 'kineo') {
        return;
    }

    $renderer = $PAGE->get_renderer('theme_kineo');

    $help = new stdClass();
    $help->help_url = filter_var(get_string('theme_settings:brand:header_help_url', 'theme_kineo'), FILTER_VALIDATE_URL);
    $help->help_text = format_string(get_string('theme_settings:brand:header_help_text', 'theme_kineo'));

    if ($help->help_url === false) {
        return '';
    }

    return $renderer->render_header_help_link($help);

}

/**
 * @param string $css CSS Fragment
 * @return string
 */
function theme_kineo_add_custom_fonts_css(string $css): string
{
    $tenantId = theme_kineo_get_tenant_id();
    $setting = SettingsResolver::getInstance()->getLegacySetting('custom_fonts_css', $tenantId);
    if (!$setting) {
        return $css;
    }

    return theme_kineo_resolve_file_urls_to_css($css . PHP_EOL . $setting->value, 'custom_fonts', '@font');
}

/**
 * @param string $css
 * @param string $fileArea
 * @param string $placeHolder
 * @return string
 */
function theme_kineo_resolve_file_urls_to_css(string $css, string $fileArea, string $placeHolder): string
{
    global $DB;

    $tenantId = theme_kineo_get_tenant_id();
    $configId = $DB->get_field('config_plugins', 'id', ['plugin' => 'theme_kineo', 'name' => "tenant_{$tenantId}_settings"]);
    $fh = new \core\files\file_helper('theme_kineo', $fileArea, context_system::instance());
    $fh->set_item_id($configId);

    if (!$files = $fh->get_stored_files()) {
        return $css;
    }

    foreach ($files as $file) {
        $url = moodle_url::make_pluginfile_url(
            $file->get_contextid(),
            $file->get_component(),
            $file->get_filearea(),
            $file->get_itemid(),
            $file->get_filepath(),
            $file->get_filename()
        );

        $css = str_replace($placeHolder . ':' . $file->get_filename(), $url->out(), $css);
    }

    return $css;
}

function theme_kineo_page_init() {
    global $PAGE;

    $PAGE->requires->js_call_amd('theme_kineo/back_to_top', 'init');
}