<?php

/**
 * Form for failed email config
 *
 * @package    local
 * @subpackage emaillog
 * @copyright  &copy; 2017 Kineo Pacific {@link http://kineo.com.au}
 * @author     tri.le
 * @version    1.0
 */

namespace local_email_log\Form;

defined('MOODLE_INTERNAL') || die();

global $CFG;

use coding_exception;
use dml_exception;
use moodleform;

require_once($CFG->libdir . '/formslib.php');

class Config extends moodleform
{
    public const INTERVAL_DAYS = 'days';
    public const INTERVAL_WEEKS = 'weeks';
    public const INTERVAL_MONTHS = 'months';
    public const INTERVAL_YEARS = 'years';

    public const INTERVAL_TYPES = [
        self::INTERVAL_DAYS,
        self::INTERVAL_WEEKS,
        self::INTERVAL_MONTHS,
        self::INTERVAL_YEARS,
    ];

    /**
     * @throws coding_exception
     * @throws dml_exception
     */
    protected function definition()
    {
        global $DB;

        $form = $this->_form;

        $form->addElement(
            'advcheckbox',
            'enabled',
            get_string('settings:enabled', 'local_email_log')
        );

        $form->setDefault('enabled', 0);
        $form->addHelpButton('enabled', 'settings:enabled', 'local_email_log');

        $form->addElement(
            'header',
            'cleanuptask',
            get_string('settings:cleanup_task', 'local_email_log')
        );

        $intervalElements = [];
        $intervalElements[] = $form->createElement(
            'text',
            'interval_length',
            get_string('settings:interval_length', 'local_email_log')
        );

        $options = [
            self::INTERVAL_DAYS => get_string('settings:days', 'local_email_log'),
            self::INTERVAL_WEEKS => get_string('settings:weeks', 'local_email_log'),
            self::INTERVAL_MONTHS => get_string('settings:months', 'local_email_log'),
            self::INTERVAL_YEARS => get_string('settings:years', 'local_email_log'),
        ];

        $intervalElements[] = $form->createElement(
            'select',
            'interval_type',
            get_string('settings:interval_type', 'local_email_log'),
            $options
        );

        $form->addGroup(
            $intervalElements,
            'interval',
            get_string('settings:interval', 'local_email_log'),
            '',
            false
        );

        $form->setType('interval_length', PARAM_TEXT);
        $form->setType('interval_type', PARAM_ALPHA);
        $form->setDefault('interval_length', 90);
        $form->setDefault('interval_type', self::INTERVAL_DAYS);
        $form->addHelpButton('interval', 'settings:interval', 'local_email_log');

        $form->addElement(
            'header',
            'failed_emails',
            get_string('settings:failed_emails', 'local_email_log')
        );

        $form->addElement(
            'radio',
            'notifiedusers',
            get_string('settings:notifiedusers', 'local_email_log'),
            get_string('settings:manager', 'local_email_log'),
            'manager'
        );

        $notifyElements = [];
        $notifyElements[] = $form->createElement(
            'radio',
            'notifiedusers',
            '',
            get_string('settings:emailinprofilefield', 'local_email_log'),
            'profilefield'
        );

        $profileFields = $DB->get_records_menu('user_info_field', ['datatype' => 'text'], 'name', 'shortname,name');
        $notifyElements[] = $form->createElement('select', 'profilefield', '', $profileFields);
        $form->addGroup($notifyElements, 'profilefieldgroup', '', null, false);
        $form->setDefault('notifiedusers', 'manager');
        $form->addHelpButton('notifiedusers', 'settings:notifiedusers', 'local_email_log');

        $form->addElement('text', 'subject', get_string('settings:subject', 'local_email_log'));
        $form->setType('subject', PARAM_TEXT);

        $form->addElement('textarea', 'body', get_string('settings:body', 'local_email_log'));
        $form->setType('body', PARAM_TEXT);

        $this->add_action_buttons();
    }

    public function validation($data, $files): array
    {
        $errors = parent::validation($data, $files);

        if (empty($data['interval_length']) || !is_number($data['interval_length']) || $data['interval_length'] < 0) {
            $errors['interval'] = get_string('error:integers_only', 'local_email_log');
        }

        if (empty($data['interval_type']) || !in_array($data['interval_type'], self::INTERVAL_TYPES)) {
            $errors['interval'] = get_string('error:interval_type', 'local_email_log');
        }

        return $errors;
    }


    public function process_data()
    {
        $data = $this->get_data();
        set_config('notifiedusers', $data->notifiedusers, 'local_email_log');
        if ($data->notifiedusers == 'profilefield') {
            set_config('profilefield', $data->profilefield, 'local_email_log');
        } else {
            set_config('profilefield', '', 'local_email_log');
        }
        set_config('subject', $data->subject, 'local_email_log');
        set_config('body', $data->body, 'local_email_log');
        set_config('interval_length', $data->interval_length, 'local_email_log');
        set_config('interval_type', $data->interval_type, 'local_email_log');
        set_config('enabled', $data->enabled, 'local_email_log');
    }
}
