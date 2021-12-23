<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Defines the review form for a course.
 *
 * @package    block
 * @subpackage rate_course
 * @copyright  &copy; 2014 Nine Lanterns Pty Ltd  {@link http://www.ninelanterns.com.au}
 * @author     james.ballard
 * @version    1.0
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/formslib.php');

/**
 * Description
 */
class block_rate_course_recommend extends moodleform {

    // Define the form.
    public function definition() {
        global $USER;

        $mform =& $this->_form;

        $mform->addElement('header', 'reviewheader', get_string('intro', 'block_rate_course'));

        $mform->addElement('hidden', 'courseid', $this->_customdata['courseid']);
        $mform->setType('courseid', PARAM_INT);

        $mform->addElement('hidden', 'userid', $USER->id);
        $mform->setType('userid', PARAM_INT);

        $mform->addElement('text', 'useridto', get_string('user'),
            array('class' => 'userselect2', 'data-step' => 1));
        $mform->addHelpButton('useridto', 'useridto', 'block_rate_course');
        $mform->setType('useridto', PARAM_INT);

        $this->add_action_buttons(true, get_string('submitrecommendation', 'block_rate_course'));

    }

    // Perform some extra moodle validation.
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        return $errors;
    }
}