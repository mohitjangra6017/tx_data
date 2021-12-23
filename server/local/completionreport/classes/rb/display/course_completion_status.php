<?php
/**
 * Completed or blank report display
 *
 * @package   local_completionreport
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2018 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace local_completionreport\rb\display;


use totara_reportbuilder\rb\display\base;

defined('MOODLE_INTERNAL') || die;

global $CFG;
require_once($CFG->dirroot.'/completion/completion_completion.php');

class course_completion_status extends base
{
    /**
     * Copied from @see \rb_source_course_completion::rb_display_completion_status
     *
     * @param string $value
     * @param string $format
     * @param \stdClass $row
     * @param \rb_column $column
     * @param \reportbuilder $report
     * @return string
     */
    public static function display($value, $format, \stdClass $row, \rb_column $column, \reportbuilder $report)
    {
        global $COMPLETION_STATUS;

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

    /**
     * @param \rb_column $column
     * @param \rb_column_option $option
     * @param \reportbuilder $report
     * @return bool
     */
    public static function is_graphable(\rb_column $column, \rb_column_option $option, \reportbuilder $report)
    {
        return true;
    }
}