<?php
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2010 onwards Totara Learning Solutions LTD
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author David Curry <david.curry@totaralms.com>
 * @author Valerii Kuznetsov <valerii.kuznetsov@totaralms.com>
 * @author Simon Player <simon.player@totaralearning.com>
 * @package totara
 * @subpackage feedback360
 */

use totara_core\advanced_feature;

require_once($CFG->dirroot . '/totara/core/lib.php');
require_once($CFG->dirroot . '/totara/question/lib.php');
require_once($CFG->dirroot . '/totara/feedback360/lib/assign/lib.php');


class feedback360 {
    /**
     * Feedback360 responders restriction flags.
     */
    const RECIPIENT_ANYUSER = 1;
    const RECIPIENT_EMAIL = 2;
    const RECIPIENT_LM = 4;
    const RECIPIENT_MANAGER = 8;
    const RECIPIENT_COHORT = 16;
    const RECIPIENT_POSITION = 32;
    const RECIPIENT_ORGANISATION = 64;

    /**
     * feedback360 status
     */
    const STATUS_DRAFT = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_CLOSED = 2;
    const STATUS_COMPLETED = 3;

    /**
     * Selfevaluation status
     */
    const SELF_EVALUATION_DISABLED = 0;
    const SELF_EVALUATION_OPTIONAL = 1;
    const SELF_EVALUATION_REQUIRED = 2;

    /**
     * Feedback360 id
     * @var int
     */
    protected $id = 0;

    /**
     * Feedback360 status
     * @var int
     */
    private $status = self::STATUS_DRAFT;

    /**
     * Can the learner can self evaluate
     * @var int
     */
    public $selfevaluation = self::SELF_EVALUATION_OPTIONAL;

    /**
     * User->id of the creator of the feedback
     * @var int
     */
    public $userid = 0;

    /**
     * Feedback360 name
     * @var string
     */
    public $name = '';

    /**
     * Feedback360 description
     * @var string
     */
    public $description = '';

    /**
     * Allowed recipients groups
     * @var int bitmask
     */
    public $recipients = 0;

    /**
     * Whether the feedback is anonymous or not
     * @var int
     */
    public $anonymous = 0;

    /**
     * Create instance of an
     */
    public function __construct($id = 0) {
        // Set "all" until recipients will be implemented on userend.
        $this->recipients = 127;
        if ($id) {
            $this->id = $id;
            $this->load();
        }
    }

    /**
     * Allow read access to restricted properties
     * @param string $name
     * @return mixed
     */
    public function __get($name) {
        if (in_array($name, array('id', 'status'))) {
            return $this->$name;
        }
    }

    /**
     * Set feedback360 properties
     *
     * @param stdClass $todb
     * @return $this
     */
    public function set(stdClass $todb) {
        if (isset($todb->name)) {
            $this->name = $todb->name;
        }
        if (isset($todb->status)) {
            $this->status = $todb->status;
        }
        if (isset($todb->description)) {
            $this->description = $todb->description;
        }
        if (isset($todb->recipients)) {
            $this->recipients = $todb->recipients;
        }
        if (isset($todb->userid)) {
            $this->userid = $todb->userid;
        }
        if (isset($todb->anonymous)) {
            $this->anonymous = $todb->anonymous;
        }
        if (isset($todb->selfevaluation)) {
            $this->selfevaluation = $todb->selfevaluation;
        }
        return $this;
    }

    /**
     * Get stdClass with feedback360 properties
     *
     * @return stdClass
     */
    public function get() {
        $obj = new stdClass();
        $obj->name = $this->name;
        $obj->userid = $this->userid;
        $obj->description = $this->description;
        $obj->status = $this->status;
        $obj->id = $this->id;
        $obj->recipients = $this->recipients;
        $obj->anonymous = $this->anonymous;
        $obj->selfevaluation = $this->selfevaluation;

        return $obj;
    }

    /**
     * Saves current feedback360 properties
     *
     * @return $this
     */
    public function save() {
        global $DB, $USER;

        $todb = $this->get();

        if ($this->id > 0) {
            $todb->id = $this->id;
            $DB->update_record('feedback360', $todb);

            \totara_feedback360\event\feedback360_updated::create_from_instance($this)->trigger();
        } else {
            $todb->userid = $USER->id;
            $this->id = $DB->insert_record('feedback360', $todb);

            \totara_feedback360\event\feedback360_created::create_from_instance($this)->trigger();
        }
        // Refresh data.
        $this->load($this->id);
        return $this;
    }

    /**
     * Delete a feedback360
     */
    public function delete() {
        global $DB, $TEXTAREA_OPTIONS;

        if ($this->status == self::STATUS_ACTIVE) {
            throw new feedback360_exception('Cannot delete active feedback');
        }

        // Delete question data table.
        sql_drop_table_if_exists('{feedback360_quest_data_' . $this->id . '}');

        // Delete questions.
        $questions = feedback360_question::get_list($this->id);
        foreach ($questions as $question) {
            feedback360_question::delete($question->id);
        }

        // Delete grps, and all user/resp/email assignments.
        $assign = new totara_assign_feedback360('feedback360', $this);
        $assign->lockoverride = false;
        $assign->delete();

        // Delete files.
        $fs = get_file_storage();
        $fs->delete_area_files($TEXTAREA_OPTIONS['context']->id, 'totara_feedback360', 'feedback360', $this->id);
        // Delete the feedback360.
        $DB->delete_records('feedback360', array('id' => $this->id));

        \totara_feedback360\event\feedback360_deleted::create_from_instance($this)->trigger();
    }

    /**
     * Reload feedback360 properties from DB
     *
     * @return $this
     */
    public function load() {
        global $DB;
        $feedback360 = $DB->get_record('feedback360', array('id' => $this->id));
        if (!$feedback360) {
            throw new feedback360_exception(get_string('loadfeedback360failure', 'totara_feedback360'), 1);
        }
        $this->set($feedback360);
        return $this;
    }

    /**
     * Save user answer on feedback
     *
     * @param stdClass $formdata
     * @param feedback360_responder $resp
     * @return bool
     */
    public function save_answers(stdClass $formdata, feedback360_responder $resp) {
        global $DB;
        if ($resp->is_completed()) {
            return false;
        }
        // Get data to save.
        $answers = $this->postupdate_answers($formdata, $resp);

        // Save.
        $questdata = $DB->get_record('feedback360_quest_data_'.$this->id, array('feedback360respassignmentid' => $resp->id));
        if (!$questdata) {
            $answers->feedback360respassignmentid = $resp->id;
            $DB->insert_record('feedback360_quest_data_'.$this->id, $answers);
        } else {
            $answers_array = (array)$answers;
            if (!empty($answers_array)) {
                $answers->id = $questdata->id;
                $answers->timemodified = time();
                // This db call fails if there are no answers found (page with only info, no user input).
                $DB->update_record('feedback360_quest_data_'.$this->id, $answers);
            }
        }
        return true;
    }

    /**
     * Load user answer on feedback
     *
     * @param feedback360_responder $resp
     * @param stdClass
     */
    public function get_answers(feedback360_responder $resp) {
        global $DB;
        $questdata = $DB->get_record('feedback360_quest_data_'.$this->id, array('feedback360respassignmentid' => $resp->id));
        if ($questdata) {
            return $this->prepare_answers($questdata, $resp);
        }
        return null;
    }


    /**
     * Import answers db data to form data
     * @param stdClass $questdata
     * @param stdClass $roleassignment
     * @return stdClass
     */
    public function prepare_answers(stdClass $questdata, feedback360_responder $resp) {
        $questionsrs = feedback360_question::get_list($this->id);
        $answers = new stdClass();
        foreach ($questionsrs as $questiondata) {
            $question = new feedback360_question($questiondata->id, $resp);
            $answers = $question->get_element()->set_as_db($questdata)->get_as_form($answers, true);
        }
        return $answers;
    }

    /**
     * Export answers form data to db data
     *
     * @param stdClass $questdata
     * @param stdClass $roleassignment
     * @return stdClass
     */
    public function postupdate_answers(stdClass $formdata, feedback360_responder $resp) {
        $questionsrs = feedback360_question::get_list($this->id);
        $answers = new stdClass();
        foreach ($questionsrs as $questiondata) {
            $question = new feedback360_question($questiondata->id, $resp);
            $answers = $question->get_element()->set_as_form($formdata)->get_as_db($answers);
        }
        return $answers;
    }

    /**
     * Counts the number of completed responder assignments this feedback360 has
     *
     * @return int
     */
    public function count_completed_answers() {
        global $DB;
        $completed_responses = $DB->count_records_sql('SELECT COUNT(f.id)
                FROM {feedback360} f
                JOIN {feedback360_user_assignment} fua ON (f.id = fua.feedback360id)
                JOIN {feedback360_resp_assignment} fra ON (fua.id = fra.feedback360userassignmentid AND fra.timecompleted != 0)
                WHERE f.id = :feedback360id', array('feedback360id' => $this->id));
        return $completed_responses;
    }

    /*
     * Get an array of all feedback360s (for a particular userid)
     *
     * @param int $userid   Either get all the records or just the records for the userid
     * @return array        The array of feedback360 records
     */
    public static function get_manage_list($userid = 0) {
        global $DB;

        $context = context_system::instance();
        require_capability('totara/feedback360:managefeedback360', $context);

        $params = ($userid == 0) ? array() : array('userid' => $userid);

        return $DB->get_records('feedback360', $params, 'status, name ASC');
    }

    /**
     * Clone feedback360
     * @param int $feedback360id Default $this->id
     * @param int $daysoffset number of days to add to each stage time due.
     */
    public static function duplicate($feedback360id) {
        global $DB, $TEXTAREA_OPTIONS;

        $context = context_system::instance();
        require_capability('totara/feedback360:clonefeedback360', $context);

        // Clone the feedback360 and set it to draft.
        $feedback360 = new feedback360($feedback360id);
        $feedback360->id = 0;
        $feedback360->status = self::STATUS_DRAFT;

        // Get id.
        $newfeedback360 = $feedback360->save();

        // Now it's link to newfeedback.
        unset($feedback360);

        // Copy textarea files.
        $data = new stdClass();
        $data->description = $newfeedback360->description;
        $data->descriptionformat = FORMAT_HTML;
        $data = file_prepare_standard_editor($data, 'description', $TEXTAREA_OPTIONS, $TEXTAREA_OPTIONS['context'],
            'totara_feedback360', 'feedback360', $feedback360id);

        $data = file_postupdate_standard_editor($data, 'description', $TEXTAREA_OPTIONS, $TEXTAREA_OPTIONS['context'],
            'totara_feedback360', 'feedback360', $newfeedback360->id);
        $newfeedback360->description = $data->description;

        $newfeedback360->save();

        $question_records = $DB->get_records('feedback360_quest_field', array('feedback360id' => $feedback360id), 'sortorder');
        foreach ($question_records as $question_record) {
            $question = new feedback360_question($question_record->id);
            $question->duplicate($newfeedback360->id);
        }

        // Clone assigned groups.
        $assign = new totara_assign_feedback360('feedback360', new feedback360($feedback360id));
        $assign->duplicate($newfeedback360);

        return $newfeedback360->id;
    }

    /**
     * Set current status of a feedback360
     *
     * @param int $status feedback360::STATUS_*
     */
    public function set_status($newstatus) {
        $allowedstatus = array(
            self::STATUS_ACTIVE => array(self::STATUS_CLOSED, self::STATUS_COMPLETED),
            self::STATUS_CLOSED => array(self::STATUS_ACTIVE),
            self::STATUS_DRAFT => array(self::STATUS_ACTIVE),
            self::STATUS_COMPLETED => array()
        );
        if (!in_array($newstatus, $allowedstatus[$this->status])) {
            $a = new stdClass();
            $a->oldstatus = self::display_status($this->status);
            $a->newstatus = self::display_status($newstatus);
            throw new feedback360_exception(get_string('error:cannotchangestatus', 'totara_feedback360', $a));
        } else {
            $this->status = $newstatus;
            if ($newstatus == self::STATUS_COMPLETED || $newstatus == self::STATUS_CLOSED) {
                $this->timefinished = time();
            } else if ($newstatus == self::STATUS_ACTIVE) {
                $this->timefinished = null;
            }
            $this->save();
        }
    }

    public function close() {
        $this->set_status(self::STATUS_CLOSED);
    }

    public static function cancel_user_assignment($userassignmentid, $asmanager = false) {
        global $DB;

        // Remove all unanswered feedback requests.
        $user_assignment = $DB->get_record('feedback360_user_assignment', array('id' => $userassignmentid), '*', MUST_EXIST);
        $feedback360 = $DB->get_record('feedback360', array('id' => $user_assignment->feedback360id));
        $resp_assignments = $DB->get_records('feedback360_resp_assignment',
                array('feedback360userassignmentid' => $userassignmentid));
        $delete_user_assignment = true;
        foreach ($resp_assignments as $resp_assignment) {
            if (empty($feedback360->anonymous) && empty($resp_assignment->timecompleted)) {
                self::cancel_resp_assignment($resp_assignment, $asmanager);
            } else {
                $delete_user_assignment = false;
            }
        }
        return $delete_user_assignment;
    }

    /**
     * Cancels a resp_assignment
     *
     * @param int/object $resp_assignment   Either the id of a resp record or the record itself.
     * @param bookean    $asmanager         Whether to send notices as the user or as their manager,
     *                                      defaults to as the user.
     */
    public static function cancel_resp_assignment($resp_assignment, $asmanager = false) {
        global $CFG, $DB, $USER;

        require_once($CFG->dirroot . '/totara/message/messagelib.php');

        // Check if it is an id that has been passed in.
        if (is_int($resp_assignment)) {
            $resp_assignment = $DB->get_record('feedback360_resp_assignment', array('id' => $resp_assignment));
        }

        // Double check that it is now an object.
        if (!is_object($resp_assignment)) {
            print_error('error:unexpectedtype', 'totara_feedback360');
        }

        $stringmanager = get_string_manager();

        $user_assignment = $DB->get_record('feedback360_user_assignment',
                array('id' => $resp_assignment->feedback360userassignmentid));
        $feedback360 = $DB->get_record('feedback360', array('id' => $user_assignment->feedback360id));
        $userfrom = $DB->get_record('user', array('id' => $user_assignment->userid));
        $userto = $DB->get_record('user', array('id' => $resp_assignment->userid));

        $stringvars = new stdClass();
        $stringvars->feedbackname = format_string($feedback360->name);
        if ($asmanager) {
            $stringvars->userfrom = fullname($USER);
            $stringvars->staffname = fullname($userfrom);
            $userfrom = $USER;
        } else {
            $stringvars->userfrom = fullname($userfrom);
        }

        // Check for related email_assignment.
        $email = '';
        if (!empty($resp_assignment->feedback360emailassignmentid)) {
            // Delete email_assignment.
            $param = array('id' => $resp_assignment->feedback360emailassignmentid);
            $email = $DB->get_field('feedback360_email_assignment', 'email', $param);

            $transaction = $DB->start_delegated_transaction();
            $DB->delete_records('feedback360_email_assignment', $param);
            $DB->delete_records('feedback360_resp_assignment', array('id' => $resp_assignment->id));
            $transaction->allow_commit();

            if ($asmanager) {
                $subject = get_string('managercancellationsubject', 'totara_feedback360', $stringvars);
                $message = get_string('managercancellationemail', 'totara_feedback360', $stringvars);
            } else {
                $subject = get_string('cancellationsubject', 'totara_feedback360', $stringvars);
                $message = get_string('cancellationemail', 'totara_feedback360', $stringvars);
            }
            // Send a cancellation email.
            $userto = \totara_core\totara_user::get_external_user($email);
            email_to_user($userto, $userfrom, $subject, strip_tags($message), $message);
        } else {
            $DB->delete_records('feedback360_resp_assignment', array('id' => $resp_assignment->id));

            $event = new stdClass;
            $event->userfrom = $userfrom;
            $event->icon = 'feedback360-cancel';
            $event->userto = $userto;
            if ($asmanager) {
                $event->subject = $stringmanager->get_string('managercancellationsubject', 'totara_feedback360',
                        $stringvars, $userto->lang);
                $event->fullmessage = $stringmanager->get_string('managercancellationalert', 'totara_feedback360',
                        $stringvars, $userto->lang);
                $event->fullmessagehtml = $stringmanager->get_string('managercancellationalert', 'totara_feedback360',
                        $stringvars, $userto->lang);
            } else {
                $event->subject = $stringmanager->get_string('cancellationsubject', 'totara_feedback360',
                        $stringvars, $userto->lang);
                $event->fullmessage = $stringmanager->get_string('cancellationalert', 'totara_feedback360',
                        $stringvars, $userto->lang);
                $event->fullmessagehtml = $stringmanager->get_string('cancellationalert', 'totara_feedback360',
                        $stringvars, $userto->lang);
            }

            // Send a cancellation alert.
            tm_alert_send($event);
        }

        \totara_feedback360\event\request_deleted::create_from_instance($resp_assignment, $user_assignment->userid, $email)->trigger();
    }

    /**
     * Retrieve the appropriate string for the status
     *
     * @param int $status   An instance of feedback360::STATUS_X
     * @return string       The corresponding string
     */
    public static function display_status($status) {
        switch ($status) {
            case self::STATUS_DRAFT:
                $result = get_string('draft', 'totara_feedback360');
                break;
            case self::STATUS_ACTIVE:
                $result = get_string('active', 'totara_feedback360');
                break;
            case self::STATUS_CLOSED:
                $result = get_string('closed', 'totara_feedback360');
                break;
            case self::STATUS_COMPLETED:
                $result = get_string('completed', 'totara_feedback360');
                break;
        }

        return $result;
    }

    /**
     * Retrieve the appropriate string for the self evaluation status
     *
     * @param int $status   An instance of feedback360::SELF_EVALUATION_X
     * @return string   corresponding string
     */
    public static function get_selfevaluation_status($status) {
        switch ($status) {
            case self::SELF_EVALUATION_DISABLED:
                $result = get_string('notallowed', 'totara_feedback360');
                break;
            case self::SELF_EVALUATION_OPTIONAL:
                $result = get_string('optional', 'totara_feedback360');
                break;
            case self::SELF_EVALUATION_REQUIRED:
                $result = get_string('required', 'totara_feedback360');
                break;
        }

        return $result;
    }


    public static function can_view_feedback360s($userid = null) {
        global $USER, $DB;

        if (!isloggedin()) {
            return false;
        }

        if (!$userid) {
            $userid = $USER->id;
        }

        $systemcontext = context_system::instance();
        if (has_capability('totara/feedback360:viewownreceivedfeedback360', $systemcontext, $userid)) {
            // Count feedback360 forms assigned to user.
            $forms = $DB->get_fieldset_select('feedback360_user_assignment', 'id', 'userid = ?', array('userid' => $userid));
            if (!empty($forms) > 0) {
                return true;
            }

            // Count active requests from user.
            foreach ($forms as $form) {
                $own_requests = $DB->count_records('feedback360_resp_assignment', array('feedback360userassignmentid' => $form));
                if ($own_requests > 0) {
                    return true;
                }
            }

        }

        if (has_capability('totara/feedback360:viewownrequestedfeedback360', $systemcontext, $userid)) {
            // Count feedbacks requested of user.
            $requests = $DB->count_records('feedback360_resp_assignment', array('userid' => $userid));
            if ($requests > 0) {
                return true;
            }
        }
        // TODO: Not clear what this function do. Add comments please. Also, add to feedback360_test::test_can_view()
        // Scenario that passes previous checks but fails this check.
        $usercontext = context_user::instance($userid);
        if (has_capability('totara/feedback360:viewstaffreceivedfeedback360', $usercontext, $userid)) {
            $sql = "SELECT COUNT(fua.id)
                      FROM {feedback360} fb
                      JOIN {feedback360_user_assignment} fua
                        ON fua.feedback360id = fb.id
                     WHERE (fb.status = ? OR fb.status = ?)
                       AND fua.userid = ?";
            $count = $DB->count_records_sql($sql, array(self::STATUS_ACTIVE, self::STATUS_COMPLETED, $userid));
            if ($count > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Is self evaluation enabled.
     *
     * @param $userassignmentid
     * @return bool
     */
    public static function self_evaluation_enabled($userassignmentid) {
        global $DB;

        $params = array(
            'userassignmentid' => $userassignmentid,
            'selfevaluation' => feedback360::SELF_EVALUATION_DISABLED,
        );

        $sql = "SELECT 1
                  FROM {feedback360_user_assignment} fua
                  JOIN {feedback360} f
                      ON f.id = fua.feedback360id
                 WHERE fua.id = :userassignmentid
                   AND f.selfevaluation != :selfevaluation";

        return $DB->record_exists_sql($sql, $params);
    }

    /**
     * Can the user self evaluate.
     *
     * @param $userassignmentid
     * @param $userid
     * @return bool
     */
    public static function can_self_evaluate($userassignmentid, $userid) {
        global $DB;

        $params = array(
            'feedback360userassignmentid' => $userassignmentid,
            'userid' => $userid,
            'selfevaluation' => feedback360::SELF_EVALUATION_DISABLED,
        );

        $sql = "SELECT 1
                  FROM {feedback360_resp_assignment} fra
                  JOIN {feedback360_user_assignment} fua
                      ON fua.id = fra.feedback360userassignmentid
                  JOIN {feedback360} f
                      ON f.id = fua.feedback360id
                 WHERE  fra.feedback360userassignmentid = :feedback360userassignmentid
                   AND fra.userid = :userid
                   AND fra.timecompleted = 0
                   AND f.selfevaluation != :selfevaluation";

        return $DB->record_exists_sql($sql, $params);
    }

    /**
     * Has a self evaluation been completed.
     *
     * @param $userassignmentid
     * @param $userid
     * @return bool
     */
    public static function self_evaluation_completed($userassignmentid, $userid) {
        global $DB;

        $sql = "SELECT 1
                  FROM {feedback360_resp_assignment}
                 WHERE feedback360userassignmentid = :userassignmentid
                   AND userid = :userid
                   AND timecompleted != 0";

        $params = array(
            'userassignmentid' => $userassignmentid,
            'userid' => $userid,
        );

        return $DB->record_exists_sql($sql, $params);
    }

    /**
     * Create table
     */
    private function create_answers_table() {
        global $DB;

        if ($this->id < 1) {
            throw new feedback360_exception(get_string('error:feedbacktablecreation', 'totara_feedback360'), 4);
        }

        $tablename = 'feedback360_quest_data_'.$this->id;
        $table = new xmldb_table($tablename);
        // Feedback360 specific fields/keys/indexes.
        $xmldb = array();
        $xmldb[] = new xmldb_field('id', XMLDB_TYPE_INTEGER, 10, XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE);
        $xmldb[] = new xmldb_field('timecompleted', XMLDB_TYPE_INTEGER, 10, XMLDB_UNSIGNED, null, null, 0);
        $xmldb[] = new xmldb_field('timemodified', XMLDB_TYPE_INTEGER, 10, XMLDB_UNSIGNED, null, null, 0);
        $xmldb[] = new xmldb_field('feedback360respassignmentid', XMLDB_TYPE_INTEGER, 10, XMLDB_UNSIGNED, XMLDB_NOTNULL);
        // Feedback360 keys.
        $xmldb[] = new xmldb_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $xmldb[] = new xmldb_key('feedquestdata_feeresass'.$this->id.'_fk', XMLDB_KEY_FOREIGN, array('feedback360respassignmentid'),
            'feedback360_resp_assignment', array('id'));

        // Question specific fields/keys/indexes.
        $questionman = new question_manager();
        $xmldb = $questionman->get_xmldb($this->fetch_questions(), $xmldb);
        $questionman->add_db_table($xmldb, $table);

        $dbman = $DB->get_manager();
        $dbman->create_table($table);
    }

    /**
     * Activate questions in case there is something that the question needs to do during activation.
     */
    private function activate_questions() {
        $allquestions = $this->fetch_questions();

        foreach ($allquestions as $question) {
            $question->get_element()->activate();
        }
    }

    /**
     * Check if feedback was activated
     *
     * @param int $feedback360id
     */
    public static function was_activated($feedback360id) {
        global $DB;

        $columns = $DB->get_columns('feedback360_quest_data_'.$feedback360id);
        return !empty($columns);
    }

    /**
     * Return instances of all questions
     *
     * @return array of feedback36_question
     */
    public function fetch_questions() {
        global $DB;
        $questionrs = $DB->get_records('feedback360_quest_field', array('feedback360id' => $this->id));
        $questions = array();
        foreach ($questionrs as $key => $questdata) {
            $questions[$key] = new feedback360_question($questdata->id);
        }
        return $questions;
    }

    /**
     * Activate the feedback.
     */
    public function activate() {
        if (!in_array($this->status, array(self::STATUS_DRAFT, self::STATUS_CLOSED))) {
            throw new feedback360_exception(get_string('error:activationstatus', 'totara_feedback360'), 5);
        }

        if ($this->status == self::STATUS_DRAFT) {
            $assign = new totara_assign_feedback360('feedback360', $this);
            $assign->store_user_assignments();
            $this->create_answers_table();
            $this->activate_questions();
        }

        $this->set_status(self::STATUS_ACTIVE);
    }

    /**
     * Check if it is possible to activate the Feedback360
     */
    public function validate() {
        $errors = array();

        // Check it has at least one question.
        $questions = feedback360_question::get_list($this->id);
        $is_question = false;
        if (!empty($questions)) {
            foreach ($questions as $questdata) {
                $question = new feedback360_question($questdata->id);
                if ($question->get_element()->is_answerable()) {
                    $is_question = true;
                }
            }
        }
        if (!$is_question) {
            $errors['questions'] = get_string('error:questionsrequired', 'totara_feedback360');
        }

        // Check that some learners are assigned.
        $assign = new totara_assign_feedback360('feedback360', $this);
        $learners = $assign->get_current_users_count();
        if ($learners == 0) {
            $errors['learners'] = get_string('error:learnersrequired', 'totara_feedback360');
        }
        // Check recipients.
        if ($this->recipients < 1) {
            $errors['recipients'] = get_string('error:recipientsrequired', 'totara_feedback360');
        }

        return $errors;
    }

    /**
     * Check if feedback360 is in draft state
     *
     * @param mixed $feedback360 feedback360.id or instance of feedback360
     * @return bool
     */
    public static function is_draft($feedback360) {
        if (is_numeric($feedback360)) {
            $feedback360 = new feedback360($feedback360);
        }
        if (!($feedback360 instanceof feedback360)) {
            throw new feedback360_exception('Feedback360 object not found', 2);
        }
        return ($feedback360->status == self::STATUS_DRAFT);
    }

    public static function has_user_assignment($userid, $feedback360id) {
        global $DB;

        $params = array('feedback360id' => $feedback360id, 'userid' => $userid);
        return $DB->record_exists('feedback360_user_assignment', $params);
    }

    /**
     * Given an id from the table feedback360_user_assignment, ensure that a given user's id
     * matches the userid on that assignment and that it is active.
     *
     * In a number of places the user assignment id is placed into a variable called $formid.
     *
     * @param $userid
     * @param $userassignmentid
     * @return bool true if user is assigned with this assignment id
     */
    public static function validate_user_to_assignment_id($userid, $userassignmentid) {
        global $DB;

        $sql = "SELECT fa.id
                FROM {feedback360_user_assignment} fa
                JOIN {feedback360} f
                ON fa.feedback360id = f.id
                WHERE fa.userid = :userid
                AND fa.id = :assignmentid
                AND f.status = :status
                ";
        $params = array('userid' => $userid, 'assignmentid' => $userassignmentid, 'status' => self::STATUS_ACTIVE);

        return $DB->record_exists_sql($sql, $params);
    }

    /**
     * Checks if a user is the manager of someone assigned to a feedback360
     *
     * @param int feedback360id     The id of the feedback to check for
     * @param int userid            The id of the user to look for staff members of
     *
     * @return boolean
     */
    public static function check_managing_assigned($feedback360id, $userid) {
        global $DB;

        $staff = \totara_job\job_assignment::get_staff_userids($userid);

        if (!empty($staff)) {
            list($insql, $inparams) = $DB->get_in_or_equal($staff);

            $sql = 'SELECT userid
                    FROM {feedback360_user_assignment}
                    WHERE feedback360id = ?
                    AND userid ' . $insql;
            $params = array_merge(array($feedback360id), $inparams);
            $staff_assigned = $DB->get_fieldset_sql($sql, $params);

            if (!empty($staff_assigned)) {
                return $staff_assigned;
            }
        }

        return array();
    }

    /**
     * Prints an error if Feedback 360 is not enabled
     *
     */
    public static function check_feature_enabled() {
        if (advanced_feature::is_disabled('feedback360')) {
            print_error('feedback360disabled', 'totara_feedback360');
        }
    }

    /**
     * Check to see if logged in user can view the specified user's 360 feedback
     *
     * @param int $userid id of the user for whom we are want to display data
     * @return bool
     * @since Totara 9.0
     */
    public static function can_view_other_feedback360s($userid) {
        global $USER;

        if (!isloggedin()) {
            return false;
        }

        if (!$userid) {
            $userid = $USER->id;
        }

        // Check user has permission to request feedback, and set up the page.
        if ($USER->id == $userid) {
            return feedback360::can_view_feedback360s($userid);
        } else if (\totara_job\job_assignment::is_managing($USER->id, $userid)) {
            // You are a manager view a staff members feedback.
            $usercontext = context_user::instance($userid, MUST_EXIST);
            return has_capability('totara/feedback360:viewstaffreceivedfeedback360', $usercontext) ||
                   has_capability('totara/feedback360:viewstaffrequestedfeedback360', $usercontext);
        } else if (is_siteadmin()) {
            // Site admin can see everything.
            return true;
        } else {
            // You aren't the user, their manager or an admin
            return false;
        }
    }

}

/**
 * Feedback questions definition
 */
class feedback360_question extends question_storage {
    /**
     * Relative postion in feedback form
     * @var int
     */
    public $sortorder = 0;

    /**
     * Feedback
     * @var int
     */
    public $feedback360id = null;

    /**
     * Question is required to answer
     * @var bool
     */
    public $required = false;

    /**
     * Create question instance
     *
     * @param int $id
     * @param feedback360_responder $roleassignment
     */
    public function __construct($id = 0, feedback360_responder $respassignment = null) {
        $this->answerfield = 'feedback360respassignmentid';
        $this->prefix = 'feedback360';
        if ($id) {
            $this->id = $id;
            $this->load($respassignment);
        }
    }

    /**
     * Set question properties from form
     *
     * @param stdClass $todb
     * @return $this
     */
    public function set(stdClass $todb) {
        if (is_null($this->feedback360id) && isset($todb->feedback360id)) {
            $this->feedback360id = $todb->feedback360id;
        }

        if (isset($todb->name)) {
            $this->name = $todb->name;
        }

        if (isset($todb->sortorder)) {
            $this->sortorder = $todb->sortorder;
        }

        if (isset($todb->required)) {
            $this->required = (bool)$todb->required;
        }

        $this->get_element()->define_set($todb);
        return $this;
    }

    /**
     * Get stdClass with question properties
     *
     * @param $isform destination is form (otherwise db)
     * @return stdClass
     */
    public function get($isform = false) {
        // Get element settings.

        $obj = new stdClass;
        if ($isform) {
            $this->element->define_get($obj);
        }
        $obj->id = $this->id;
        $obj->feedback360id = $this->feedback360id;
        $obj->name = $this->name;
        $obj->sortorder = $this->sortorder;
        $obj->required = (int)$this->required;
        return $obj;
    }


    /**
     * Save question to database
     *
     * @return feedback360_question
     */
    public function save() {
        global $DB;

        $todb = $this->get();
        $this->export_storage_fields($todb);
        if ($todb->feedback360id < 1) {
            throw new feedback360_exception('Question must belong to an feedback', 32);
        }

        // Fix sort order.
        $sameplace = false;
        if ($this->id) {
            $sameplace = $DB->record_exists('feedback360_quest_field', array('id' => $this->id, 'sortorder' => $this->sortorder,
                'feedback360id' => $this->feedback360id));
        }
        if (!$sameplace) {
            // Put as last item.
            $sqlorder = 'SELECT sortorder
                    FROM {feedback360_quest_field}
                    WHERE feedback360id = ?
                      AND id <> ?
                    ORDER BY sortorder DESC';
            $neworder = $DB->get_record_sql($sqlorder, array($todb->feedback360id, (int)$this->id), IGNORE_MULTIPLE);
            if (!$neworder) {
                $todb->sortorder = 0;
            } else {
                $todb->sortorder = $neworder->sortorder + 1;
            }
        }
        $this->sortorder = $todb->sortorder;
        if ($this->id > 0) {
            $todb->id = $this->id;
            $DB->update_record('feedback360_quest_field', $todb);
        } else {
            $this->id = $DB->insert_record('feedback360_quest_field', $todb);
        }
        return $this;
    }

    /**
     * Load instance of question
     *
     * @param feedback360_responder $respassignment assignment of question for answer
     */
    public function load(feedback360_responder $respassignment = null) {
        global $DB;

        // Load data.
        $quest = $DB->get_record('feedback360_quest_field', array('id' => $this->id));
        if (!$quest) {
            throw new feedback360_exception('Cannot load quest field', 31);
        }

        $this->feedback360id = $quest->feedback360id;
        $this->sortorder = $quest->sortorder;
        $this->required = (bool)$quest->required;
        $this->import_storage_fields($quest);

        if ($respassignment) {
            $questionman = new question_manager($respassignment->subjectid, $respassignment->id);
        } else {
            $questionman = new question_manager();
        }
        $this->attach_element($questionman->create_element($this));
    }

    /**
     * Duplicate a feedback360 question given a feedbackid to assign it to
     *
     * @param int $feedback360id    The id of the feedback360 to assign the duplicate to
     */
    public function duplicate($feedback360id) {
        $oldelement = clone($this->get_element());
        $srcid = $this->id;
        $this->id = 0;
        $this->feedback360id = $feedback360id;
        $this->save();
        // Separate instance for cloned question.
        $newquestion = clone($this);
        $this->id = $srcid;
        $this->load();

        $newquestion->load();
        $element = $newquestion->get_element();
        $element->duplicate($oldelement);

        return $newquestion;

    }

    /**
     * Get list of questions related to a feedback
     *
     * @param int $feedback360id
     */
    public static function get_list($feedback360id) {
        global $DB;
        return $DB->get_records('feedback360_quest_field', array('feedback360id' => $feedback360id), 'sortorder');
    }

    /**
     * Change relative position of quesiton
     *
     * @param int $qeustionid
     * @param int $pos starts with 0
     */
    public static function reorder($questionid, $pos) {
        db_reorder($questionid, $pos, 'feedback360_quest_field', 'feedback360id');
    }

    /**
     * Delete question
     *
     * @param mixed $question feedback360_question or it's id
     * @param bool delete success
     */
    public static function delete($question) {
        global $DB;
        if (is_numeric($question)) {
            $question = new feedback360_question($question);
        }
        if (!($question instanceof feedback360_question)) {
            throw new feedback360_exception('feedback360_question object not found', 2);
        }

        // We need to be sure that all relations to feedback answers are cleaned.
        if (feedback360::was_activated($question->feedback360id)) {
            return false;
        }
        try {
            $question->get_element()->delete();
        } catch (Exception $e) {
            // Delete even if element was badly broken.
        }
        $DB->delete_records('feedback360_quest_field', array('id' => $question->id));
        return true;
    }

    /**
     * Check if user can view answer by assignment.
     * Ignores email assignments as a token is required for this
     *
     * @param int $assignmentid Responder assignment id
     * @param int $userid
     * @return bool
     */
    public function user_can_view($assignmentid, $userid) {
        // If there is no assignment.
        if (empty($assignmentid)) {
            return false;
        }
        $resp = new feedback360_responder($assignmentid);
        // Check that the user is the subject or the responder.
        if (in_array($userid , array((int)$resp->userid, $resp->subjectid))) {
            return true;
        }
        return false;
    }

}

/**
 * Feedback360 response assignments
 */
class feedback360_responder {
    /**
     * Type of assignment: by user or by email
     */
    const TYPE_USER = 0;
    const TYPE_EMAIL = 1;

    /**
     * Response id
     * @var int
     */
    protected $id = 0;

    /**
     * Feedback360 id
     * @var int
     */
    protected $feedback360id = 0;

    /**
     * Feedback360 user assignment id
     * @var int
     */
    protected $feedback360userassignmentid = 0;

    /**
     * Response user id
     * @var int
     */
    protected $userid = 0;

    /**
     * Email assignment email
     * @var string
     */
    protected $email = '';

    /**
     * Email assignment email
     * @var string
     */
    protected $token = '';

    /**
     * Type of assignment
     * @var type
     */
    protected $type = self::TYPE_USER;

    /**
     * User that requested feedback
     * @var int
     */
    protected $subjectid = 0;

    /**
     * Is user that requested feedback watched response
     * @var bool
     */
    public $viewed = false;

    /**
     * Time when feedback was requested
     * @var int
     */
    public $timeassigned = 0;

    /**
     * Time when feedback360 response was completed
     *
     * @var int
     */
    public $timecompleted = 0;

    /**
     * When feedback should be completed
     * Read only from user assignment
     * @var int
     */
    protected $timedue = 0;

    /**
     * Fake response - do not save
     * @var bool
     */
    protected $fake = false;

    /**
     * @var bool true when user accessed the response via email token
     */
    public $tokenaccess = false;

    /**
     * @var string used for when a user who has requested feedback is viewing responses
     */
    protected $requestertoken;

    /**
     * Constructor
     * @param int $id feedback360_resp_assignment.id
     */
    public function __construct($id = 0) {
        if ($id > 0) {
            $this->load($id);
        }
    }

    /**
     * Allow read access to restricted properties
     * @param string $name
     * @return mixed
     */
    public function __get($name) {
        if (isset($this->$name)) {
            return $this->$name;
        }
    }

    /**
     * Factory method to get assignment by email
     *
     * @param string $email
     * @param string $token
     * @return feedback360_responder or false if not found
     */
    public static function by_email($email, $token) {
        global $DB;

        // Get feedback360_resp_assignment.id by email and token record from feedback360_email_assignment.
        $emailparams = array('email' => $email, 'token' => $token);
        $emailid = $DB->get_field('feedback360_email_assignment', 'id', $emailparams);
        if (!$emailid) {
            return false;
        }

        // Instantiate and return feedback360_responder.
        $resp = $DB->get_record('feedback360_resp_assignment', array('feedback360emailassignmentid' => $emailid));
        if (!$resp) {
            return false;
        }
        return new feedback360_responder($resp->id);
    }

    /**
     * Get a feedback_responder object based on the 'requestertoken' associated with a response.
     *
     * This should only be used when the response is being viewed by the user who requested feedback.
     * It should NOT be used by the user giving the feedback.
     *
     * @param $token
     * @return bool|feedback360_responder
     */
    public static function get_by_requester_token($requestertoken) {
        global $DB;

        $resp = $DB->get_record('feedback360_resp_assignment', array('requestertoken' => $requestertoken));
        if (!$resp) {
            return false;
        }

        return new feedback360_responder($resp->id);
    }

    /**
     * Return responder email if TYPE_EMAIL.
     * @return string
     */
    public function get_email() {
        return $this->email;
    }

    /**
     * Factory method to get assignment by user id's and feedback id
     *
     * @param int $userid that will respond on feedback
     * @param int $feedback360id
     * @param int $subjectid user that requested feedback
     * @return feedback360_responder
     */
    public static function by_user($userid, $feedback360id, $subjectid) {
        global $DB;
        $sql = "SELECT fra.id
                FROM {feedback360_user_assignment} fua
                JOIN {feedback360_resp_assignment} fra ON (fra.feedback360userassignmentid = fua.id)
                WHERE fra.userid = ? AND fua.feedback360id = ? AND fua.userid = ?";

        if (!$data = $DB->get_record_sql($sql, array($userid, $feedback360id, $subjectid))) {
            print_error('error:respassignmentaccess', 'totara_feedback360');
        }

        return new feedback360_responder($data->id);
    }

    /**
     * Factory method to get fake assignment for preview
     *
     * @param int $feedback360id
     * @return feedback360_responder fake instance
     */
    public static function by_preview($feedback360id) {
        global $USER;
        $fakeresp = new feedback360_responder();
        $fakeresp->feedback360id = $feedback360id;
        $fakeresp->type = self::TYPE_USER;
        $fakeresp->userid = $USER->id;
        $fakeresp->subjectid = $USER->id;
        $fakeresp->timeassigned = time();
        $fakeresp->timedue = time() + 3600;
        $fakeresp->fake = true;
        return $fakeresp;
    }

    /**
     * Load object from db
     *
     * @param int $id
     */
    public function load($id) {
        global $DB;
        $sql = "SELECT fra.*, fua.feedback360id, fua.userid as subjectid, fua.timedue, fea.email, fea.token
                FROM {feedback360_resp_assignment} fra
                JOIN {feedback360_user_assignment} fua ON (fua.id = fra.feedback360userassignmentid)
                LEFT JOIN {feedback360_email_assignment} fea ON (fea.id = fra.feedback360emailassignmentid)
                WHERE fra.id = ?";
        $respdata = $DB->get_record_sql($sql, array($id), '*', MUST_EXIST);
        $this->id = $respdata->id;
        $this->feedback360id = $respdata->feedback360id;
        $this->feedback360userassignmentid = $respdata->feedback360userassignmentid;
        $this->subjectid = $respdata->subjectid;
        $this->viewed = $respdata->viewed;
        $this->timeassigned = $respdata->timeassigned;
        $this->timecompleted = $respdata->timecompleted;
        $this->timedue = $respdata->timedue;
        $this->requestertoken = $respdata->requestertoken;

        if ($respdata->feedback360emailassignmentid > 0) {
            if (empty($respdata->email) || empty($respdata->token)) {
                throw new feedback360_exception('Cannot load responder', 41);
            }
            $this->type = self::TYPE_EMAIL;
            $this->email = $respdata->email;
            $this->token = $respdata->token;
        } else {
            $this->type = self::TYPE_USER;
            $this->userid = $respdata->userid;
        }
    }

    /**
     * Save response assignment changes
     */
    public function save() {
        global $DB;
        $data = new stdClass();

        $data->id = $this->id;
        $data->feedback360userassignmentid = $this->feedback360userassignmentid;
        $data->timeassigned = $this->timeassigned;
        $data->viewed = $this->viewed;
        $data->timecompleted = $this->timecompleted;
        $data->requestertoken = $this->get_requestertoken();
        if ($this->type == self::TYPE_USER) {
            $data->userid = $this->userid;
        } else {
            if ($this->id > 0) {
                // Try to find email assignment.
                $email = $DB->get_record('feedback360_email_assignment',
                        array('email' => $this->email, 'token' => $this->token));
                if ($email) {
                    $data->feedback360emailassignmentid = $email->id;
                }
            }
            if (!isset($data->feedback360emailassignmentid) || !$data->feedback360emailassignmentid) {
                $email = new stdClass();
                $email->email = $this->email;
                $email->token = $this->token;
                $data->feedback360emailassignmentid = $DB->insert_record('feedback360_email_assignment', $email);
            }
        }

        if ($this->id > 0) {
            $DB->update_record('feedback360_resp_assignment', $data);
        } else {
            $data->id = $DB->insert_record('feedback360_resp_assignment', $data);

            $userassign = $DB->get_field('feedback360_user_assignment', 'userid', array('id' => $this->feedback360userassignmentid));
            $email = isset($this->email) ? $this->email : '';
            \totara_feedback360\event\request_created::create_from_instance($data, $userassign, $email)->trigger();
        }
    }

    /**
     * Mark assignment as completed
     * @param int $time time stamp of completion
     */
    public function complete($time = 0) {
        if (!$time) {
            $time = time();
        }
        $this->timecompleted = $time;
        $this->save();
    }

    /**
     * Is response completed
     *
     * @return bool
     */
    public function is_completed() {
        return (bool)$this->timecompleted;
    }

    /**
     * Fake response assignmnet
     *
     * @return bool
     */
    public function is_fake() {
        return $this->fake;
    }

    /**
     * Is this response assignement to email
     *
     * @return bool
     */
    public function is_email() {
        return $this->type == self::TYPE_EMAIL;
    }

    /**
     * Is this response assignement to user
     *
     * @return bool
     */
    public function is_user() {
        return $this->type == self::TYPE_USER;
    }

    /**
     * Takes an array of users that are intended to be assigned as responders to feedback360
     * assignment.  This means it should include users being added as well as users being kept.
     *
     * Returns an array of 3 arrays to be used for further processing:
     * - 0 => users who are not already assigned and are being added.
     * - 1 => users who are already assigned are being kept.
     * - 2 => users who were assigned but are not being kept.
     *
     * Users who are currently assigned but are now invalid will be added to the array of
     * cancelled users.
     *
     * @param array $userids - ids of all users that are intended to be assigned as responders.
     * @param int $userassignmentid - id from feedback360_user_assignment table.
     * @return array[] of the form (array $newusers, array $keptusers, array $cancelusers).
     */
    public static function sort_system_userids($userids, $userassignmentid) {
        global $DB;

        $new = array();
        $keep = array();
        $cancel = array();

        // Invalid users.
        if (empty($userids)) {
            $invaliduserids = array();
        } else {
            list($insql, $inparams) = $DB->get_in_or_equal($userids);
            $invaliduserids = $DB->get_fieldset_select('user', 'id', '(suspended = 1 OR deleted = 1) AND id '.$insql, $inparams);
        }

        $guest = guest_user();
        $invaliduserids[] = $guest->id;

        if (!feedback360::self_evaluation_enabled($userassignmentid)) {
            $requesteruserid = $DB->get_field('feedback360_user_assignment', 'userid', array('id' => $userassignmentid));
            if (!empty($requesteruserid)) {
                $invaliduserids[] = $requesteruserid;
            }
        }

        $existingusers = self::get_system_users_by_assignment($userassignmentid);

        foreach($userids as $userid) {
            if (isset($existingusers[$userid])) {
                // No check for invalid ids here. Current behaviour is to keep them.
                $keep[$userid] = $userid;
            } else {
                if (in_array($userid, $invaliduserids)) {
                    // Invalid id, don't add to new.
                    continue;
                }
                $new[$userid] = $userid;
            }
        }

        // Any existing users not in keep are added to cancel.
        foreach($existingusers as $id => $user) {
            if (!isset($keep[$id])) {
                $cancel[$id] = $id;
            }
        }

        return array($new, $keep, $cancel);
    }

    /**
     * Gets user records for system users (meaning not assigned via email)
     * who are responders to the given feedback360 user assignment.
     *
     * This will include users that should be invalid but are currently still assigned.
     *
     * @param $userassignmentid
     * @return array of user records, with user id as key.
     */
    public static function get_system_users_by_assignment($userassignmentid) {
        global $DB;
        $userfields = get_all_user_name_fields(true, 'u');
        $sql = "
            SELECT u.id, $userfields
              FROM {feedback360_resp_assignment} fra
              JOIN {user} u
                ON fra.userid = u.id
             WHERE fra.feedback360userassignmentid = :userassignmentid
               AND fra.feedback360emailassignmentid IS NULL
        ";
        $params = array('userassignmentid' => $userassignmentid);

        return $DB->get_records_sql($sql, $params);
    }

    /**
     * Create the records for feedback360_resp_assignment for system users and sends notifications.
     *
     * @param stdClass $data Data about assignments.
     * @param bool $asmanager Whether we are sending as the user or as their manager defaults to sending as the user.
     * @param stdClass $userfrom User object for sender.
     * @param stdClass $strvars Variables to be substituted into strings.
     */
    public static function update_and_notify_system($data, $asmanager, $userfrom, $strvars) {
        global $DB;

        // Create new resp_assignments for all users selected in the system.
        $systemnew = !empty($data->systemnew) ? explode(',', $data->systemnew) : array();
        $systemkeep = !empty($data->systemkeep) ? explode(',', $data->systemkeep) : array();

        // If user is self evaluating, add them as well.
        if (!empty($data->selfevaluation)) {
            $systemnew[] = $data->userid;
        }

        // We need to validate the data to ensure new, keep and cancel lists are genuine.
        $newandkeep = array_merge($systemnew, $systemkeep);
        list($systemnew, $systemkeep, $systemcancel) = self::sort_system_userids($newandkeep, $data->formid);

        self::update_system_assignments($systemnew, $systemcancel, $data->formid, $data->duedate, $asmanager);

        if ($data->duenotifications && !empty($systemkeep)) {

            // Prepare strings.
            $strmanagerupdatesubject = new lang_string('managerupdatesubject', 'totara_feedback360', $strvars);
            $strmanagerupdatealert = new lang_string('managerupdatealert', 'totara_feedback360', $strvars);
            $strupdatesubject = new lang_string('updatesubject', 'totara_feedback360', $strvars);
            $strupdatealert = new lang_string('updatealert', 'totara_feedback360', $strvars);

            // Send an alert with an updated duedate to everyone in systemkeep.
            $event = new stdClass();
            $event->userfrom = $userfrom;
            $event->icon = 'feedback360-update';

            foreach ($systemkeep as $uid) {
                $userto = $DB->get_record('user', array('id' => $uid));

                $event->userto = $userto;
                if ($asmanager) {
                    $event->subject = $strmanagerupdatesubject->out($userto->lang);
                    $event->fullmessage = $strmanagerupdatealert->out($userto->lang);
                } else {
                    $event->subject = $strupdatesubject->out($userto->lang);
                    $event->fullmessage = $strupdatealert->out($userto->lang);
                }
                tm_alert_send($event);
            }
        }

    }

    /**
     * Create the records for feedback360_resp_assignment.
     *
     * @param array $new                An array of userids to create assignments for.
     * @param array $cancel             An array of existing userids that need to be cancelled
     * @param int $userformid           The id of the linked feedback360_user_assignment
     * @param int $duedate              The date they should submit feedback by, for the notification
     * @param boolean $asmanager        Whether we are sending as the user or as their manager
     *                                  defaults to sending as the user.
     */
    public static function update_system_assignments($new, $cancel, $userformid, $duedate, $asmanager = false) {
        global $DB, $CFG, $USER;

        require_once($CFG->dirroot . '/totara/message/messagelib.php');

        $stringmanager = get_string_manager();

        $user_assignment = $DB->get_record('feedback360_user_assignment', array('id' => $userformid));
        $feedback360 = $DB->get_record('feedback360', array('id' => $user_assignment->feedback360id), '*', MUST_EXIST);
        $userfrom = $DB->get_record('user', array('id' => $user_assignment->userid));

        // Create all the resp_assignments.
        $resp_assignment = new stdClass();
        $resp_assignment->feedback360userassignmentid = $userformid;
        $resp_assignment->timeassigned = time();

        $taskvars = new stdClass();
        if ($asmanager) {
            $taskvars->fullname = fullname($USER);
            $taskvars->staffname = fullname($userfrom);
        } else {
            $taskvars->fullname = fullname($userfrom);
        }

        if ($duedate) {
            $dueupdate = new stdClass();
            $dueupdate->id = $userformid;
            $dueupdate->timedue = $duedate;
            $DB->update_record('feedback360_user_assignment', $dueupdate);
        }

        // Randomise order users are added to ensure ids can't be used to identify users.
        // This will lead to keys being lost - NEVER rely on them after this!
        shuffle($new);

        // Loop through the users to add and assign them where appropriate.
        foreach ($new as $userid) {
            $resp_assignment->userid = $userid;
            $resp_assignment->requestertoken = self::create_requestertoken();
            $resp_assignment->id = $DB->insert_record('feedback360_resp_assignment', $resp_assignment);

            $userto = $DB->get_record('user', array('id' => $userid));

            $params = array('userid' => $userfrom->id, 'feedback360id' => $user_assignment->feedback360id);
            $url = new moodle_url('/totara/feedback360/feedback.php', $params);
            $taskvars->link = html_writer::link($url, $url->out());
            $taskvars->url = $url->out();
            $taskvars->feedbackname = format_string($feedback360->name);

            // Time due.
            if (!empty($user_assignment->timedue)) {
                $duedate = userdate($user_assignment->timedue, get_string('strftimedatefulllong', 'langconfig'));
                $taskvars->timedue = get_string('byduedate' , 'totara_feedback360', $duedate);
            } else {
                $taskvars->timedue = '';
            }

            // Send a task to the requested user.
            $eventdata = new stdClass();
            $eventdata->userto = $userto;
            $eventdata->userfrom = ($asmanager) ? $USER : $userfrom;
            $eventdata->icon = 'feedback360-request';

            if ($userto->id == $USER->id) {
                // This is a self evaluation.
                if ($feedback360->selfevaluation == feedback360::SELF_EVALUATION_OPTIONAL) {
                    // Optional self evaluation.
                    $eventdata->subject = $stringmanager->get_string('selfevaluationemailrequestsubject', 'totara_feedback360',
                        $taskvars, $userto->lang);
                    $eventdata->fullmessage = $stringmanager->get_string('selfevaluationemailrequestoptionalbody',
                        'totara_feedback360', $taskvars, $userto->lang);
                    $eventdata->fullmessagehtml = $stringmanager->get_string('selfevaluationemailrequestoptionalbodyhtml',
                        'totara_feedback360', $taskvars, $userto->lang);
                } else {
                    // Required self evaluation.
                    $eventdata->subject = $stringmanager->get_string('selfevaluationemailrequestsubject', 'totara_feedback360',
                        $taskvars, $userto->lang);
                    $eventdata->fullmessage = $stringmanager->get_string('selfevaluationemailrequestrequiredbody',
                        'totara_feedback360', $taskvars, $userto->lang);
                    $eventdata->fullmessagehtml = $stringmanager->get_string('selfevaluationemailrequestrequiredbodyhtml',
                        'totara_feedback360', $taskvars, $userto->lang);
                }
            } else if ($asmanager) {
                $eventdata->subject = $stringmanager->get_string('manageremailrequestsubject', 'totara_feedback360',
                        $taskvars, $userto->lang);
                $eventdata->fullmessage = $stringmanager->get_string('manageremailrequeststr', 'totara_feedback360',
                        $taskvars, $userto->lang);
            } else {
                $eventdata->subject = $stringmanager->get_string('emailrequestsubject', 'totara_feedback360',
                        $taskvars, $userto->lang);
                $eventdata->fullmessage = $stringmanager->get_string('emailrequeststr', 'totara_feedback360',
                        $taskvars, $userto->lang);
            }
            tm_task_send($eventdata);

            \totara_feedback360\event\request_created::create_from_instance($resp_assignment, $user_assignment->userid)->trigger();
        }

        // Loop through everything in the cancel array and remove their resp_assignment.
        if (empty($feedback360->anonymous)) {
            // Only allow cancellations when a feedback isn't anonymous.
            foreach ($cancel as $userid) {
                $resp_params = array('userid' => $userid, 'feedback360userassignmentid' => $userformid);
                $resp_assignment = $DB->get_record('feedback360_resp_assignment', $resp_params);

                if (!empty($resp_assignment->timecompleted)) {
                    // We can't cancel completed responses.
                    continue;
                }

                feedback360::cancel_resp_assignment($resp_assignment, $asmanager);
            }
        } else if (in_array($user_assignment->userid, $cancel)) {
            // Ok to remove for uncompleted self evaluation, if not complete.
            $resp_params = array('userid' => $user_assignment->userid, 'feedback360userassignmentid' => $userformid);
            $resp_assignment = $DB->get_record('feedback360_resp_assignment', $resp_params);

            if (empty($resp_assignment->timecompleted)) {
                feedback360::cancel_resp_assignment($resp_assignment, $asmanager);
            }
        }
    }

    /**
     * Get the 'requestertoken' associated with a feedback response. This will create a token if
     * no token is found. This does not check the database. Ensure you use this classes load method
     * to pull any data from the database if necessary.
     *
     * The requestertoken is used for accessing of responses by the user who requested feedback.
     * This should NOT be used for users who will be giving responses.
     *
     * @return string the requester token (a 40 character sha1 hash).
     */
    protected function get_requestertoken() {
        if (empty($this->requestertoken)) {
            $this->requestertoken = self::create_requestertoken();
        }

        return $this->requestertoken;
    }

    /**
     * Creates a random, unique 40 character sha1 hash to be used as the 'requestertoken'.
     *
     * @return string the requester token (a 40 character sha1 hash).
     */
    private static function create_requestertoken() {
        $stringtohash = 'requester' . time() . random_string() . get_site_identifier();
        return sha1($stringtohash);
    }

    /**
     * Given an array of emails sorts them into three other arrays based on the following:
     * - 0 => emails in the supplied list that ARE NOT already assigned as responders to this user assignment (new).
     * - 1 => emails in the supplied list that ARE already assigned as responders to this user assignment (keep).
     * - 2 => emails that are already assigned as responders but are not in the supplied list (cancel).
     *
     * @param array $emails - should be for emails that are intended to be assigned, including new and existing.
     * @param int $userassignmentid id from the feedback360_user_assignment table.
     * @return array[] of the form (array new, array keep, array cancel).
     */
    public static function sort_responder_emails($emails, $userassignmentid) {

        $new = array();
        $keep = array();
        $cancel = array();

        $existingemails = self::get_emails_by_assignment($userassignmentid);

        foreach($emails as $email) {
            if ($responderid = array_search($email, $existingemails)) {
                // Current behaviour is not to validate emails that are existing, this is to
                // reduce the risk of data loss.
                $keep[$responderid] = $email;
            } else {
                // If the email is invalid, don't add it.
                if (validate_email($email)) {
                    $new[] = $email;
                }
            }
        }

        // Any existing users not in keep are added to cancel.
        foreach($existingemails as $id => $email) {
            if (!isset($keep[$id])) {
                $cancel[$id] = $email;
            }
        }

        return array($new, $keep, $cancel);
    }

    /**
     * Get all emails currently assigned as responders to a given feedback360 user assignment.
     * @param int $userassignmentid - id from the feedback360_user_assignment.
     * @return array of emails, with the corresponding feedback360_resp_assignment id as keys.
     */
    public static function get_emails_by_assignment($userassignmentid) {
        global $DB;

        $sql = "
            SELECT fra.id, fea.email
              FROM {feedback360_resp_assignment} fra
        INNER JOIN {feedback360_email_assignment} fea
                ON fra.feedback360emailassignmentid = fea.id
             WHERE fra.feedback360userassignmentid = :userassignmentid
        ";
        $params = array('userassignmentid' => $userassignmentid);

        return $DB->get_records_sql_menu($sql, $params);
    }

    /**
     * Create the records for feedback360_resp_assignment for external users and sends notifications.
     *
     * @param stdClass $data Data about assignments.
     * @param bool $asmanager Whether we are sending as the user or as their manager defaults to sending as the user.
     * @param stdClass $userfrom User object for sender.
     * @param stdClass $strvars Variables to be substituted into strings.
     */
    public static function update_and_notify_email($data, $asmanager, $userfrom, $strvars) {
        global $USER;
        // Create new email and resp assignments for emails given.
        $emailnew = !empty($data->emailnew) ? explode(',', $data->emailnew) : array();
        $emailkeep = !empty($data->emailkeep) ? explode(',', $data->emailkeep) : array();

        $newandkeep = array_merge($emailnew, $emailkeep);
        list($emailnew, $emailkeep, $emailcancel) = self::sort_responder_emails($newandkeep, $data->formid);

        self::update_external_assignments($emailnew, $emailcancel, $data->formid, $data->duedate, $asmanager);

        if ($data->duenotifications && !empty($emailkeep)) {
            // Send an email with an updated duedate to everyone in emailkeep.
            // No need to translate these messages as they are going to external users.
            if ($asmanager) {
                $emailsubject = get_string('managerupdatesubject', 'totara_feedback360', $strvars);
                $email_str = get_string('managerupdateemail', 'totara_feedback360', $strvars);
                $sendas = $USER;
            } else {
                $emailsubject = get_string('updatesubject', 'totara_feedback360', $strvars);
                $email_str = get_string('updateemail', 'totara_feedback360', $strvars);
                $sendas = $userfrom;
            }

            foreach ($emailkeep as $email) {
                $userto = \totara_core\totara_user::get_external_user($email);
                email_to_user($userto, $sendas, $emailsubject, $email_str, $email_str);
            }
        }
    }

    /**
     * Create the records for feedback360_resp_assignment.
     *
     * @param array() $newassignments   An array of email addresses to create feedback360_email_assignment records for
     * @param array() $cancellations    An array of email addresses to cancel existing requests to
     * @param int $userformid           The id of the linked feedback360_user_assignment
     * @param int $duedate              The date they should submit feedback by, for the email
     * @param boolean $asmanager        Whether we are sending as the user or as their manager
     *                                  defaults to sending as the user.
     */
    public static function update_external_assignments($newassignments, $cancellations, $userformid, $duedate, $asmanager = false) {
        global $DB, $CFG, $USER;

        $user_assignment = $DB->get_record('feedback360_user_assignment', array('id' => $userformid));
        $feedback360 = $DB->get_record('feedback360', array('id' => $user_assignment->feedback360id), '*', MUST_EXIST);
        $userfrom = $DB->get_record('user', array('id' => $user_assignment->userid));

        // Create and link the email and resp assignments.
        $emailvars = new stdClass();
        if ($asmanager) {
            $emailvars->fullname = fullname($USER);
            $emailvars->staffname = fullname($userfrom);
            $userfrom = $USER;
        } else {
            $emailvars->fullname = fullname($userfrom);
        }

        if ($duedate) {
            $dueupdate = new stdClass();
            $dueupdate->id = $userformid;
            $dueupdate->timedue = $duedate;
            $DB->update_record('feedback360_user_assignment', $dueupdate);
        }

        $resp_assignment = new stdClass();
        $resp_assignment->feedback360userassignmentid = $userformid;
        $resp_assignment->timeassigned = time();

        // Randomise order users are added to ensure ids can't be used to identify users.
        // This loses the keys, DO NOT rely on them after this.
        shuffle($newassignments);

        foreach ($newassignments as $email) {
            // Create the feedback360_email_assignment.
            $email_assignment = new stdClass();
            $email_assignment->email = $email;

            // Create a string that would be very difficult to replicate.
            // The token generated here is for use by the user giving feedback (opposite to 'requestertoken').
            $responderstringtohash = $email . 'responder' . $userformid . time() . get_site_identifier();
            $email_assignment->token = sha1($responderstringtohash);

            $emailid = $DB->insert_record('feedback360_email_assignment', $email_assignment);

            // Create and link the feedback360_resp_assignment.
            $resp_assignment->userid = $CFG->siteguest; // They aren't a user so we'll put them down as guests.
            $resp_assignment->feedback360emailassignmentid = $emailid;
            $resp_assignment->requestertoken = self::create_requestertoken();
            $resp_assignment->id = $DB->insert_record('feedback360_resp_assignment', $resp_assignment);

            // Set up some variables for the email.
            $params = array('token' => $email_assignment->token);
            $url = new moodle_url('/totara/feedback360/feedback.php', $params);
            $emailvars->link = html_writer::link($url, $url->out());
            $emailvars->url = $url->out();

            if ($asmanager) {
                $emailplain = get_string('manageremailrequeststr', 'totara_feedback360', $emailvars);
                $emailhtml = get_string('manageremailrequesthtml', 'totara_feedback360', $emailvars);
                $emailsubject = get_string('manageremailrequestsubject', 'totara_feedback360', $emailvars);
            } else {
                $emailplain = get_string('emailrequeststr', 'totara_feedback360', $emailvars);
                $emailhtml = get_string('emailrequesthtml', 'totara_feedback360', $emailvars);
                $emailsubject = get_string('emailrequestsubject', 'totara_feedback360', $emailvars);
            }

            // Send the email requesting feedback from external email.
            $userto = \totara_core\totara_user::get_external_user($email);

            // Create a message.
            $message = new stdClass();
            $message->component         = 'moodle';
            $message->name              = 'instantmessage';
            $message->courseid          = SITEID;
            $message->userfrom          = $userfrom;
            $message->userto            = $userto;
            $message->subject           = $emailsubject;
            $message->fullmessage       = $emailplain;
            $message->fullmessageformat = FORMAT_PLAIN;
            $message->fullmessagehtml   = $emailhtml;
            $message->smallmessage      = $emailplain;

            message_send($message);

            \totara_feedback360\event\request_created::create_from_instance($resp_assignment, $user_assignment->userid, $email)->trigger();
        }

        if (empty($feedback360->anonymous)) {
            // Only allow cancellations when a feedback isn't anonymous.
            foreach ($cancellations as $email) {
                $sql = "SELECT ra.*, ea.email
                        FROM {feedback360_resp_assignment} ra
                        JOIN {feedback360_email_assignment} ea
                        on ra.feedback360emailassignmentid = ea.id
                        WHERE ra.feedback360userassignmentid = ?
                        and ea.email = ?";
                $params = array($userformid, $email);
                $resp_assign = $DB->get_record_sql($sql, $params);

                if (!empty($resp_assignment->timecompleted)) {
                    // We can't cancel completed responses.
                    continue;
                }

                feedback360::cancel_resp_assignment($resp_assign, $asmanager);
            }
        }
    }

    /**
     * Checks that the timestamp for a given due date is valid in terms of being further in future
     * than now and the current due date if there is one.
     *
     * The timestamp should be cleaned as an integer before this (e.g. by setting the type on a form element).
     *
     * 0 is equivalent to not set. This allows timestamps to go from being set to not set.
     *
     * @param int $newduedate - timestamp intended to be
     * @param int $userformid - id from the feedback360_user_assignment table.
     * @return array containing any error. This will have the key of 'duedate' to correspond to the form element.
     */
    public static function validate_new_timedue_timestamp($newduedate, $userformid) {
        global $DB;
        $errors = array();

        // We currently allow setting of empty values.
        if (!empty($newduedate)) {
            // If they have set a due date check that it is in the future.
            if ($newduedate < time()) {
                $errors['duedate'] = get_string('error:duedatepast', 'totara_feedback360');
            }

            $oldduedate = $DB->get_field('feedback360_user_assignment', 'timedue', array('id' => $userformid));

            // If we are updating an existing request, check that the due date is the same or further in the future.
            if (!empty($oldduedate) and ($oldduedate > $newduedate)) {
                $errors['duedate'] = get_string('error:newduedatebeforeold', 'totara_feedback360');
            }
        }

        return $errors;
    }

    public static function update_timedue($duedate, $userformid) {
        global $DB;

        // Update the due date.
        $user_assignment = $DB->get_record('feedback360_user_assignment', array('id' => $userformid));
        $user_assignment->timedue = $duedate;
        $DB->update_record('feedback360_user_assignment', $user_assignment);
    }
}

/**
 * Exceptions related to Feedback360
 */
class feedback360_exception extends Exception {
}

/**
 * Listener for feedback360 specific events.
 */
class feedback360_event_handler {
    /**
     * User deleted message handler
     *
     * @param \totara_appraisal\event\appraisal_user_deleted $event
     */
    public static function feedback360_user_deleted(\core\event\user_deleted $event) {
        global $DB;

        $userid = $event->objectid;
        // Wipe data in feedback360 assigned to this user.
        $transaction = $DB->start_delegated_transaction();
        $userassignments = $DB->get_records('feedback360_user_assignment', array('userid' => $userid));
        $assignments = array();
        // Find all the responses from other users TO this user.
        foreach ($userassignments as $userassignment) {
            $assignmentid = $userassignment->id;
            $assignments[] = $assignmentid;
            $feedback360id = $userassignment->feedback360id;
            // Check for related email_assignments and delete them.
            $sql = "SELECT feedback360emailassignmentid FROM {feedback360_resp_assignment}
                     WHERE feedback360userassignmentid = ?
                       AND feedback360emailassignmentid IS NOT NULL";
            if ($emails = $DB->get_fieldset_sql($sql, array($assignmentid))) {
                $DB->delete_records_list('feedback360_email_assignment', 'id', $emails);
            }
            // Get all resp_assignments from other users TO this user and clean up scale and question data.
            if ($responses = $DB->get_records('feedback360_resp_assignment', array('feedback360userassignmentid' => $assignmentid))) {
                foreach ($responses as $response) {
                    $DB->delete_records('feedback360_scale_data', array('feedback360respassignmentid' => $response->id));
                    $DB->delete_records('feedback360_quest_data_' . $feedback360id, array('feedback360respassignmentid' => $response->id));
                }
            }
        }
        // Now remove all the resp_assignments above in one query.
        $DB->delete_records_list('feedback360_resp_assignment', 'feedback360userassignmentid', $assignments);

        // Clean up responses for other users requested FROM this user.
        $sql = "SELECT fra.id, fua.feedback360id FROM {feedback360_resp_assignment} fra
                  JOIN {feedback360_user_assignment} fua ON fra.feedback360userassignmentid = fua.id
                 WHERE fra.userid = ?";
            if ($responses = $DB->get_records_sql($sql, array($userid))) {
            foreach ($responses as $response) {
                $feedback360id = $response->feedback360id;
                $DB->delete_records('feedback360_scale_data', array('feedback360respassignmentid' => $response->id));
                $DB->delete_records('feedback360_quest_data_' . $feedback360id, array('feedback360respassignmentid' => $response->id));
            }
        }
        // Finally clean up this users assignments and responses.
        $DB->delete_records('feedback360_resp_assignment', array('userid' => $userid));
        $DB->delete_records('feedback360_user_assignment', array('userid' => $userid));
        $transaction->allow_commit();
    }
}

/**
 * Serves the folder files.
 *
 * @category files
 * @param stdClass $course course object
 * @param stdClass $cm course module
 * @param context $context context object
 * @param string $filearea file area
 * @param array $args extra arguments
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 * @return bool false if file not found, does not return if found - just send the file
 */
function totara_feedback360_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options=array()) {
    global $USER, $DB, $SESSION;

    if (strpos($filearea, 'quest_') === 0) {
        // This is 0 if the image was not uploaded by the responder ie static image.
        $responderassignmentid = (int)$args[0];
        $questionid = (int)str_replace('quest_', '', $filearea);

        $question = new feedback360_question($questionid);

        if (!($question)) {
            send_file_not_found();
        }

        /** @var array $visibleforusers list of user ids that can see file in the feedback that are subordinates to the current user */
        $visibleforusers = array();
        if (!has_capability('totara/feedback360:managefeedback360', context_system::instance())) {
            $users = \totara_job\job_assignment::get_direct_staff_userids($USER->id);
            foreach ($users as $user) {
                $usercontext = context_user::instance($user);
                if ((has_capability('totara/feedback360:viewmanagestafffeedback', $usercontext)
                        || has_capability('totara/feedback360:viewstaffreceivedfeedback360', $usercontext)
                        || has_capability('totara/feedback360:viewstaffrequestedfeedback360', $usercontext))
                    && ($responderassignmentid == 0 || $question->user_can_view($responderassignmentid, $user))
                ) {
                    $visibleforusers[] = $user;
                }

            }
            if (count($visibleforusers) == 0 && !($responderassignmentid == 0 || $question->user_can_view($responderassignmentid, $USER->id))) {
                send_file_not_found();
            }
        }

        $visibleforusers[] = $USER->id;

        // List SQL for users that the feedback has a user assignment for a user that the current user can view their feedback.
        list($uamatchsql, $uaparams) = $DB->get_in_or_equal($visibleforusers, SQL_PARAMS_NAMED);

        // List SQL for users that the feedback has a responder assignment for a user that the current user can view their feedback.
        list($ramatchsql, $raparams) = $DB->get_in_or_equal($visibleforusers, SQL_PARAMS_NAMED);

        /*
         * Check if the feedback has a user assignment responder assignment for the current user or one of their subordinates
         * that they can see the feedbacks of.
         */
        $sql = "SELECT 1
                  FROM {feedback360_quest_field} qf
            INNER JOIN {feedback360_user_assignment} ua ON ua.feedback360id = qf.feedback360id
             LEFT JOIN {feedback360_resp_assignment} ra ON ra.feedback360userassignmentid = ua.id
                 WHERE qf.id = :questionid AND ((ra.userid {$ramatchsql} AND ra.userid <> :guestid) OR ua.userid {$uamatchsql}) ";
        $params = array_merge($raparams, $uaparams, array('questionid' => $questionid, 'guestid' => guest_user()->id));

        $user_can_view = is_siteadmin($USER) || $DB->record_exists_sql($sql, $params);

        // Checks if the user has being assigned by email to the feedback
        // This is a hack to get around authenticating anonymous users when viewing files in feeback360.
        if (!empty($SESSION->totara_feedback360_usertoken)) {
            // Make sure the token is valid.
            if ($DB->record_exists_sql("
                SELECT 1
                  FROM {feedback360_email_assignment} fea
            INNER JOIN {feedback360_resp_assignment} fra ON fea.id = fra.feedback360emailassignmentid
            INNER JOIN {feedback360_user_assignment} fua ON fua.id = fra.feedback360userassignmentid
            INNER JOIN {feedback360} f ON fua.feedback360id = f.id
            INNER JOIN {feedback360_quest_field} qf ON qf.feedback360id = f.id
                 WHERE fea.token = :token AND qf.id = :questionid", ['token' => $SESSION->totara_feedback360_usertoken, 'questionid' => $questionid])) {
                $user_can_view = true;
            }
        }

        // If the user can neither view the feedback through being assigned, subordinate being assigned or email assignment.
        if (!$user_can_view) {
            send_file_not_found();
        }
    } else {
        // Assumes that the file is in the description for the feedback.
        $feedback360id = (int)$args[0];
        if (!isloggedin()) {
            send_file_not_found();
        }

        $canview = false;
        // If the have the capability to manage their feedbacks and are assigned to the feedback.
        if (has_capability('totara/feedback360:manageownfeedback360', $context)
            && $DB->record_exists('feedback360_user_assignment', array('userid' => $USER->id, 'feedback360id' => $feedback360id))) {
            $canview = true;
        }

        // They should be able to see the files if they can request feedback from someone.
        $users = \totara_job\job_assignment::get_direct_staff_userids($USER->id);
        foreach ($users as $user) {
            $usercontext = context_user::instance($user);
            if (has_capability('totara/feedback360:managestafffeedback', $usercontext)
                && $DB->record_exists('feedback360_user_assignment', array('userid' => $user, 'feedback360id' => $feedback360id))) {
                $canview = true;
                break;
            }
        }

        // If they can manage any feedback.
        if (has_capability('totara/feedback360:managefeedback360', $context)) {
            $canview = true;
        }

        if (!$canview) {
            send_file_not_found();
        }
    }

    $fs = get_file_storage();
    if (!$file = $fs->get_file($context->id, 'totara_feedback360', $filearea, $args[0], '/', $args[1])) {
        send_file_not_found();
    }

    \core\session\manager::write_close();
    send_stored_file($file, 60*60, 0, true, $options);
}

require_once($CFG->dirroot . '/user/selector/lib.php');

class request_feedback_potential_user_selector extends user_selector_base {
    protected $guestid;
    protected $courseid;

    public function __construct($name, $options, $currentusers = array()) {
        parent::__construct($name, $options);
        $this->guestid = $options['guestid'];
        $this->userid = $options['userid'];
        $this->currentusers = $options['currentusers'];
    }

    /**
     * Potential users to request feedback from
     * @param string $search
     * @return array
     */
    public function find_users($search) {
        global $DB;

        list($wherecondition, $params) = $this->search_sql($search, 'u');
        $params['userid'] = $this->userid;

        $fields      = 'SELECT ' . $this->required_fields_sql('u');

        $sql = " FROM {user} u
                 WHERE
                     u.deleted = 0
                 AND u.suspended = 0
                 AND u.id != :userid
                 AND $wherecondition";

        if (!empty($this->currentusers)) {
            list($usersql, $userparams) = $DB->get_in_or_equal($this->currentusers, SQL_PARAMS_NAMED, 'param', false);

            $sql .= " AND u.id $usersql";
            $params = array_merge($params, $userparams);
        }

        if (!$this->is_validating()) {
            $potentialmemberscount = $DB->count_records_sql("SELECT COUNT(DISTINCT u.id) $sql", $params);
            if ($potentialmemberscount > $this->maxusersperpage) {
                return $this->too_many_results($search, $potentialmemberscount);
            }
        }

        $availableusers = $DB->get_records_sql($fields . $sql, $params);

        if (empty($availableusers)) {
            return array();
        }

        if ($search) {
            $group = get_string('potentialusersmatching', 'totara_feedback360', $search);
        } else {
            $group = get_string('potentialusers', 'totara_feedback360');
        }

        return array($group => $availableusers);
    }
}

class request_feedback_current_user_selector extends user_selector_base {
    protected $guestid;
    protected $courseid;
    protected $currentusers;

    public function __construct($name, $options, $currentusers = array()) {
        parent::__construct($name, $options);
        $this->guestid = $options['guestid'];
        $this->userid = $options['userid'];
        $this->currentusers = $options['currentusers'];
        $this->completedusers = $options['completedusers'];
    }

    /**
     * Current users
     * @param string $search
     * @return array
     */
    public function find_users($search) {
        global $DB;

        if (empty($this->currentusers)) {
            return array();
        }

        list($wherecondition, $params) = $this->search_sql($search, 'u');
        list($userssql, $userparams) = $DB->get_in_or_equal($this->currentusers, SQL_PARAMS_NAMED);
        if (!empty($this->completedusers)) {
            list($completedsql, $completedparams) = $DB->get_in_or_equal($this->completedusers, SQL_PARAMS_NAMED, 'param', false);
            $completedsql = ' AND u.id ' . $completedsql;
        } else {
            $completedsql = '';
            $completedparams = array();
        }

        $fields      = 'SELECT ' . $this->required_fields_sql('u');

        $sql = " FROM {user} u
                 WHERE
                     u.deleted = 0
                 AND u.suspended = 0
                 AND u.id $userssql
                 $completedsql
                 AND $wherecondition";

        $params = array_merge($userparams, $completedparams, $params);

        if (!$this->is_validating()) {
            $potentialmemberscount = $DB->count_records_sql("SELECT COUNT(DISTINCT u.id) $sql", $params);
            if ($potentialmemberscount > $this->maxusersperpage) {
                return $this->too_many_results($search, $potentialmemberscount);
            }
        }

        $availableusers = $DB->get_records_sql($fields . $sql, $params);

        if (empty($availableusers)) {
            return array();
        }

        if ($search) {
            $group = get_string('currentusersmatching', 'totara_feedback360', $search);
        } else {
            $group = get_string('currentusers', 'totara_feedback360');
        }

        return array($group => $availableusers);
    }
}
