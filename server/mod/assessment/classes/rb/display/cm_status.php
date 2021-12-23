<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2019 Kineo
 */

namespace mod_assessment\rb\display;

use rb_column;
use reportbuilder;
use stdClass;
use totara_reportbuilder\rb\display\base;

class cm_status extends base
{

    public static function display($value, $format, stdClass $row, rb_column $column, reportbuilder $report): string
    {
        if ($value == COMPLETION_COMPLETE) {
            return get_string('complete', 'rb_source_assessment');
        }

        return get_string('incomplete', 'rb_source_assessment');
    }

}
