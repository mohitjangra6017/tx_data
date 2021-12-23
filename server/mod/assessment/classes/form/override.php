<?php
/**
 * @copyright   City & Guilds Kineo 2017
 * @author      Steven Hughes <steven.hughes@kineo.com>
 */

namespace mod_assessment\form;

defined('MOODLE_INTERNAL') || die();

use mod_assessment\helper;
use mod_assessment\model;

class override extends \moodleform
{

    private string $plugin = 'mod_assessment';

    public function definition()
    {
        $mform = $this->_form;

        $assessment = $this->_customdata['assessment'];
        $learner = $this->_customdata['learner'];
        $override = model\override::make(['userid' => $learner->id, 'assessmentid' => $assessment->id]);
        $attemptcount = helper\attempt::count_user_attempts($assessment, $learner->id);

        $remaining = helper\attempt::get_remaining_attempts($assessment, $learner->id);
        if ($remaining == helper\attempt::UNLIMITED) {
            $remaining = get_string('unlimited');
        }

        // Learner info.
        $mform->addElement('static', 'username', get_string('overrideuser', $this->plugin), fullname($learner));
        $mform->addElement('static', 'attemptsused', get_string('attemptsused', $this->plugin), $attemptcount);

        $attemptgroup = [];
        $attemptgroup[] = $mform->createElement('text', 'count');
        $attemptgroup[] = $mform->createElement(
            'advcheckbox',
            'unlimited',
            null,
            get_string('unlimited')
        );
        $mform->addGroup($attemptgroup, 'attempts', get_string('extraattempts', $this->plugin));
        $mform->disabledIf('attempts[count]', 'attempts[unlimited]', 'checked', 1);
        $mform->setType('attempts[count]', PARAM_RAW_TRIMMED);

        $mform->addElement(
            'static',
            'activitymaxattempts',
            get_string('activitymaxattemptssetting', $this->plugin),
            $assessment->attempts
        );

        // Save and cancel.
        $strsave = $remaining ? get_string('continue') : get_string('savechanges');
        $this->add_action_buttons(true, $strsave);

        $this->set_data([
            'attempts' => [
                'unlimited' => $override->is_unlimited(),
                'count' => $override->is_unlimited() ? '' : $remaining
            ],
        ]);
    }

    public function get_data(): ?object
    {
        $data = parent::get_data();
        if ($data) {
            if (isset($data->attempts)) {
                $data->attempts = $data->attempts['unlimited'] ? helper\attempt::UNLIMITED : $data->attempts['count'];
            }
        }
        return $data;
    }

    public function validate_value($value): bool
    {
        return !is_numeric($value) || $value < 0 || intval($value) != floatval($value);
    }

    public function validation($data, $files): array
    {
        $errors = parent::validation($data, $files);

        if (isset($data['attempts'])) {
            $attempts = $data['attempts'];

            if ($attempts['unlimited'] != helper\attempt::UNLIMITED) {
                if ($this->validate_value($attempts['count'])) {
                    $errors['attempts'] = get_string('error:nonnegativeint', $this->plugin);
                }
            }
        }

        return $errors;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->render();
    }
}