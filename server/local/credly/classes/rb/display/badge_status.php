<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_credly\rb\display;

use local_credly\entity\BadgeIssue;
use rb_column;
use reportbuilder;
use stdClass;
use totara_reportbuilder\rb\display\base;

class badge_status extends base
{
    public static array $statuses = [];

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
    public static function display($value, $format, stdClass $row, rb_column $column, reportbuilder $report): string
    {
        BadgeIssue::validateStatus($value);

        if (!isset(self::$statuses[$value])) {
            self::$statuses[$value] = get_string('badge_issue:status:' . $value, 'local_credly');
        }

        return self::$statuses[$value];
    }
}