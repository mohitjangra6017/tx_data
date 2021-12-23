<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_core\Hook\ReportBuilder;

use core\hook\admin_setting_changed;
use core\notification;

class PostgresWorkMem
{
    public static string $limit = '512MB';

    public static string $default = '8MB';

    public static function setTempWorkMem(): void
    {
        global $DB;

        if (!self::isWorkMemEnabled() || $DB->get_dbfamily() !== 'postgres') {
            return;
        }

        // Because this setting can be set in the config.php file, let's do another check here to make sure it's suitable to include directly in a SQL query.
        $value = self::getWorkMemValue();
        if (!get_real_size($value)) {
            return;
        }

        try {
            $DB->execute("SET work_mem = '{$value}'");
        } catch (\Exception $e) {
            debugging(
                'Unable to set work_mem. Please check the local_core work mem settings. Reason: ' .
                $e->getMessage(),
                DEBUG_DEVELOPER
            );
            return;
        }
    }

    public static function isWorkMemEnabled(): bool
    {
        return get_config('local_core', 'work_mem_enabled') ?: false;
    }

    public static function getWorkMemValue(): string
    {
        return get_config('local_core', 'work_mem') ?: static::$default;
    }

    public static function settingUpdated(admin_setting_changed $hook): void
    {
        if ($hook->name !== 'work_mem') {
            return;
        }

        if (!preg_match('/^[0-9]+[KMG]B$/', $hook->newvalue)) {
            notification::error(get_string('error:invalid_work_mem', 'local_core', $hook->newvalue));
            set_config($hook->name, $hook->oldvalue, 'local_core');
            return;
        }

        $actualSize = get_real_size($hook->newvalue);

        if ($actualSize === 0) {
            notification::error(get_string('error:invalid_work_mem_size', 'local_core', $hook->newvalue));
            set_config($hook->name, $hook->oldvalue, 'local_core');
        }

        if ($actualSize > get_real_size(static::$limit)) {
            notification::error(get_string('error:work_mem_exceeds_limit', 'local_core', ['value' => $hook->newvalue, 'limit' => static::$limit]));
            set_config($hook->name, $hook->oldvalue, 'local_core');
        }
    }
}