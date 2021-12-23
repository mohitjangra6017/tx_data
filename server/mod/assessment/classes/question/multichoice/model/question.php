<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package mod_assessment
 * @subpackage assquestion_text
 */

namespace mod_assessment\question\multichoice\model;

use mod_assessment\model\answer;
use mod_assessment\question\gradable;
use mod_assessment\question\multichoice\form\element\checkboxes;
use mod_assessment\question\multichoice\form\element\radios;
use totara_form\element;
use totara_form\form;

defined('MOODLE_INTERNAL') || die();

class question extends \mod_assessment\model\question
{

    use gradable;

    public const CHOICETYPE_SINGLE = 1;
    public const CHOICETYPE_MULTI = 2;

    public function get_choicetype()
    {
        return $this->get_config()->choicetype;
    }

    public function get_default()
    {
        $defaults = [];
        $default = '';
        foreach ($this->get_responses() as $response) {
            if ($response->default) {
                $default = $response->text;
                $defaults[$response->text] = $response->text;
            }
        }
        return $this->get_choicetype() == self::CHOICETYPE_MULTI ? $defaults : $default;
    }

    /**
     * @return string
     */
    public function get_displayname(): string
    {
        return get_string('pluginname', 'assquestion_multichoice');
    }

    /**
     * @return checkboxes|radios
     */
    public function get_element()
    {
        $responses = $this->get_responses();
        $radioreponses = [];
        foreach ($responses as $response) {
            $radioreponses[$response->text] = $response->text;
        }

        if ($this->get_choicetype() == self::CHOICETYPE_MULTI) {
            return new checkboxes(
                "q_{$this->id}",
                $this->question,
                $radioreponses
            );
        } else {
            return new radios("q_{$this->id}", $this->question, $radioreponses);
        }
    }

    /**
     * @return response[]
     */
    public function get_responses(): array
    {
        $configresponses = $this->get_config()->responses ?? [];
        $responses = [];
        foreach ($configresponses as $configresponse) {
            $responses[] = new response(
                $configresponse->text,
                $configresponse->value,
                (int)$configresponse->penalty,
                !empty($configresponse->default)
            );
        }
        return $responses;
    }

    /**
     * @return string
     */
    public function get_type(): string
    {
        return 'multichoice';
    }

    /**
     * @return bool
     */
    public function is_question(): bool
    {
        return true;
    }

    public function get_max_score(): int
    {
        $score = 0;
        foreach ($this->get_responses() as $response) {
            $score += $response->value;
        }
        return $score;
    }

    public function get_score(answer $answer)
    {
        $score = 0;

        $values = json_decode($answer->value);
        if (!is_array($values)) {
            $values = [$values];
        }

        foreach ($this->get_responses() as $response) {
            if (in_array($response->text, $values)) {
                $score += $response->value;
                $score -= $response->penalty;
            }
        }

        return max([0, $score]);    // Must be at least 0.
    }

    public function encode_value($value, form $form)
    {
        return json_encode($value);
    }

    public function report_display($value, $format, \stdClass $row, \rb_column $column, \reportbuilder $report): string
    {
        $values = (array)json_decode($value);
        return implode(', ', $values);
    }

    public function set_config($config, $requireupdate = false): question
    {
        $currentconfig = $this->get_config();
        $newconfig = json_decode($config);

        if (empty($currentconfig) || $currentconfig->choicetype != $newconfig->choicetype) {
            $requireupdate = true;
        }

        return parent::set_config($config, $requireupdate);
    }
}
