<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

namespace mod_assessment\rb\display;

use Exception;
use mod_assessment\model\import_error;
use rb_column;
use reportbuilder;
use stdClass;
use totara_reportbuilder\rb\display\base;

defined('MOODLE_INTERNAL') || die();

class error_code extends base
{
    public static function display($value, $format, stdClass $row, rb_column $column, reportbuilder $report): string
    {
        try {
            $error = new import_error($value);
        } catch (Exception $exception) {
            return get_string('error:import_unknown', 'assessment');
        }

        return $error->get_string();
    }
}
