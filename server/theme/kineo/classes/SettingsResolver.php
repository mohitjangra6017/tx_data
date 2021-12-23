<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace theme_kineo;

use core\tenant_orm_helper;
use core\theme\settings;
use core\webapi\resolver\query\get_theme_settings;
use stdClass;
use theme_kineo\Settings\Collection;
use theme_kineo\Settings\Editor;
use theme_kineo\Settings\Image;
use theme_kineo\Settings\Setting;
use theme_kineo\Settings\Tab;
use totara_core\advanced_feature;
use totara_tenant\entity\tenant;
use totara_tui\local\locator\bundle;

final class SettingsResolver
{
    protected $srcDirectory;

    /** @var Collection */
    protected $settings;

    protected $tabs;

    private static $instance;

    private array $resolvedVariables = [];

    private function __construct()
    {
        global $CFG;
        // TODO: There has to be a better way of loading a component directory...
        $this->srcDirectory = $CFG->srcroot . '/client/component/theme_kineo/';
    }

    public static function getInstance(): SettingsResolver
    {
        if (static::$instance === null) {
            static::$instance = new SettingsResolver();
        }

        return static::$instance;
    }

    /**
     * Returns the theme settings for the site, or only the tenant allowed settings.
     * @param bool $isTenantEnabled
     * @return Collection
     */
    public function getThemeSettings(bool $isTenantEnabled = false): Collection
    {
        if ($this->settings === null) {
            $this->loadThemeSettings();
        }

        if (!$isTenantEnabled) {
            return $this->settings;
        }

        return $this->filterSettingsForTenant();
    }

    /**
     * Reloads the Theme Settings from file to ensure they are up-to-date.
     */
    public function reloadThemeSettings()
    {
        $this->settings = null;
        $this->resolvedVariables = [];
        $this->loadThemeSettings();
    }

    private function filterSettingsForTenant(): Collection
    {
        $settings = $this->settings->getSettings();

        $allowPreLoginTenantTheme = get_config('core', 'allowprelogintenanttheme');

        $tenantSettings = array_filter(
            $settings,
            function ($item) use ($allowPreLoginTenantTheme) {
                return
                    $item->isTenantConfigurable() &&
                    (
                        $item->getHeading() != 'login-signup-page' || $allowPreLoginTenantTheme
                    );
            }
        );

        return new Collection($tenantSettings, $this->settings->getTabs());
    }

    /**
     * Filter the settings list as needed.
     * Replace the settings so that we don't have references anywhere to removed settings.
     */
    private function filterSettings()
    {
        $settings = $this->settings->getSettings();

        $filteredSettings = array_filter(
            $settings,
            function ($item) {
                // Filter out settings that rely on Totara Features that are disabled.
                // If a setting doesn't need a Totara Feature, it should always be allowed.
                return !isset($item->getOptions()[Setting::REQUIRES_TOTARA_FEATURE_KEY]) ||
                       advanced_feature::is_enabled($item->getOptions()[Setting::REQUIRES_TOTARA_FEATURE_KEY]);
            }
        );

        if (count($settings) === count($filteredSettings)) {
            return;
        }

        $this->settings = new Collection($filteredSettings, $this->settings->getTabs());
    }

    /**
     * Load the values of all Settings as set by the front end.
     * Take into account the tenant, as tenants can have different settings.
     * @param int $tenantId The tenant to load the data from. 0 is the site level data.
     * @return array
     */
    public function resolveThemeVariables(int $tenantId = 0): array
    {
        // The tenant must at least exist, and be enabled for theme settings. Otherwise just grab the site theme.
        $tenant = tenant::repository()->find($tenantId);
        $settings = new settings(\theme_config::load('kineo'), $tenantId);
        if ($tenantId > 0 && (!$tenant || !$settings->is_tenant_branding_enabled())) {
            $tenantId = 0;
            $settings = new settings(\theme_config::load('kineo'), $tenantId);
        }

        if (!empty($this->resolvedVariables[$tenantId])) {
            return $this->resolvedVariables[$tenantId];
        }

        $this->resolvedVariables[$tenantId] = [];
        $themeCategories = $settings->get_categories();

        // This is always an un-keyed array, so key it with the identifier to make the next step easier.
        /** @var Setting[] $variables */
        $variables = array_combine(
            array_map(
                function ($item) {
                    return $item->getIdentifier();
                },
                $this->getThemeSettings()->getSettings()
            ),
            $this->getThemeSettings()->getSettings()
        );

        // Load the default values from the JSON metadata.
        $defaultValues = [];
        foreach ($variables as $variable) {
            $defaultValues[$variable->getIdentifier()] = $variable->getDefault();
        }

        // Now add in the defaults from the _variables.scss file.
        $file = bundle::get_bundle_css_json_variables_file('theme_kineo');
        $content = $this->loadJsonFile($file->to_string());
        foreach ($content->vars ?? [] as $identifier => $cssVariable) {
            if ($cssVariable->type == 'var') {
                $defaultValues[$identifier] = '@' . $cssVariable->value;
            } else {
                $defaultValues[$identifier] = $cssVariable->value;
            }
        }

        // Collect the values of all settings as they have been configured on the front end.
        $configuredValues = [];
        foreach ($themeCategories as $category) {
            foreach ($category['properties'] as $property) {
                $configuredValues[$property['name']] = $property['value'];
            }
        }

        // Add in any images that have been uploaded.
        foreach ($settings->get_files() as $file) {
            if (!isset($variables[$file->get_ui_key()])) {
                continue;
            }
            $url = $file->get_current_url();
            if (!$url && $file->has_default()) {
                $url = $file->get_default_url();
            }
            $configuredValues[$file->get_ui_key()] = $url ?? false;
        }

        foreach ($variables as $variable) {
            $value = $this->findVariableValue($variable->getIdentifier(), $variables, $defaultValues, $configuredValues);
            if ($value) {
                $this->resolvedVariables[$tenantId][$variable->getIdentifier()] = $value;
            }
        }

        return $this->resolvedVariables[$tenantId];
    }

    /**
     * @param string $variable
     * @param int $tenantId
     * @return string|null
     */
    public function getResolvedVariableValue(string $variable, int $tenantId = 0): ?string
    {
        return $this->resolveThemeVariables($tenantId)[$variable] ?? null;
    }

    /**
     * Gets an array of theme settings defined for the tenant, which are going to be output to SCSS.
     * @param int $tenantId
     * @return string[]
     */
    public function getScssThemeVariables(int $tenantId = 0): array
    {
        $variables = $this->resolveThemeVariables($tenantId);
        $output = [];
        foreach ($this->getThemeSettings()->getSettings() as $setting) {
            if (!$setting->getOptions()[Setting::SCSS_VARIABLE_KEY]) {
                continue;
            }
            $output[$setting->getIdentifier()] = $variables[$setting->getIdentifier()] ?? $setting->getDefault();
        }
        return $output;
    }

    protected function loadThemeSettings()
    {
        global $CFG;
        require_once $CFG->libdir . '/adminlib.php';

        $settings = new Collection();
        $fileShas = [];

        $themeSettings = $this->loadJsonFile($this->srcDirectory . 'theme_settings.json');
        $fileShas[] = sha1_file($this->srcDirectory . 'theme_settings.json');
        foreach ($themeSettings->tabs ?? [] as $tab) {
            $tabObject = new Tab($tab->identifier, $tab->headings ?? []);
            $this->tabs[] = $tabObject;
            $settings->addTab($tabObject);

            $tabSettings = $this->loadJsonFile($this->srcDirectory . 'settings/' . $tab->identifier . '.json', false);
            $fileShas[] = sha1_file($this->srcDirectory . 'settings/' . $tab->identifier . '.json');
            foreach ($tabSettings as $setting) {
                $settings->addSetting(SettingsFactory::createSetting($setting, $tabObject));
            }
        }
        $this->addCustomSettings($settings);

        $fileSha = sha1(implode('', $fileShas));
        $storedRevision = get_config('theme_kineo', 'settings_revision');
        $needsUpgrade = get_config('theme_kineo', 'installrunning')
                        || (get_component_version('theme_kineo', 'disk') != get_component_version('theme_kineo', 'installed'));
        $revisionMatchesFileSha = !empty($storedRevision) && $fileSha === $storedRevision;

        if ($needsUpgrade || !$revisionMatchesFileSha) {
            $this->syncConfigWithSettingsJson($settings);
            set_config('settings_revision', $fileSha, 'theme_kineo');
        }

        $this->settings = $settings;
        $this->filterSettings();
        $this->update();
    }

    /**
     * @param Collection $settings
     */
    private function syncConfigWithSettingsJson(Collection $settings): void
    {
        global $DB;

        $mainSite[] = 0;
        $tenants = $DB->get_fieldset_select('tenant', 'id', 'id > 0');
        $allTenants = array_merge($mainSite, $tenants);
        foreach ($allTenants as $tenant) {
            $configuredSettings = new settings(\theme_config::load('kineo'), $tenant);
            $currentCategories = $configuredSettings->get_categories();
            $newCategories = [];

            foreach ($settings->getTabs() as $tab) {
                $id = $tab->getIdentifier();
                $newCategories[$id] = ['name' => $id, 'properties' => []];

                $currentCategory = array_filter($currentCategories, fn($category) => $category['name'] === $id);
                $currentCategory = reset($currentCategory) ?: ['name' => $id, 'properties' => []];

                foreach ($settings->getSettingsFromTab($id) as $setting) {
                    if ($setting->getType() === Setting::TYPE_FILE || $setting->getType() === Setting::TYPE_IMAGE) {
                        continue;
                    }

                    $currentProperty = array_filter(
                        $currentCategory['properties'],
                        fn($item) => $item['name'] === $setting->getIdentifier()
                    );

                    switch ($setting->getType()) {
                        case Setting::TYPE_EDITOR:
                            $type = 'html';
                            break;
                        case Setting::TYPE_TEXTAREA:
                        case Setting::TYPE_URL:
                            $type = 'text';
                            break;
                        default:
                            $type = 'value';
                    }

                    $currentProperty = reset($currentProperty)
                        ?: ['name' => $setting->getIdentifier(), 'type' => $type, 'value' => $setting->getDefault()];

                    $newCategories[$id]['properties'][] = $currentProperty;
                }
            }

            $tenantCategory = array_filter($currentCategories, fn($category) => $category['name'] === 'tenant');
            $tenantCategory = reset($tenantCategory) ?: ['name' => 'tenant', 'properties' => []];
            $newCategories['tenant'] = $tenantCategory;

            set_config("tenant_{$tenant}_settings", json_encode(array_values($newCategories)), 'theme_kineo');
        }

        theme_reset_all_caches();
        get_string_manager()->reset_caches();
    }

    /**
     * @param string $filePath The path to the JSON file to load
     * @param bool $mustExist If the file must exist and contain valid JSON. If false, no error is returned at all.
     * @return array|object
     */
    private function loadJsonFile(string $filePath, bool $mustExist = true)
    {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            if (!$mustExist) {
                return (object) [];
            }
            throw new \Exception('Theme Settings file does not exist, or not readable.');
        }

        $content = json_decode(file_get_contents($filePath));
        if (json_last_error() !== 0) {
            if (!$mustExist) {
                return (object) [];
            }
            throw new \Exception('Json Error decoding Theme Settings file: ' . json_last_error_msg());
        }

        return $content;
    }

    /**
     * Takes the JSON file and updates all the categories that we currently have stored in the database,
     * making sure that when the page loads it can successfully load the data upon saving the form.
     */
    private function update()
    {
        $themeSettings = new settings(\theme_config::load('kineo'), 0);
        $currentCategories = $themeSettings->get_categories();
        $newCategories = array_map(
            function ($item) {
                return ['name' => $item->getIdentifier(), 'properties' => []];
            },
            $this->tabs
        );

        foreach ($newCategories as &$newCategory) {
            foreach ($currentCategories as $category) {
                if ($newCategory['name'] === $category['name']) {
                    $newCategory['properties'] = $category['properties'];
                }
            }
        }

        $themeSettings->update_categories($newCategories);
    }

    /**
     * For a given default, try to resolve it if it contains @variables.
     * Recursively searches for the variable name until it either finds a non-variable value, or hits a dead end.
     * @param string $identifier
     * @param Setting[] $variables
     * @param array $defaultValues
     * @param array $configuredValues
     * @return string|null
     */
    private function findVariableValue(string $identifier, array $variables, array $defaultValues, array $configuredValues): ?string
    {
        // Either this setting is configured, or has a default value.
        // We only want to recurse if the value is an @variable.
        $value = $configuredValues[$identifier] ?? $defaultValues[$identifier] ?? null;
        if ($value === null || strpos($value, '@') !== 0) {
            return $value;
        }

        $nextVariable = substr($value, 1);
        unset($variables[$identifier]);

        return $this->findVariableValue($nextVariable, $variables, $defaultValues, $configuredValues);
    }

    /**
     * Defines the custom settings that are one of a kind and hard coded.
     * They are all added to a new "Custom" Tab.
     * If a Custom Tab already exists, an error is thrown as this is the fault of a developer and needs fixing.
     */
    private function addCustomSettings(Collection &$settings)
    {
        $existingTab = $settings->getTab('custom');
        if ($existingTab !== null) {
            throw new \coding_exception('A custom tab already exists and must be removed by a developer.');
        }

        // The headings used on the Custom Tab. This is just an array of strings, that are mapped to language strings.
        $headings = ['custom-css'];
        $customTab = new Tab('custom', $headings);
        $settings->addTab($customTab);

        $settings->addSetting(
            new Setting('custom-css', Setting::TYPE_TEXTAREA, '', $customTab, 'custom-css', true, [])
        );

        // Add Google Font setting.
        $fontsTab = $settings->getTab('fonts');
        $googleFontsSetting = new Setting(
            'google-fonts',
            Setting::TYPE_URL,
            '',
            $fontsTab,
            'font-family',
            true,
            ['pattern' => '^https:\/\/fonts\.googleapis\.com\/css.*']
        );
        // This needs to be at the top, so we rewrite the settings so this is guaranteed to be first.
        $settings = new Collection(
            array_merge([$googleFontsSetting], $settings->getSettings()),
            $settings->getTabs()
        );
        
        // Add in the Email Notification settings.
        $brandTab = $settings->getTab('brand');
        if ($brandTab === null) {
            return;
        }

        $heading = 'email-notifications';
        $brandTab->addHeading($heading);

        $settings->addSetting(
            new Editor(
                'formbrand_field_notificationshtmlheader',
                Setting::TYPE_EDITOR,
                '',
                $brandTab,
                $heading,
                true,
                [
                    Editor::COMPONENT_KEY => 'totara_tui',
                    Editor::AREA_KEY => 'formbrand_notifications_htmlheader',
                    Editor::TYPE_KEY => FORMAT_HTML,
                    Setting::SHOW_DEFAULT_KEY => false,
                    Setting::SHOW_IDENTIFIER_KEY => false,
                ]
            )
        );
        $settings->addSetting(
            new Editor(
                'formbrand_field_notificationshtmlfooter',
                Setting::TYPE_EDITOR,
                '',
                $brandTab,
                $heading,
                true,
                [
                    Editor::COMPONENT_KEY => 'totara_tui',
                    Editor::AREA_KEY => 'formbrand_notifications_htmlfooter',
                    Editor::TYPE_KEY => FORMAT_HTML,
                    Setting::SHOW_DEFAULT_KEY => false,
                    Setting::SHOW_IDENTIFIER_KEY => false,
                ]
            )
        );
        $settings->addSetting(
            new Setting(
                'formbrand_field_notificationstextfooter',
                Setting::TYPE_TEXTAREA,
                '',
                $brandTab,
                $heading,
                true,
                [
                    Setting::SHOW_DEFAULT_KEY => false,
                    Setting::SHOW_IDENTIFIER_KEY => false,
                ]
            )
        );
    }

    /**
     * @param string $elName
     * @param int $tenantId
     * @return mixed|null
     */
    public function getLegacySetting(string $elName, $tenantId = 0)
    {
        $allSettings = $this->getLegacySettings($tenantId);

        $filtered = array_filter(
            $allSettings,
            function ($setting) use ($elName){
                return $setting->name === $elName;
            }
        );

        return count($filtered) === 1 ? reset($filtered) : null;
    }

    /**
     * @param $elName
     * @param $value
     * @param int $tenantId
     */
    public function storeLegacySetting($elName, $value, $tenantId = 0)
    {
        $allSettings = $this->getLegacySettings($tenantId) ?? [];

        if ($this->getLegacySetting($elName, $tenantId)) {
            $allSettings = array_map(
                function ($setting) use ($elName, $value){
                    if ($elName === $setting->name) {
                        $setting->value = $value;
                    }
                    return $setting;
                },
                $allSettings
            );
        } else {
            $setting = new stdClass();
            $setting->name = $elName;
            $setting->value = $value;
            $allSettings[] = $setting;
        }

        set_config("tenant_{$tenantId}_settings_legacy", json_encode($allSettings), 'theme_kineo');
    }

    /**
     * @param int $tenantId
     * @return array|mixed
     */
    public function getLegacySettings($tenantId = 0)
    {
        return json_decode(get_config('theme_kineo', "tenant_{$tenantId}_settings_legacy")) ?? [];
    }

    /**
     * @param array $settings
     * @param int $tenantId
     * @return bool
     */
    public function storeLegacySettings(array $settings , $tenantId = 0): bool
    {
        $validated = [];

        foreach ($settings as $setting) {
            if (!empty($setting['name']) && !empty($setting['value'])) {
                $toDb = new stdClass();
                $toDb->name = $setting['name'];
                $toDb->value = $setting['value'];
                $validated[] = $toDb;
            }
        }

        return $validated ?
            set_config("tenant_{$tenantId}_settings_legacy", json_encode($validated), 'theme_kineo') :
            false;
    }
}