<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Ben Lobo <ben.lobo@kineo.com>
 */

namespace local_core\Watcher;

use local_core\Hook\PreUpgrade;
use local_core\Hook\PostUpgrade;
use local_core\DatabaseCleaner;
use local_core\Migration\LogHandler;
use local_core\Migration\MigrationService;
use moodle_exception;
use moodle_transaction;

class UpgradeWatcher
{
    /**
     * The Migration Service which will handle all custom Migrations.
     *
     * @var MigrationService
     */
    private static $migrationService;

    private static LogHandler $logHandler;

    /**
     * Watcher handler function to start a delegated transaction at the beginning of an upgrade.
     *
     * This handler will be executed when the 'upgrade_core' and/or 'upgrade_noncore' functions in 'server/lib/upgradelib.php'
     * are run.
     *
     * @param PreUpgrade $hook
     */
    public static function onPreUpgrade(PreUpgrade $hook)
    {
        // If this is a fresh new Totara install running right now, don't do any core TKE stuff.
        if (during_initial_install()) {
            return;
        }

        self::$logHandler = new LogHandler();

        // Make sure to only initialise the Migration Service once, should this hook get called twice.
        if (is_null(static::$migrationService)) {
            static::$migrationService = new MigrationService(self::$logHandler);
            static::$migrationService->prepare($hook);
        }
    }

    /**
     * Watcher handler function to carry out core TKE post upgrade tasks and commit or roll back changes as required.
     *
     * This handler will be executed after the 'upgrade_noncore' function is called in either 'server/admin/index.php' or
     * 'server/admin/cli/upgrade.php' depending on whether the upgrade is being run via the front-end or the command line.
     *
     * As this handler will be executed even if neither the 'upgrade_core' or 'upgrade_noncore' functions were actually run,
     * we need to check if the Migration Service was started before we attempt to use it.
     *
     * We also carry out post upgrade database cleanup tasks as required. The cleanup tasks will only run
     * if the upgrade is running as a CLI script. This is so that the tasks can interactively prompt for input regarding
     * whether or not to run the individual tasks.
     *
     * @param PostUpgrade $hook
     * @throws moodle_exception
     */
    public static function onPostUpgrade(PostUpgrade $hook)
    {
        if (!is_null(static::$migrationService)) {
            static::$migrationService->process();
        }

        // Don't attempt any cleanup if NOT running as CLI script as we want to be able to allow command line interaction.
        if (!$hook->isCliRequest()) {
            return;
        }

        self::$logHandler->log("Attempting post upgrade database cleanup...");

        $dbCleaner = DatabaseCleaner::getInstance(self::$logHandler);

        if (!is_null(static::$migrationService)) {
            $params = static::$migrationService::getCliParams();
            $dbCleaner->setInteractive(empty($params['non-interactive']));
            $dbCleaner->setFixPlugins(!empty($params['fix-plugins']));
            $dbCleaner->setFixSchema(!empty($params['fix-database-schema']));
        }

        // Find and remove any missing plugins.
        $dbCleaner->missingPluginCleanup();

        // Find and fix any database schema errors.
        $dbCleaner->databaseSchemaCleanup();

        self::$logHandler->finish();
    }
}
