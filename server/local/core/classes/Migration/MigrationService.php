<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_core\Migration;

use core_component;
use dml_transaction_exception;
use local_core\Hook\PreUpgrade;
use LogicException;
use moodle_transaction;
use stdClass;
use Throwable;

final class MigrationService
{
    private bool $dryRun = false;

    private bool $isCliRequest = false;

    private moodle_transaction $transaction;

    private LogHandler $logHandler;

    private bool $interactive = true;

    private bool $execute = false;

    private static array $cliParams = [];

    /** @var Migration[] */
    private array $migrations = [];

    public const LOG_SECTION = 'SYSTEM';

    public function __construct(LogHandler $logHandler)
    {
        $this->logHandler = $logHandler;
    }

    public static function modifyCliParams(array $options, array $unrecognized): array
    {
        $longOptions = [
            // First the core options, we always need these to mimic the core upgrade.php.
            'non-interactive' => false,
            'allow-unstable' => false,
            'help' => false,
            'lang' => $options['lang'],
            // Now our custom options.
            'execute' => false,
            'fix-database-schema' => false,
            'fix-plugins' => false,
        ];
        $shortOptions = [
            // Core's short options first.
            'h' => 'help',
            // Now our short options.
        ];

        self::$cliParams = cli_get_params($longOptions, $shortOptions);
        if (self::getCliParams()['help']) {
            cli_write(
                <<<HELP
=== T:KE Migration Service ===
T:KE Migrations provides some extra options around the core Totara CLI upgrade below.

--execute                Executes the upgrade and migrations. By default, running this upgrade will cause it to dry run. 
                         You must pass this flag if you are using --non-interactive. 
                         If running interactively and you do not pass this flag, you will be prompted. 
--fix-database-schema    Triggers the database schema fixes if you are running non-interactively.
                         Will go through the database and check for any schema inconsistencies between the database and the code and fix them.
--fix-plugins            Triggers the plugin cleanup fixes if you are running non-interactively.
                         Finds any plugins in the database that do not exist in code and removes them.
                         
=== Core Totara Upgrade ===

HELP
            );
        }
        return self::$cliParams;
    }

    public static function getCliParams(): ?array
    {
        return self::$cliParams[0] ?? null;
    }

    /**
     * @param PreUpgrade $hook
     * @throws dml_transaction_exception
     */
    public function prepare(PreUpgrade $hook): void
    {
        global $DB, $USER;

        $this->isCliRequest = $hook->isCliRequest();
        $this->interactive = $hook->isCliRequest() && empty(self::getCliParams()['non-interactive']);
        $this->execute = $hook->isCliRequest() && !empty(self::getCliParams()['execute']);

        // Make sure the log handler is finalised before anything else so that everything is logged correctly.
        $this->setupLogHandler();

        $this->setupDryRun($hook);
        if (!$this->isCliRequest) {
            $this->logHandler->log('This upgrade is being run by User ID ' . $USER->id);
        }

        // Setup a transaction now. This will be committed/rolled back in the _finalise_ method.
        $this->transaction = $DB->start_delegated_transaction();
        $this->logHandler->log('Database transaction started');

        $this->collectMigrations();

        $this->setupExceptionHandler();

        $this->logTotaraUpgradeDetails();

        $this->logHandler->log('Migration System prepared. Handing back to Totara to perform the upgrade...');
        if ($this->isCliRequest) {
            $this->logHandler->startCapturingStdOut();
        }
    }

    public function process(): void
    {
        global $DB;

        if ($this->isCliRequest) {
            $this->logHandler->stopCapturingStdOut();
        }

        $this->logHandler->log('Running all Migrations');

        foreach ($this->migrations as $migration) {
            $class = get_class($migration);
            $sectionName = substr($class, strrpos($class, '\\') + 1);

            // Fire up a log section so the Migration logs get prefixed, and start tracking the time it takes to run the Migration.
            $this->logHandler->log('Starting Migration ' . $class)->startSection($sectionName);
            $start = microtime(true);

            try {
                $migration->execute();
            } catch (Throwable $e) {
                $this->handleException($e);
                exit(1);
            }

            $end = microtime(true);
            $this->logHandler
                ->stopSection($sectionName)
                ->log("Finished Migration {$class}, took " . ($end - $start) . " seconds")
            ;

            $this->logHandler->log('Testing if the Migration can be run again')->startSection($sectionName);
            if ($migration->canMigrate()) {
                throw new LogicException("Executed Migration can still be executed. Developer must ensure their Migration returns false for {$class}::canMigrate");
            }
            $this->logHandler->stopSection($sectionName)->log('Migration has been finalised and will not run again');
        }

        $this->logHandler->log('All Migrations completed successfully');

        if ($this->dryRun) {
            $this->logHandler->log('Rolling back the database as dry run is enabled');
            $DB->force_transaction_rollback();
            $this->logHandler->log('Database rolled back - all changes removed');
        } else {
            $this->logHandler->log('No dry run - Committing all database changes');
            $DB->commit_delegated_transaction($this->transaction);
            $this->logHandler->log('All database changes committed');
        }

        $this->restoreExceptionHandler();

        $this->logHandler->log('Migration System completed');
    }

    private function setupLogHandler(): void
    {
        if ($this->isCliRequest) {
            cli_heading('T:KE Migration System has taken control of the Totara Upgrade from here.');
        }
        $this->logHandler->startSection(self::LOG_SECTION);
        $this->logHandler->outputToStdOut($this->isCliRequest);
        $this->logHandler->log('Migration System starting up');
        if ($this->isCliRequest) {
            $this->logHandler->log('Running in CLI mode');
        } else {
            $this->logHandler->log('Running in web mode');
        }
    }

    private function setupDryRun(PreUpgrade $hook): void
    {
        if ($this->isCliRequest) {
            if ($this->interactive && !$this->execute) {
                $result = cli_input(
                    "Do you want to carry out a dry run upgrade (no changes will be made to the database)? (y/n)",
                    'y',
                    ['y', 'n'],
                    false
                );
                $this->dryRun = $result == 'y';
                $reason = 'Mode chosen via the CLI.';
            } else {
                $this->dryRun = !$this->execute;
                $reason = 'Mode chosen via CLI parameters.';
            }
        } else {
            $this->dryRun = $hook->isDryRun();
            $reason = 'Mode chosen via the config.php file.';
        }

        if ($this->dryRun) {
            $this->logHandler->log('Dry run mode enabled. No changes will be made to the database. ' . $reason);
        } else {
            $this->logHandler->log('Dry run mode disabled. All changes to the database will be persisted. ' . $reason);
        }
    }

    private function collectMigrations(): void
    {
        $this->logHandler->log('Searching for all Migrations...');
        foreach (core_component::get_namespace_classes('Migration', Migration::class) as $class) {
            $this->logHandler->log('Found Migration: ' . $class);
            $sectionName = substr($class, strrpos($class, '\\') + 1);

            try {
                /** @var Migration $migration */
                $migration = new $class($this->logHandler, $this->dryRun);

                $this->logHandler->startSection($sectionName);
                if ($migration->canMigrate()) {
                    $this->logHandler->setSection(self::LOG_SECTION)->log('Migration can migrate, preparing...');
                    $migration->prepare();
                    $this->logHandler->stopSection($sectionName)->log('Migration prepared');
                } else {
                    $this->logHandler->stopSection($sectionName)->log('Migration cannot run, skipping');
                    continue;
                }
            } catch (Throwable $e) {
                $this->logHandler->stopSection($sectionName);
                $this->logHandler->logThrowable($e, "Migration {$class} failed to initialise");
                continue;
            }

            $this->migrations[] = $migration;
        }
        $this->logHandler->log('Found ' . count($this->migrations) . ' Migration(s); They will be migrated after the Totara upgrade completes');
    }

    /**
     * This is a copy of the core function _core_component::get_all_versions_hash_.
     * It will gather up all the plugins and core Totara version changes to summarise in the log.
     */
    private function logTotaraUpgradeDetails(): void
    {
        $loadVersionFile = function ($file) {
            $plugin = new stdClass();
            $plugin->version = null;
            $module = $plugin;
            include($file);
            return $plugin;
        };

        $loadTotaraVersion = function () {
            global $CFG;
            $TOTARA = new stdClass();
            require($CFG->dirroot . '/version.php');
            return $TOTARA->build;
        };

        $versionString = '';

        $newTotaraVersion = $loadTotaraVersion();
        $oldTotaraVersion = get_config(null, 'totara_build');
        if ($oldTotaraVersion != $newTotaraVersion) {
            $versionString .= PHP_EOL . "Core Totara: From {$oldTotaraVersion} to {$newTotaraVersion}";
        }

        $types = core_component::get_plugin_types();
        foreach ($types as $type => $dir) {
            foreach (core_component::get_plugin_list($type) as $pluginName => $path) {
                $plugin = $loadVersionFile($path . '/version.php');
                $oldPluginVersion = get_config($type . '_' . $pluginName, 'version');
                if ($oldPluginVersion != $plugin->version) {
                    $versionString .= PHP_EOL . get_string('pluginname', $type . '_' . $pluginName);
                    $versionString .= $oldPluginVersion
                        ? ": Update from {$oldPluginVersion} to {$plugin->version}"
                        : ": Install {$plugin->version}";
                }
            }
        }

        $this->logHandler->log('The following Totara and plugin updates will take place:' . $versionString);
    }

    private function setupExceptionHandler(): void
    {
        set_exception_handler([$this, 'handleException']);
    }

    private function restoreExceptionHandler(): void
    {
        restore_exception_handler();
    }

    /**
     * Our overridden exception handler. All the time Migrations are happening, any thrown exceptions will end up here instead of Moodle.
     * @param Throwable $throwable
     */
    private function handleException(Throwable $throwable): void
    {
        global $DB;
        $this->logHandler->restartSection(self::LOG_SECTION)->logThrowable($throwable);
        $DB->force_transaction_rollback();
        $this->logHandler->log('All transactions rolled back. No changes saved to the database.');
    }
}