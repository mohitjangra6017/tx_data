<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package totara
 * @subpackage assquestion_rating
 */

namespace mod_assessment\question\rating\model;

use mod_assessment\model\answer;
use mod_assessment\question\gradable;
use mod_assessment\question\rating\form\element\slider;
use rb_column;
use reportbuilder;
use stdClass;
use totara_form\form;
use totara_reportbuilder\rb\display\integer;

defined('MOODLE_INTERNAL') || die();

class question extends \mod_assessment\model\question
{

    use gradable;

    public function encode_value($value, form $form)
    {
        return $value;
    }

    public function get_default()
    {
        $defaultconfig = $this->get_config()->default;
        return $defaultconfig->enable ? $defaultconfig->value : null;
    }

    public function get_displayname(): string
    {
        return get_string('pluginname', 'assquestion_rating');
    }

    public function get_element(): slider
    {
        return new slider(
            "q_{$this->id}",
            $this->question,
            $this->get_min_value(),
            $this->get_max_value()
        );
    }

    public function get_max_score()
    {
        return $this->get_max_value() - $this->get_min_value();
    }

    public function get_max_value()
    {
        return $this->get_config()->maxval;
    }

    public function get_min_value()
    {
        return $this->get_config()->minval;
    }

    public function get_score(answer $answer)
    {
        return $answer->value - $this->get_min_value();
    }

    public function get_type(): string
    {
        return 'rating';
    }

    public function is_question(): bool
    {
        return true;
    }

    public function report_display($value, $format, stdClass $row, rb_column $column, reportbuilder $report): string
    {
        return integer::display($value, $format, $row, $column, $report) ?? '';
    }

}
