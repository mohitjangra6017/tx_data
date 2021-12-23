<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_core\Migration;

abstract class Migration
{
    private LogHandler $logHandler;

    private bool $dryRun;

    final public function __construct(LogHandler $logHandler, bool $dryRun)
    {
        $this->logHandler = $logHandler;
        $this->dryRun = $dryRun;
    }

    final public function isDryRun(): bool
    {
        return $this->dryRun;
    }

    final protected function log(string $message, array $context = []): void
    {
        $this->logHandler->log($message, $context);
    }

    /**
     * Defines if this Migration should be run.
     *
     * Returning true will cause both _prepare_ and _execute_ to be called.
     * It is required that when _execute_ runs, you ensure that _canMigrate_ can no longer return true.
     * If _canMigrate_ returns true on the existence of a DB table, then DROP that table in _execute_.
     * This is an error and will cause your migration to fail if _canMigrate_ returns true after _execute_.
     *
     * @return bool
     */
    abstract public function canMigrate(): bool;

    /**
     * This is called before the Totara upgrade is run. This gives your Migration a chance to collect any
     * data it needs, such as from the database or the Moodle data directory.
     */
    abstract public function prepare(): void;

    /**
     * Do all your actual Migration work here.
     * Any uncaught exceptions here will cause a full rollback of the DB.
     * Make sure that _canMigrate_ returns false by the time this function completes, otherwise your Migration
     * will cause an error and will fail the entire upgrade.
     */
    abstract public function execute(): void;
}