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

class badge_learning_type extends base
{
    public static array $types = [];

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
        if (empty(self::$types)) {
            self::$types = [
                'course' => get_string('course'),
                'program' => get_string('program', 'totara_program'),
                'certification' => get_string('certification', 'totara_program'),
            ];
        }

        return self::$types[$value];
    }

}