<?php
/**
 * @package   local_email_log
 * @author    Jo Jones <jo.jones@kineo.com>
 * @copyright 2018 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace local_email_log\Form;

use moodleform;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once("{$CFG->libdir}/formslib.php");

class Resend extends moodleform
{

    public function definition()
    {
        $form =& $this->_form;

        $id = $this->_customdata['id'];
        $email = $this->_customdata['email'];

        $form->addElement(
            'hidden',
            'id'
        );
        $form->setType('id', PARAM_INT);
        $form->setDefault('id', $id);

        $form->addElement(
            'text',
            'email',
            get_string('usertoemail', 'local_email_log')
        );

        $form->setType('email', PARAM_EMAIL);
        $form->setDefault('email', $email);
        $form->addRule('email', null, 'required');

        $buttonarray = [];
        $buttonarray[] = $form->createElement(
            'submit',
            'resend',
            get_string('send', 'local_email_log')
        );
        $buttonarray[] = $form->createElement(
            'cancel',
            'cancel',
            get_string('cancel')
        );

        $form->addGroup($buttonarray, 'buttonar', '', [' '], false);
    }
}
