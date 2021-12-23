<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon Adams <simon.adams@kineo.com>
 */

namespace theme_kineo\Service;


use coding_exception;
use context_system;
use context_tenant;
use core\files\file_helper;
use core\record\tenant;
use core\theme\settings;
use Exception;
use stdClass;
use theme_config;
use theme_kineo\SettingsResolver;

class ThemeBackupService
{
    /** @var string[] */
    private const VALID_PROPERTIES = [
        'name' => 'name',
        'type' => 'type',
        'value' => 'value',
    ];

    /** @var int */
    private int $tenantId;

    /** @var string[] */
    private array $legacyFileAreas = ['custom_fonts', 'custom_images'];

    public function __construct(int $tenantId = 0)
    {
        global $CFG;

        if (!empty($CFG->tenantsenabled) && $tenantId) {
            tenant::fetch($tenantId);
            $this->tenantId = $tenantId;
        } else {
            $this->tenantId = $tenantId;
        }
    }

    /**
     * @return array
     * @throws coding_exception
     */
    public function createBackup(): array
    {
        $backup = [];
        $backup['settings'] = $this->getSettings();
        $backup['files'] = $this->getFiles();
        $backup['legacy_files'] = $this->getLegacyFiles();
        $backup['legacy_settings'] = $this->getLegacySettings();

        return $backup;
    }

    /**
     * @param array $backup
     * @return bool
     */
    public function restoreBackup(array $backup): bool
    {
        if (empty($backup['settings'])
            && empty($backup['files'])
            && empty($backup['legacy_files'])
            && empty($backup['legacy_settings'])) {
            return false;
        }

        $this->storeSettings($backup['settings']);
        $this->storeFiles($backup['files']);
        $this->storeLegacyFiles($backup['legacy_files']);
        SettingsResolver::getInstance()->storeLegacySettings($backup['legacy_settings'], $this->tenantId);

        return true;
    }

    /**
     * @return array
     * @throws coding_exception
     */
    private function getFiles(): array
    {
        $settings = new settings(theme_config::load('kineo'), $this->tenantId);
        $themeFiles = $settings->get_files();
        $rawFiles = [];

        foreach ($themeFiles as $themeFile) {
            if (!$currentFile = $themeFile->get_current_file()) {
                continue;
            }
            if (!$currentFile->is_directory()) {
                $backupFile = [];
                $backupFile['file_id'] = $themeFile::get_id();
                $backupFile['file_name'] = $currentFile->get_filename();
                try {
                    $backupFile['content'] = base64_encode($currentFile->get_content());
                } catch (Exception $e) {
                    continue;
                }
                $rawFiles[] = $backupFile;
            }
        }
        return $rawFiles;
    }

    /**
     * @return array
     */
    private function getLegacyFiles(): array
    {
        global $DB;

        $rawFiles = [];

        foreach ($this->legacyFileAreas as $fileArea) {
            $fh = new file_helper('theme_kineo', $fileArea, context_system::instance());
            if (!$itemId = $DB->get_field('config_plugins', 'id', ['plugin' => 'theme_kineo', 'name' => "tenant_{$this->tenantId}_settings"])) {
                continue;
            }
            $fh->set_item_id($itemId);
            $files = $fh->get_stored_files();
            foreach ($files as $file) {
                try {
                    $backupFile['file_name'] = $file->get_filename();
                    $backupFile['filearea'] = $fileArea;
                    $backupFile['content'] = base64_encode($file->get_content());
                    $rawFiles[] = $backupFile;
                } catch (Exception $e) {
                    continue;
                }
            }
        }
        return $rawFiles;
    }

    /**
     * @return array
     */
    private function getSettings(): array
    {
        $allSettings = theme_config::load('kineo');

        $key = "tenant_{$this->tenantId}_settings";
        if (empty($allSettings->settings->$key)) {
            throw new Exception(sprintf('Settings not found for tenantid %s', $this->tenantId));
        }
        $settings = $allSettings->settings->$key;
        return json_decode($settings, true);
    }

    /**
     * @return array
     */
    private function getLegacySettings(): array
    {
        return convert_to_array(SettingsResolver::getInstance()->getLegacySettings($this->tenantId));
    }

    /**
     * Restore overwrites any existing files and removes those not present in the restore file
     * @param array $files
     */
    private function storeFiles(array $files): void
    {
        $settings = new settings(theme_config::load('kineo'), $this->tenantId);
        $themeFiles = $settings->get_files();
        $context = $this->tenantId ? context_tenant::instance($this->tenantId) : context_system::instance();
        foreach ($themeFiles as $themeFile) {
            $file = array_filter(
                $files,
                function ($file) use ($themeFile) {
                    return $file['file_id'] === $themeFile::get_id();
                }
            );
            if (count($file) != 1) {
                $themeFile->delete();
                continue;
            }

            $file = reset($file);
            $itemId = $themeFile->get_item_id($this->tenantId, 'kineo');

            if ($currentFile = $themeFile->get_current_file($itemId, $context)) {
                $currentFile->delete();
            }

            $record = new stdClass();
            $record->filearea = $themeFile->get_area();
            $record->component = $themeFile->get_component();
            $record->itemid = $itemId;
            $record->filename = $file['file_name'];
            $record->filepath = '/';
            $record->contextid = $context->id;
            get_file_storage()->create_file_from_string($record, base64_decode($file['content']));
        }
    }

    /**
     * Restore overwrites any existing files and removes those not present in the restore file
     * @param array $backupFiles
     */
    private function storeLegacyFiles(array $backupFiles): void
    {
        global $DB;

        $itemId = $DB->get_field('config_plugins', 'id', ['plugin' => 'theme_kineo', 'name' => "tenant_{$this->tenantId}_settings"]);
        $context = context_system::instance();

        foreach ($this->legacyFileAreas as $fileArea) {
            $fh = new file_helper('theme_kineo', $fileArea, $context);
            $fh->set_item_id($itemId);
            $storedFiles = $fh->get_stored_files();
            foreach ($storedFiles as $file) {
                $file->delete();
            }
            foreach ($backupFiles as $backupFile) {
                if ($backupFile['filearea'] === $fileArea) {
                    $record = new stdClass();
                    $record->filearea = $fileArea;
                    $record->component = 'theme_kineo';
                    $record->itemid = $itemId;
                    $record->filename = $backupFile['file_name'];
                    $record->filepath = '/';
                    $record->contextid = $context->id;
                    get_file_storage()->create_file_from_string($record, base64_decode($backupFile['content']));
                }
            }
        }
    }

    /**
     * Restore overwrites existing settings and removes those not present in the file
     * @param array $fileSettingsCategories
     * @throws coding_exception
     */
    private function storeSettings(array $fileSettingsCategories): void
    {
        $themeSettings = new settings(theme_config::load('kineo'), $this->tenantId);
        $validSettings = SettingsResolver::getInstance()->getThemeSettings();

        $toRestore = [];

        foreach ($fileSettingsCategories as $fileSettingsCategory) {
            $validCategoryInFile =
                !empty($fileSettingsCategory['name']) && $validSettings->getTab($fileSettingsCategory['name']);

            $propertiesInCategory = !empty($fileSettingsCategory['properties']);

            if (!$validCategoryInFile || !$propertiesInCategory) {
                continue;
            }

            $propertiesToUpdate = [];

            // Only allow properties with valid keys from the given file, everything else is removed
            foreach ($fileSettingsCategory['properties'] as $fileProperties) {
                $cleanedProperties = array_intersect_key($fileProperties, self::VALID_PROPERTIES);
                if (empty(array_diff_key(self::VALID_PROPERTIES, $cleanedProperties))) {
                    $propertiesToUpdate[] = array_diff_assoc($cleanedProperties, self::VALID_PROPERTIES);
                }
            }

            if ($propertiesToUpdate) {
                $categoryToUpdate['name'] = $fileSettingsCategory['name'];
                $categoryToUpdate['properties'] = $propertiesToUpdate;
                $toRestore[] = $categoryToUpdate;
            }
        }

        if ($themeSettings->is_initial_tenant_branding()) {
            array_unshift(
                $toRestore,
                [
                    'name' => 'tenant',
                    'properties' => [
                        [
                            'name' => 'formtenant_field_tenant',
                            'type' => 'boolean',
                            'value' => 'false',
                        ],
                    ],
                ]
            );
        }

        $themeSettings->update_categories($toRestore);
    }
}