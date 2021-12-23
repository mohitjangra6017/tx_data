<?php

namespace local_leaderboard\Form;

use coding_exception;
use dml_exception;
use local_leaderboard\Utils;
use core\event\course_module_completion_updated;
use moodleform;

defined('MOODLE_INTERNAL') || die;

global $CFG;
require_once dirname(dirname(__DIR__)) . '/lib.php';
require_once $CFG->dirroot . '/lib/formslib.php';

class EditLeaderboardForm extends moodleform
{
    /**
     * @throws coding_exception
     * @throws dml_exception
     */
    protected function definition()
    {
        global $DB;

        $form = $this->_form;
        $scoreId = $this->_customdata['scoreid'];
        $eventlist = $this->_customdata['eventlist'];
        $pluginlist = $this->_customdata['pluginlist'];
        $frequencyOptions = $this->_customdata['frequencyoptions'];

        $configuredEvent = $scoreId ? $DB->get_record('local_leaderboard', ['id' => $scoreId]) : false;

        $form->addElement('hidden', 'id', $scoreId);
        $form->setType('id', PARAM_INT);

        $form->addElement('header', 'settings', get_string('form:settings:header', 'local_leaderboard'));

        if (isset($eventlist['\\' . course_module_completion_updated::class]) && empty($scoreId)) {
            $form->addElement(
                'advcheckbox',
                'mod_completions',
                get_string('form:settings:mod_completions', 'local_leaderboard')
            );
        }

        // Hidden elements added here as we need to force values on disabled selects when mod_completions is checked
        $form->addElement('html', (new \HTML_QuickForm_hidden('plugin', 'core'))->toHtml());
        $form->addElement(
            'html',
            (new \HTML_QuickForm_hidden(
                'eventname', '\core\event\course_module_completion_updated'
            ))->toHtml()
        );

        // Plugins component list.
        $form->addElement('select', 'plugin', get_string('area', 'tool_monitor'), $pluginlist);
        // Doesn't make sense to change plugin once already set. Disable and don't add require rule.
        if (empty($scoreId)) {
            $form->addRule('plugin', get_string('required'), 'required');
        }
        $form->disabledIf('plugin', 'id', 'neq', 0);

        // Events list.
        $form->addElement('select', 'eventname', get_string('event', 'tool_monitor'), $eventlist);
        // Doesn't make sense to change event once already set. Disable and don't add require rule.
        if (empty($scoreId)) {
            $form->addRule('eventname', get_string('required'), 'required');
        }
        $form->disabledIf('eventname', 'id', 'neq', 0);

        $awardItems = [];
        $awardItems[] = $form->createElement('text', 'count', '', ['size' => '6']);
        $awardItems[] = $form->createElement('select', 'frequency', '', $frequencyOptions);
        $form->addGroup(
            $awardItems,
            'awardthreshold',
            get_string('form:editscore:award', 'local_leaderboard'),
            null,
            false
        );
        $form->setType('count', PARAM_INT);

        // Score
        $form->addElement('text', 'score', get_string('form:editscore:score', 'local_leaderboard'), ['size' => '6']);
        $form->setType('score', PARAM_TEXT);
        $form->addRule('score', null, 'regex', '/\d+/', 'client');
        $form->disabledIf('score', 'usegrade', 'checked');

        $disabled =
            !$configuredEvent || !in_array($configuredEvent->eventname, Utils::GRADE_EVENTS) ? ['disabled' => 'disabled'] : [];

        // Use grades
        $form->addElement(
            'advcheckbox',
            'usegrade',
            get_string('form:editscore:usegrades', 'local_leaderboard'),
            '',
            $disabled
        );
        $form->addHelpButton('usegrade', 'form:editscore:usegrades', 'local_leaderboard');

        $this->add_action_buttons(true, get_string('savechanges'));
    }

    /**
     * @param array $data
     * @param array $files
     * @return array
     * @throws coding_exception
     * @throws dml_exception
     */
    function validation($data, $files)
    {
        $configuredEvents = Utils::getConfiguredEvents();

        $errors = parent::validation($data, $files);

        if ($data['eventname'] == '\\' . course_module_completion_updated::class && !empty($data['count'])) {
            $errors['awardthreshold'] =
                get_string('error:threshold', 'local_leaderboard', course_module_completion_updated::get_name());
        }

        if (isset($data['score']) && !is_number($data['score']) && !empty($data['score'])) {
            $errors['score'] = get_string('error:integersonly', 'local_leaderboard');
        }

        if (isset($data['score']) && !empty($data['usegrade']) && !empty($data['score'])) {
            $errors['score'] = get_string('error:gradeandscore', 'local_leaderboard');
        }

        if (isset($data['eventname']) && isset($configuredEvents[$data['eventname']])
            && $data['id']
               != $configuredEvents[$data['eventname']]) {
            $errors['eventname'] = get_string('error:eventconfigured', 'local_leaderboard');
        }

        return $errors;
    }
}
