<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

namespace mod_assessment\rb\display;

use mod_assessment\model\role;
use rb_column;
use reportbuilder;
use stdClass;
use totara_reportbuilder\rb\display\base;

class assessment_role extends base
{

    public static function display($value, $format, stdClass $row, rb_column $column, reportbuilder $report): ?string
    {
        $role = new role($value);
        return $role->get_string();
    }
}
