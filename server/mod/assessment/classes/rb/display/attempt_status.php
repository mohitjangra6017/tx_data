<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2019 Kineo
 */

namespace mod_assessment\rb\display;

use mod_assessment\helper\attempt;
use rb_column;
use reportbuilder;
use stdClass;
use totara_reportbuilder\rb\display\base;

class attempt_status extends base
{

    public static function display($value, $format, stdClass $row, rb_column $column, reportbuilder $report): string
    {
        return attempt::status($value);
    }

}
