<?php
/**
 * @copyright City & Guilds Kineo 2019
 * @author Simon Adams <simon.adams@kineo.com>
 */

namespace local_leaderboard\rb\aggregate;

use totara_reportbuilder\rb\aggregate\base;

defined('MOODLE_INTERNAL') || die();

class rank extends base
{
    /**
     * @param string $field
     * @return string
     */
    public static function get_field_aggregate($field)
    {
        return "DENSE_RANK() OVER (ORDER BY SUM({$field}) DESC)";
    }
}