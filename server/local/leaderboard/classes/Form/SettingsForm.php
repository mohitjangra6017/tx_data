<?php
/**
 * Settings form.
 *
 * @package   local_score
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2016 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace local_leaderboard\Form;

use coding_exception;
use moodleform;

defined('MOODLE_INTERNAL') || die;

class SettingsForm extends moodleform
{
    /**
     * @throws coding_exception
     */
    protected function definition()
    {
        $form = $this->_form;
        $form->updateAttributes(['class' => 'settings-form mform']);
        $courseOptions = $this->_customdata['courseOptions'];
        $progOptions = $this->_customdata['progOptions'];
        $excludedUsers = $this->_customdata['userOptions'];

        $form->addElement('header', 'header_settings', get_string('settings:header:settings', 'local_leaderboard'));
        $form->addElement(
            'select',
            'coursemultiplierfieldid',
            get_string('settings:coursemultiplierfieldid', 'local_leaderboard'),
            $courseOptions
        );
        $form->addHelpButton('coursemultiplierfieldid', 'settings:coursemultiplierfieldid', 'local_leaderboard');

        $form->addElement(
            'select',
            'progmultiplierfieldid',
            get_string('settings:progmultiplierfieldid', 'local_leaderboard'),
            $progOptions
        );
        $form->addHelpButton('progmultiplierfieldid', 'settings:progmultiplierfieldid', 'local_leaderboard');

        $form->addElement(
            'select',
            'excludeusersfieldid',
            get_string('settings:excludeusersfieldid', 'local_leaderboard'),
            $excludedUsers
        );
        $form->addHelpButton('excludeusersfieldid', 'settings:excludeusersfieldid', 'local_leaderboard');

        $form->addElement('advcheckbox', 'activeonly', get_string('form:editscore:activeonly', 'local_leaderboard'));
        $form->addHelpButton('activeonly', 'form:editscore:activeonly', 'local_leaderboard');

        $form->addElement(
            'advcheckbox',
            'rankuserswithscores',
            get_string('form:editscore:rankuserswithscores', 'local_leaderboard')
        );
        $form->addHelpButton('rankuserswithscores', 'form:editscore:rankuserswithscores', 'local_leaderboard');
    }
}
