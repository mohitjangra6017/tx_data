<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

namespace mod_assessment\question\meta\model;

use mod_assessment\question\meta\form\collection\meta;
use rb_column;
use reportbuilder;
use stdClass;
use totara_form\element;
use totara_form\form;

class question extends \mod_assessment\model\question
{

    public function encode_value($value, form $form): string
    {
        return '';
    }

    public function get_default(): string
    {
        return '';
    }

    public function get_displayname(): string
    {
        return get_string('pluginname', 'assquestion_meta');
    }

    public function get_element(): meta
    {
        return new meta("q_{$this->id}");
    }

    /**
     * @return string
     */
    public function get_type(): string
    {
        return 'meta';
    }

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

    public function report_display($value, $format, stdClass $row, rb_column $column, reportbuilder $report): string
    {
        return '';
    }

}
