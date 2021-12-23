<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_credly\rb\display;

use html_writer;
use local_credly\entity\BadgeIssue;
use moodle_url;
use rb_column;
use reportbuilder;
use stdClass;
use totara_reportbuilder\rb\display\base;

class badge_actions extends base
{
    /**
     * Format the value.
     *
     * @param string $value
     * @param string $format
     * @param stdClass $row
     * @param rb_column $column
     * @param reportbuilder $report
     * @return string
     */
    public static function display($value, $format, stdClass $row, rb_column $column, reportbuilder $report)
    {
        if ($format !== 'html') {
            return '';
        }

        $extraFields = static::get_extrafields_row($row, $column);
        if (BadgeIssue::statusIsFailure($extraFields->status)) {
            $url = new moodle_url(
                '/local/credly/resend.php',
                ['badgeid' => $value, 'reportid' => $report->get_id(), 'return_url' => $report->get_current_url()]
            );
            return html_writer::link($url, get_string('badge:resend', 'local_credly'));
        }

        return '';
    }

}