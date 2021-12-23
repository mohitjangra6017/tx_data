<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

namespace mod_assessment\question\editor\model;

use mod_assessment\question\editor\form\element\editor;
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
        return true;
    }

    /**
     * @return string
     */
    public function get_type(): string
    {
        return 'editor';
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
        return get_string('pluginname', 'assquestion_editor');
    }

    public function get_element(): editor
    {
        return new editor("q_{$this->id}", $this->question);
    }

    public function encode_value($value, form $form)
    {
        return json_encode($value);
    }

    public function report_display($value, $format, stdClass $row, rb_column $column, reportbuilder $report): string
    {
        return json_decode($value) ?? '';
    }
}
