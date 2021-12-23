<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Simon Adams <simon.adams@kineo.com>
 */

namespace block_rate_course\rb\display;

use html_writer;
use rb_column;
use reportbuilder;
use stdClass;
use totara_reportbuilder\rb\display\base;

class rating extends base
{
    public static function display(
        $value,
        $format,
        stdClass $row,
        rb_column $column,
        reportbuilder $report
    ) {
        if ($format !== 'html') {
            return $value;
        }

        $display = html_writer::start_div("star-rating rating-sm rating-disabled");
        $display .= html_writer::start_div("rating-container rating-gly-star", ['data-content' => ""]);
        $display .= html_writer::div(
            "",
            'rating-stars',
            ['style' => 'width:' . (round($value * 2) * 10) . '%', 'data-content' => ""]
        );
        $display .= html_writer::end_div();
        $display .= html_writer::end_div();

        return $display;
    }
}