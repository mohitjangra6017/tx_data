<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

namespace mod_assessment\question\datetime\model;

use mod_assessment\question\datetime\form\element\datetime;
use rb_column;
use reportbuilder;
use stdClass;
use totara_form\element;
use totara_form\form;
use totara_reportbuilder\rb\display\nice_date;
use totara_reportbuilder\rb\display\nice_datetime;

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
        return 'datetime';
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
        return get_string('pluginname', 'assquestion_datetime');
    }

    public function get_element(): datetime
    {
        return new datetime("q_{$this->id}", $this->question);
    }

    public function encode_value($value, form $form)
    {
        return json_encode($value);
    }

    public function report_display($value, $format, stdClass $row, rb_column $column, reportbuilder $report): string
    {
        $parsed = json_decode($value);
        if (empty($parsed)) {
            return '';
        }

        if ($this->get_config()->showtime ?? false) {
            return nice_datetime::display($value, $format, $row, $column, $report);
        }

        return nice_date::display($value, $format, $row, $column, $report);
    }
}
