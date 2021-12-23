<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_credly\rb\display;

use rb_column;
use reportbuilder;
use stdClass;
use totara_reportbuilder\rb\display\base;

class badge_log extends base
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
        // If the most recent log is empty, then show nothing at all.
        if (empty($value)) {
            return '';
        }

        $extraFields = static::get_extrafields_row($row, $column);
        if ($extraFields->count == 1) {
            return $value;
        }

        return $value . PHP_EOL . PHP_EOL . get_string(
                'display:logs:and_x_other_logs',
                'rb_source_credly_badges',
                $extraFields->count - 1
            );
    }

}