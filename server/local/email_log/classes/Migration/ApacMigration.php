<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 * Amended from a script produced by Hoogesh Dawoodarry <hoogesh.dawoodarry@kineo.com.au>
 */

namespace local_email_log\Migration;

use core\orm\query\builder;
use local_core\Migration\Migration;
use LogicException;
use xmldb_table;

class ApacMigration extends Migration
{
    private array $migrationData = [];

    private const OLD_TABLE = 'local_emaillog';
    private const NEW_TABLE = 'local_email_log';


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
    public function canMigrate(): bool
    {
        return builder::get_db()->get_manager()->table_exists(self::OLD_TABLE);
    }

    /**
     * This is called before the Totara upgrade is run. This gives your Migration a chance to collect any
     * data it needs, such as from the database or the Moodle data directory.
     */
    public function prepare(): void
    {
        $this->migrationData = builder::table(self::OLD_TABLE)->fetch();
        $this->log('Ready to migrate ' . count($this->migrationData) . ' Email Log records');
    }

    /**
     * Do all your actual Migration work here.
     * Any uncaught exceptions here will cause a full rollback of the DB.
     * Make sure that _canMigrate_ returns false by the time this function completes, otherwise your Migration
     * will cause an error and will fail the entire upgrade.
     */
    public function execute(): void
    {
        $data = array_map(function ($item) {
            return [
                'usertoid' => $item->recipient_id,
                'usertoemail' => $item->recipientmail,
                'userfromid' => 0,
                'userfromemail' => $item->fromemail,
                'subject' => $item->subject,
                'message' => $item->body,
                'timesent' => $item->timecreated,
                'status' => $item->status,
            ];
        }, $this->migrationData);

        $table = builder::table(self::NEW_TABLE);

        $current = $table->count();
        $new = count($data);

        builder::get_db()->insert_records(self::NEW_TABLE, $data);

        $total = $table->count();

        if ($current + $new !== $total) {
            throw new LogicException(
                sprintf(
                    'Mismatched total records in the email_log table. Expected %d, got %d',
                    $current + $new,
                    $total
                )
            );
        }

        $this->log('Migrated ' . $new . ' email log records');
        
        builder::get_db()->get_manager()->drop_table(new xmldb_table(self::OLD_TABLE));
        $this->log('Dropped old DB table ' . self::OLD_TABLE);
    }

}