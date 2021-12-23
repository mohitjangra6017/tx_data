<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Simon Adams <simon.adams@kineo.com>
 */

namespace block_rate_course\rb\display;


use totara_reportbuilder\rb\display\base;

class recommendation_status extends base
{
    public static function display($value, $format, \stdClass $row, \rb_column $column, \reportbuilder $report)
    {
        if ($value == '0') {
            return get_string('active', 'rb_source_courserecommendations');
        } elseif ($value == '1') {
            return get_string('dismissed', 'rb_source_courserecommendations');
        } else {
            return get_string('unknown', 'rb_source_courserecommendations');
        }
    }
}