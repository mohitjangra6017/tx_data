<?php

namespace local_email_log\Task;

defined('MOODLE_INTERNAL') || die();

use core\task\scheduled_task;
use Exception;
use local_email_log\Form\Config;

class CleanupLogs extends scheduled_task
{

    public function get_name(): string
    {
        return get_string('task:cleanuplogs', 'local_email_log');
    }

    private function getCutoffPointEpoch(): int
    {
        $intervalLength = get_config('local_email_log', 'interval_length');
        $intervalType = get_config('local_email_log', 'interval_type');

        $intervalLength = $intervalLength ?? 90;
        $intervalType = $intervalType ?? Config::INTERVAL_TYPES['days'];

        return strtotime("- {$intervalLength} {$intervalType}");
    }

    public function execute()
    {
        global $DB;

        $deleteBefore = $this->getCutoffPointEpoch();
        $transaction = $DB->start_delegated_transaction();

        try {
            $DB->delete_records_select('local_email_log', 'timesent <= ?', [$deleteBefore]);
        } catch (Exception $e) {
            $transaction->rollback($e);
        }

        $transaction->allow_commit();

        $fileSQL = <<<SQL
SELECT f.* 
FROM {files} f
LEFT JOIN {local_email_log} lel
  ON lel.id = f.itemid
WHERE lel.id IS NULL 
  AND f.component = 'local_email_log'
  AND f.filearea = 'attachment'
SQL;

        $fileStorage = get_file_storage();
        $fileRecords = $DB->get_records_sql($fileSQL);
        foreach ($fileRecords as $fileRecord) {
            $fileStorage->get_file_instance($fileRecord)->delete();
        }
    }
}
