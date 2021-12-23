<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

use core\theme\settings as theme_settings;

/**
 * @group tke
 * @group kineo
 * @group theme_kineo
 */
class backup_test extends \core_phpunit\testcase
{
    public function setUp(): void
    {
        parent::setUp();
        $settings = \theme_kineo\SettingsResolver::getInstance();
        $settings->reloadThemeSettings();
    }

    public function test_backup_file_creation_and_restore()
    {
        $config = \theme_config::load('kineo');
        $settings = new theme_settings($config, 0);

        $service = new \theme_kineo\Service\ThemeBackupService();
        $backup = $service->createBackup();

        $this->assertSame($settings->get_categories(), $backup['settings']);
        $service->restoreBackup($backup);

        // Ensure caches are purged before we reload settings.
        cache::make('core', 'theme_setting_categories')->purge();

        $this->assertSame($settings->get_categories(), $backup['settings']);
    }

    public function test_restoring_modified_backup()
    {
        $config = \theme_config::load('kineo');
        $settings = new theme_settings($config, 0);
        $currentCategories = $settings->get_categories();

        $newValue = 'rgba(12, 198, 201, 0.54829174)';

        $update = [];
        foreach ($currentCategories as $categoryKey => $category) {
            $update[$categoryKey] = $category;
            if ($category['name'] !== 'brand') {
                continue;
            }
            foreach ($category['properties'] as $propertyKey => $property) {
                if ($property['name'] !== 'color-black') {
                    continue;
                }

                $update[$categoryKey]['properties'][$propertyKey]['value'] = $newValue;
            }
        }

        $this->assertNotSame($currentCategories, $update);

        $settings->validate_categories($update);
        $settings->update_categories($update);

        $service = new \theme_kineo\Service\ThemeBackupService();
        $backup = $service->createBackup();

        $this->assertSame($update, $backup['settings']);
        $service->restoreBackup($backup);

        // Ensure caches are purged before we reload settings.
        cache::make('core', 'theme_setting_categories')->purge();
        $this->assertSame($settings->get_categories(), $backup['settings']);

        $resolver = \theme_kineo\SettingsResolver::getInstance();
        $this->assertSame($newValue, $resolver->getResolvedVariableValue('color-black'));
    }
}