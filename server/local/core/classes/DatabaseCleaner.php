<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Ben Lobo <ben.lobo@kineo.com>
 */

namespace local_core;

use core_plugin_manager;
use Exception;
use local_core\Migration\LogHandler;

/**
 * Database Cleaner class to handle db migration cleanup tasks (e.g. uninstall missing plugins, fix db schema errors).
 *
 * @package local_core
 */
class DatabaseCleaner
{
    private static ?DatabaseCleaner $instance = null;

    private core_plugin_manager $pluginMan;

    private bool $interactive = true;

    private bool $fixPlugins = false;

    private bool $fixSchema = false;

    private LogHandler $logHandler;

    private function __construct(LogHandler $logHandler)
    {
        $this->pluginMan = core_plugin_manager::instance();
        $this->logHandler = $logHandler;
    }

    public static function getInstance(LogHandler $logHandler): self
    {
        if (static::$instance === null) {
            static::$instance = new static($logHandler);
        }
        return static::$instance;
    }

    /**
     * @param bool $interactive
     * @return self
     */
    public function setInteractive(bool $interactive): self
    {
        $this->interactive = $interactive;
        return $this;
    }

    /**
     * @param bool $fixPlugins
     * @return self
     */
    public function setFixPlugins(bool $fixPlugins): self
    {
        $this->fixPlugins = $fixPlugins;
        return $this;
    }

    /**
     * @param bool $fixSchema
     * @return self
     */
    public function setFixSchema(bool $fixSchema): self
    {
        $this->fixSchema = $fixSchema;
        return $this;
    }

    /**
     * Finds any missing plugins and tries to uninstall them. This will only run if called during CLI script execution.
     *
     * @throws \moodle_exception
     */
    public function missingPluginCleanup()
    {
        // Don't continue if not running as a CLI script.
        if (!defined('CLI_SCRIPT') || !CLI_SCRIPT) {
            return;
        }

        $pluginInfo = $this->pluginMan->get_plugins();
        $missingPlugins = [];

        // Find any missing plugins.
        foreach ($pluginInfo as $type => $plugins) {
            foreach ($plugins as $name => $plugin) {
                if ($plugin->get_status() === \core_plugin_manager::PLUGIN_STATUS_MISSING) {
                    $missingPlugins[$name] = $plugin;
                }
            }
        }

        // If any missing plugins were detected, prompt user to confirm whether or not to uninstall them all.
        if (!empty($missingPlugins)) {
            $pluginNamesStr = implode(', ', array_keys($missingPlugins));
            cli_writeln("The following plugins are missing:" . PHP_EOL . $pluginNamesStr);
            if ($this->interactive && !$this->fixPlugins) {
                $result = cli_input('Do you want to remove these plugins? (y/n)', '', ['y', 'n'], false);
                $this->fixPlugins = $result == 'y';
            }

            if ($this->fixPlugins) {
                foreach ($missingPlugins as $name => $plugin) {
                    $this->uninstallPlugin($plugin);
                }
            } else {
                $this->logHandler->log("Not uninstalling missing plugins.");
            }
        } else {
            $this->logHandler->log("No missing plugins found.");
        }

    }

    /**
     * Uninstalls a single plugin.
     *
     * @param $plugin
     * @throws \moodle_exception
     */
    private function uninstallPlugin($plugin)
    {
        $this->logHandler->log('Uninstalling missing plugin: ' . $plugin->component);

        try {
            $pluginName = $this->pluginMan->plugin_name($plugin->component);
            if (!$this->pluginMan->can_uninstall_plugin($plugin->component)) {
                throw new \moodle_exception('err_cannot_uninstall_plugin', 'core_plugin', '',
                    array('plugin' => $plugin->component),
                    'core_plugin_manager::can_uninstall_plugin() returned false');
            }

            $progress = new \progress_trace_buffer(new \text_progress_trace(), false);
            $this->pluginMan->uninstall_plugin($plugin->component, $progress);
            $progress->finished();

            $this->logHandler->log("Uninstalled plugin '$pluginName'.");

        } catch (Exception $e) {
            $this->logHandler->logThrowable($e, "Unable to install plugin '{$plugin->component}' Reason: {$e->getMessage()}.");
        }
    }

    /**
     * Checks the installed database schema against the full xmldb_structure schema describing all known tables.
     *
     * This is essentially a version of 'database_manager->check_database_schema()' modified to fix any errors that are
     * found between the known database schema and the actual database tables.
     *
     * Not all schema errors are attempted to be fixed in this process. Any that are not attempted are displayed in a
     * listing.
     *
     * This will only run if called during CLI script execution.
     *
     * @param array|null $options
     * @throws \coding_exception
     */
    public function databaseSchemaCleanup(array $options = null)
    {
        global $DB;

        // Don't continue if not running as a CLI script.
        if (!defined('CLI_SCRIPT') || !CLI_SCRIPT) {
            return;
        }

        if ($this->interactive && !$this->fixSchema) {
            $result = cli_input("Do you want to attempt to fix database schema issues (e.g. removal of unexpected tables)? (y/n)", '', ['y', 'n'], false);
            $this->fixSchema = $result == 'y';
        }

        if (!$this->fixSchema) {
            $this->logHandler->log("Not attempting to fix database scheme issues.");
            return;
        }

        $manager = $DB->get_manager();
        $schema = $manager->get_install_xml_schema();

        $allOptions = array(
            'extratables' => true,
            'missingtables' => true,
            'extracolumns' => true,
            'missingcolumns' => true,
            'changedcolumns' => true,
        );

        $typesMap = array(
            'I' => XMLDB_TYPE_INTEGER,
            'R' => XMLDB_TYPE_INTEGER,
            'N' => XMLDB_TYPE_NUMBER,
            'F' => XMLDB_TYPE_NUMBER, // Nobody should be using floats!
            'C' => XMLDB_TYPE_CHAR,
            'X' => XMLDB_TYPE_TEXT,
            'B' => XMLDB_TYPE_BINARY,
            'T' => XMLDB_TYPE_TIMESTAMP,
            'D' => XMLDB_TYPE_DATETIME,
        );

        $options = (array)$options;
        $options = array_merge($allOptions, $options);

        $errors = array();

        /** @var string[] $dbTables */
        $dbTables = $DB->get_tables(false);
        /** @var \xmldb_table[] $tables */
        $tables = $schema->getTables();

        foreach ($tables as $table) {

            $tableName = $table->getName();

            if ($options['missingtables']) {
                // Check for missing tables.
                if (empty($dbTables[$tableName])) {
                    try {
                        $this->logHandler->log("Creating missing table '$tableName'.");
                        $manager->create_table($table);
                    } catch (\Exception $e) {
                        $this->logHandler->logThrowable($e, "Unable to create missing table '$tableName'.");
                    }
                    continue;
                }
            }

            /** @var \database_column_info[] $dbFields */
            $dbFields = $DB->get_columns($tableName, false);
            /** @var \xmldb_field[] $fields */
            $fields = $table->getFields();

            foreach ($fields as $field) {
                $fieldName = $field->getName();
                // Check for missing fields.
                if (empty($dbFields[$fieldName])) {
                    if ($options['missingcolumns']) {
                        try {
                            $this->logHandler->log("Creating missing field '$fieldName' of table '$tableName'.");
                            $manager->add_field($table, $field);
                        } catch (\Exception $e) {
                            $this->logHandler->logThrowable($e, "Unable to create missing field '$fieldName' of table '$tableName'.");
                        }
                    }
                } else if ($options['changedcolumns']) {
                    $dbField = $dbFields[$fieldName];

                    if (!isset($typesMap[$dbField->meta_type])) {
                        $errors[$tableName][] = "column '$fieldName' has unsupported type '$dbField->meta_type'";
                    } else {
                        $dbType = $typesMap[$dbField->meta_type];
                        $type = $field->getType();
                        if ($type == XMLDB_TYPE_FLOAT) {
                            $type = XMLDB_TYPE_NUMBER;
                        }
                        if ($type != $dbType) {
                            if ($expected = array_search($type, $typesMap)) {
                                $errors[$tableName][] = "column '$fieldName' has incorrect type '$dbField->meta_type', expected '$expected'";
                            } else {
                                $errors[$tableName][] = "column '$fieldName' has incorrect type '$dbField->meta_type'";
                            }
                        } else {
                            if ($field->getNotNull() != $dbField->not_null) {
                                if ($field->getNotNull()) {
                                    $errors[$tableName][] = "column '$fieldName' should be NOT NULL ($dbField->meta_type)";
                                } else {
                                    $errors[$tableName][] = "column '$fieldName' should allow NULL ($dbField->meta_type)";
                                }
                            }
                            if ($dbType == XMLDB_TYPE_TEXT) {
                                // No length check necessary - there is one size only now.

                            } else if ($dbType == XMLDB_TYPE_NUMBER) {
                                if ($field->getType() == XMLDB_TYPE_FLOAT) {
                                    // Do not use floats in any new code, they are deprecated in XMLDB editor!

                                } else if ($field->getLength() != $dbField->max_length or $field->getDecimals() != $dbField->scale) {
                                    $size = "({$field->getLength()},{$field->getDecimals()})";
                                    $dbSize = "($dbField->max_length,$dbField->scale)";
                                    if ($size === '(40,20)' and $dbSize === '(38,20)' and $tableName === 'question_numerical_units'
                                        and $fieldName === 'multiplier' and $DB->get_dbfamily() === 'mssql') {
                                        // TODO: Remove this hack after MDL-32113 gets fixed.
                                    } else {
                                        $errors[$tableName][] = "column '$fieldName' size is $dbSize, expected $size ($dbField->meta_type)";
                                    }
                                }

                            } else if ($dbType == XMLDB_TYPE_CHAR) {
                                // This is not critical, but they should ideally match.
                                if ($field->getLength() != $dbField->max_length) {
                                    $errors[$tableName][] = "column '$fieldName' length is $dbField->max_length, expected {$field->getLength()} ($dbField->meta_type)";
                                }

                                // Check field constraints but not for old MySQL.
                                if (!($DB->get_dbfamily() === 'mysql' && version_compare($DB->get_server_info()['version'], '8', '<'))) {
                                    if ($field->getAllowedValues()) {
                                        if (!$manager->field_allowed_values_constraint_exists($table, $field)) {
                                            $errors[$tableName][] = "column '$fieldName' does not have expected allowed values constraint";
                                        }
                                    } else {
                                        if ($manager->field_allowed_values_constraint_exists($table, $field)) {
                                            $errors[$tableName][] = "column '$fieldName' has unexpected allowed values constraint";
                                        }
                                    }
                                }

                            } else if ($dbType == XMLDB_TYPE_INTEGER) {
                                // Integers may be bigger in some DBs.
                                $length = $field->getLength();
                                if ($length > 18) {
                                    // Integers are not supposed to be bigger than 18.
                                    $length = 18;
                                }
                                if ($length > $dbField->max_length) {
                                    $errors[$tableName][] = "column '$fieldName' length is $dbField->max_length, expected at least {$field->getLength()} ($dbField->meta_type)";
                                }

                                // Check field constraints but not for old MySQL.
                                if (!($DB->get_dbfamily() === 'mysql' && version_compare($DB->get_server_info()['version'], '8', '<'))) {
                                    if ($field->getAllowedValues()) {
                                        if (!$manager->field_allowed_values_constraint_exists($table, $field)) {
                                            $errors[$tableName][] = "column '$fieldName' does not have expected allowed values constraint";
                                        }
                                    } else {
                                        if ($manager->field_allowed_values_constraint_exists($table, $field)) {
                                            $errors[$tableName][] = "column '$fieldName' has unexpected allowed values constraint";
                                        }
                                    }
                                }

                            } else if ($dbType == XMLDB_TYPE_BINARY) {
                                // No checks needed for binary types.

                            } else if ($dbType == XMLDB_TYPE_TIMESTAMP) {
                                $errors[$tableName][] = "column '$fieldName' is a timestamp, this type is not supported ($dbField->meta_type)";
                                continue;

                            } else if ($dbType == XMLDB_TYPE_DATETIME) {
                                $errors[$tableName][] = "column '$fieldName' is a datetime, this type is not supported ($dbField->meta_type)";
                                continue;

                            } else {
                                // Report all other unsupported types as problems.
                                $errors[$tableName][] = "column '$fieldName' has unknown type ($dbField->meta_type)";
                                continue;
                            }

                            // Note: The empty string defaults are a bit messy...
                            if ($field->getDefault() != $dbField->default_value) {
                                $default = is_null($field->getDefault()) ? 'NULL' : $field->getDefault();
                                $dbDefault = is_null($dbField->default_value) ? 'NULL' : $dbField->default_value;
                                $errors[$tableName][] = "column '$fieldName' has default '$dbDefault', expected '$default' ($dbField->meta_type)";
                            }
                        }
                    }
                }
                unset($dbFields[$fieldName]);
            }

            // Remove extra columns (indicates unsupported hacks).
            foreach ($dbFields as $fieldName => $dbField) {
                if ($options['extracolumns']) {
                    try {
                        $this->logHandler->log("Dropping unexpected field '$fieldName' of table '$tableName'.");
                        $field = new \xmldb_field($fieldName);
                        $manager->drop_field($table, $field);
                    } catch (\Exception $e) {
                        $this->logHandler->logThrowable($e, "Unable to drop unexpected field '$fieldName' of table '$tableName'.");
                    }
                }
            }

            // Add missing indexes.
            $indexes = $table->getIndexes();
            foreach ($indexes as $index) {
                try {
                    $indexName = $index->getName();
                    if (!$manager->index_exists($table, $index)) {
                        $this->logHandler->log("Adding missing index '$indexName' for table '$tableName'.");
                        $manager->add_index($table, $index);
                    }
                } catch (\Exception $e) {
                    $this->logHandler->logThrowable($e, "Unable to add missing index '$indexName' for table '$tableName'.");
                }
            }

            // Add missing keys.
            $keys = $table->getKeys();
            foreach ($keys as $key) {
                try {
                    $keyName = $key->getName();
                    if (!$manager->key_exists($table, $key)) {
                        $this->logHandler->log("Adding missing key '$keyName' for table '$tableName'.");
                        $manager->add_key($table, $key);
                    }
                } catch (\Exception $e) {
                    $this->logHandler->logThrowable($e, "Unable to add missing key '$keyName' for table '$tableName'.");
                }
            }

            unset($dbTables[$tableName]);
        }

        if ($options['extratables']) {
            // Look for unsupported tables - local custom tables should be in /local/xxxx/db/install.xml file.

            // First remove any cache tables from the list, these are always expected and should never be removed.
            $dbTables = array_filter(
                $dbTables,
                function ($table) {
                    return strpos($table, 'report_builder_cache_') === false
                           && strpos($table, 'appraisal_quest_data_') === false
                           && strpos($table, 'feedback360_quest_data_') === false;
                }
            );

            // If there is no prefix, we can not say if table is ours, sorry.
            if ($manager->generator->prefix !== '') {
                foreach ($dbTables as $tableName => $unused) {
                    if (strpos($tableName, 'pma_') === 0) {
                        // Ignore phpmyadmin tables.
                        continue;
                    }
                    $table = new \xmldb_table($tableName);
                    try {
                        $this->logHandler->log("Dropping unexpected table '$tableName'.");
                        $manager->drop_table($table);
                    } catch (\Exception $e) {
                        $this->logHandler->logThrowable($e, "Unable to drop  unexpected table '$tableName'.");
                    }
                }
            }
        }

        // Display list of the errors that were not fixed.
        if ($errors) {
            foreach ($errors as $table => $items) {
                $this->logHandler->log(get_string('tablex', 'dbtransfer', $table));
                foreach ($items as $item) {
                    $this->logHandler->log(" - " . $item);
                }
            }
        }
    }
}
