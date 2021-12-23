<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Simon Adams <simon.adams@kineo.com>
 */

namespace local_email_log\rb\display;

defined('MOODLE_INTERNAL') || die();

use html_writer;
use moodle_url;
use rb_column;
use reportbuilder;
use stdClass;
use totara_reportbuilder\rb\display\base;

class email_actions extends base
{
    public static function display($value, $format, stdClass $row, rb_column $column, reportbuilder $report): string
    {
        return html_writer::link(
            new moodle_url('/local/email_log/resend.php', ['id' => $value]),
            get_string('resendemail', 'local_email_log')
        );
    }
}