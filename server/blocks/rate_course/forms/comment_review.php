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
 * Defines the comment form for a review.
 *
 * @package    block
 * @subpackage rate_course
 * @copyright  &copy; 2017 Kineo Pacific Pty Ltd  {@link http://www.kineo.com.au}
 * @author     john.phoon
 * @version    1.0
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/formslib.php');

/**
 * Description
 */
class block_rate_course_comment extends moodleform {

    // Define the form.
    public function definition() {
        global $USER;

        $mform =& $this->_form;

        $mform->addElement('header', 'commentheader', get_string('commentheader', 'block_rate_course'));

        $mform->addElement('hidden', 'review_id', $this->_customdata['reviewid']);
        $mform->setType('review_id', PARAM_INT);

        $mform->addElement('hidden', 'user_id', $USER->id);
        $mform->setType('user_id', PARAM_INT);

        $mform->addElement('textarea', 'comment', get_string('comment', 'block_rate_course'),
            array('rows' => 3, 'cols' => 30, 'class' => 'courserating', 'maxlength' => 140));
        $mform->setType('comment', PARAM_TEXT);
        $mform->addHelpButton('comment', 'comment', 'block_rate_course');

        $this->add_action_buttons(true, get_string('submitcomment', 'block_rate_course'));

    }

    // Perform some extra moodle validation.
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        return $errors;
    }
}