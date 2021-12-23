<?php
/**
 * Program course set completion status
 *
 * @package   local_program_report
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2019 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace local_program_report\rb\display;

use totara_reportbuilder\rb\display\base;

defined('MOODLE_INTERNAL') || die;

global $CFG;
require_once($CFG->dirroot . '/totara/program/program.class.php');
require_once($CFG->dirroot . '/completion/completion_completion.php');

class program_courseset_completion_status extends base
{
    public static function display($value, $format, \stdClass $row, \rb_column $column, \reportbuilder $report)
    {
        global $COMPLETION_STATUS;

        $extra = self::get_extrafields_row($row, $column);

        if (!empty($extra->programcertifpath) && $extra->programcertifpath != $extra->coursesetcertifpath) {
            return get_string('notapplicable', 'rb_source_prog_courseset_completion');
        }

        if ($extra->coursesetstatus == STATUS_COURSESET_COMPLETE) {
            if (empty($value) || $value < COMPLETION_STATUS_COMPLETE) {
                return get_string('optional', 'rb_source_prog_courseset_completion');
            }
        }

        if (empty($value)) {
            $value = COMPLETION_STATUS_NOTYETSTARTED;
        }

        if (!array_key_exists((int)$value, $COMPLETION_STATUS)) {
            return '';
        }
        $string = $COMPLETION_STATUS[(int)$value];
        if (empty($string)) {
            return '';
        } else {
            return get_string($string, 'completion');
        }
    }
}