<?php
/**
 * Program Selector Form
 *
 * @package   local_program_report
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2019 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace local_program_report\Form;

use moodleform;

defined('MOODLE_INTERNAL') || die;

global $CFG;

require_once ($CFG->libdir . '/formslib.php');

class ProgramSelectorForm extends moodleform
{
    /**
     * Form definition
     */
    protected function definition()
    {
        $form = $this->_form;
        $programs = $this->_customdata['programs'];

        $form->addElement('select', 'id', get_string('form:program:label', 'local_program_report'), $programs);

        $this->add_action_buttons(false, get_string('form:submit', 'local_program_report'));
    }
}