<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package mod_assessment
 * @subpackage assquestion_text
 */

namespace mod_assessment\question\text\model;

use mod_assessment\question\text\form\element\text;
use totara_form\element;
use totara_form\form;
use totara_reportbuilder\rb\display\format_text;

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
        return true;
    }

    /**
     * @return string
     */
    public function get_type(): string
    {
        return 'text';
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
        return get_string('pluginname', 'assquestion_text');
    }

    public function get_element(): text
    {
        return new text("q_{$this->id}", $this->question, PARAM_TEXT);
    }

    public function encode_value($value, form $form)
    {
        return json_encode($value);
    }

    public function report_display($value, $format, \stdClass $row, \rb_column $column, \reportbuilder $report): string
    {
        return format_text::display(json_decode($value), $format, $row, $column, $report);
    }
}
