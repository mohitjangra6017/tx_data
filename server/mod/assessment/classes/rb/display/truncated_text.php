<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright Kineo 2019
 */

namespace mod_assessment\rb\display;

use rb_column;
use reportbuilder;
use stdClass;
use totara_reportbuilder\rb\display\base;

class truncated_text extends base
{

    public static function display($value, $format, stdClass $row, rb_column $column, reportbuilder $report): ?string
    {
        $extra = self::get_extrafields_row($row, $column);
        $ideal = $extra->maxchars ?? 50;
        return shorten_text($value, $ideal);
    }

}
