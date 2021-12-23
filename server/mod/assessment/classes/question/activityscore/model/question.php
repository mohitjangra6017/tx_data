<?php
/**
 * @copyright 2019 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Alex Glover <alex.glover@kineo.com>
 * @package mod_assessment
 * @subpackage assquestion_activityscore
 */

namespace mod_assessment\question\activityscore\model;

use mod_assessment\question\activityscore\form\element\activityscore;
use rb_column;
use reportbuilder;
use stdClass;
use totara_form\element;
use totara_form\form;

defined('MOODLE_INTERNAL') || die();

class question extends \mod_assessment\model\question
{

    /**
     * @return bool
     */
    public function is_gradable(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function is_question(): bool
    {
        return false;
    }

    /**
     * @return string
     */
    public function get_type(): string
    {
        return 'activityscore';
    }

    public function get_default(): string
    {
        return '';
    }

    /**
     * @return string
     */
    public function get_displayname(): string
    {
        return get_string('pluginname', 'assquestion_activityscore');
    }

    public function get_element(): activityscore
    {
        return new activityscore("q_{$this->id}", $this->question, PARAM_TEXT);
    }

    public function get_activity_ids()
    {
        return $this->get_config()->activityids;
    }

    public function encode_value($value, form $form): string
    {
        // Not needed for non-question.
        return '';
    }

    public function report_display($value, $format, stdClass $row, rb_column $column, reportbuilder $report): string
    {
        debugging('HTML question should not be inlcuded in report displays');
        return '';
    }

}
