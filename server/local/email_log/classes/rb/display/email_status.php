<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Simon Adams <simon.adams@kineo.com>
 */

namespace local_email_log\rb\display;

defined('MOODLE_INTERNAL') || die();

use local_email_log\Watcher\EmailSentWatcher;
use rb_column;
use reportbuilder;
use stdClass;
use totara_reportbuilder\rb\display\base;

class email_status extends base
{
    public static function display($value, $format, stdClass $row, rb_column $column, reportbuilder $report): string
    {
        if (in_array($value, EmailSentWatcher::STATUSES)) {
            return get_string(EmailSentWatcher::STATUSES[$value], 'local_email_log');
        } else {
            return get_string('unknown', 'local_email_log');
        }
    }
}
