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
 * Unit tests for (some of) mod/quiz/locallib.php.
 *
 * @package    mod_quiz
 * @category   test
 * @copyright  2008 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License
 */


defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/mod/quiz/lib.php');

/**
 * @copyright  2008 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License
 */
class mod_quiz_lib_testcase extends advanced_testcase {
    public function test_quiz_has_grades() {
        $quiz = new stdClass();
        $quiz->grade = '100.0000';
        $quiz->sumgrades = '100.0000';
        $this->assertTrue(quiz_has_grades($quiz));
        $quiz->sumgrades = '0.0000';
        $this->assertFalse(quiz_has_grades($quiz));
        $quiz->grade = '0.0000';
        $this->assertFalse(quiz_has_grades($quiz));
        $quiz->sumgrades = '100.0000';
        $this->assertFalse(quiz_has_grades($quiz));
    }

    public function test_quiz_format_grade() {
        $quiz = new stdClass();
        $quiz->decimalpoints = 2;
        $this->assertEquals(quiz_format_grade($quiz, 0.12345678), format_float(0.12, 2));
        $this->assertEquals(quiz_format_grade($quiz, 0), format_float(0, 2));
        $this->assertEquals(quiz_format_grade($quiz, 1.000000000000), format_float(1, 2));
        $quiz->decimalpoints = 0;
        $this->assertEquals(quiz_format_grade($quiz, 0.12345678), '0');
    }

    public function test_quiz_get_grade_format() {
        $quiz = new stdClass();
        $quiz->decimalpoints = 2;
        $this->assertEquals(quiz_get_grade_format($quiz), 2);
        $this->assertEquals($quiz->questiondecimalpoints, -1);
        $quiz->questiondecimalpoints = 2;
        $this->assertEquals(quiz_get_grade_format($quiz), 2);
        $quiz->decimalpoints = 3;
        $quiz->questiondecimalpoints = -1;
        $this->assertEquals(quiz_get_grade_format($quiz), 3);
        $quiz->questiondecimalpoints = 4;
        $this->assertEquals(quiz_get_grade_format($quiz), 4);
    }

    public function test_quiz_format_question_grade() {
        $quiz = new stdClass();
        $quiz->decimalpoints = 2;
        $quiz->questiondecimalpoints = 2;
        $this->assertEquals(quiz_format_question_grade($quiz, 0.12345678), format_float(0.12, 2));
        $this->assertEquals(quiz_format_question_grade($quiz, 0), format_float(0, 2));
        $this->assertEquals(quiz_format_question_grade($quiz, 1.000000000000), format_float(1, 2));
        $quiz->decimalpoints = 3;
        $quiz->questiondecimalpoints = -1;
        $this->assertEquals(quiz_format_question_grade($quiz, 0.12345678), format_float(0.123, 3));
        $this->assertEquals(quiz_format_question_grade($quiz, 0), format_float(0, 3));
        $this->assertEquals(quiz_format_question_grade($quiz, 1.000000000000), format_float(1, 3));
        $quiz->questiondecimalpoints = 4;
        $this->assertEquals(quiz_format_question_grade($quiz, 0.12345678), format_float(0.1235, 4));
        $this->assertEquals(quiz_format_question_grade($quiz, 0), format_float(0, 4));
        $this->assertEquals(quiz_format_question_grade($quiz, 1.000000000000), format_float(1, 4));
    }

    /**
     * Test deleting a quiz instance.
     */
    public function test_quiz_delete_instance() {
        global $SITE, $DB;
        $this->setAdminUser();

        // Setup a quiz with 1 standard and 1 random question.
        $quizgenerator = $this->getDataGenerator()->get_plugin_generator('mod_quiz');
        $quiz = $quizgenerator->create_instance(array('course' => $SITE->id, 'questionsperpage' => 3, 'grade' => 100.0));

        $questiongenerator = $this->getDataGenerator()->get_plugin_generator('core_question');
        $cat = $questiongenerator->create_question_category();
        $standardq = $questiongenerator->create_question('shortanswer', null, array('category' => $cat->id));

        quiz_add_quiz_question($standardq->id, $quiz);
        quiz_add_random_questions($quiz, 0, $cat->id, 1, false);

        // Get the random question.
        $randomq = $DB->get_record('question', array('qtype' => 'random'));

        quiz_delete_instance($quiz->id);

        // Check that the random question was deleted.
        $count = $DB->count_records('question', array('id' => $randomq->id));
        $this->assertEquals(0, $count);
        // Check that the standard question was not deleted.
        $count = $DB->count_records('question', array('id' => $standardq->id));
        $this->assertEquals(1, $count);

        // Check that all the slots were removed.
        $count = $DB->count_records('quiz_slots', array('quizid' => $quiz->id));
        $this->assertEquals(0, $count);

        // Check that the quiz was removed.
        $count = $DB->count_records('quiz', array('id' => $quiz->id));
        $this->assertEquals(0, $count);
    }

    /**
     * Test checking the completion state of a quiz.
     */
    public function test_quiz_get_completion_state() {
        global $CFG, $DB;

        // Enable completion before creating modules, otherwise the completion data is not written in DB.
        $CFG->enablecompletion = true;

        // Create a course and student.
        $course = $this->getDataGenerator()->create_course(array('enablecompletion' => true));
        $passstudent = $this->getDataGenerator()->create_user();
        $failstudent = $this->getDataGenerator()->create_user();
        $studentrole = $DB->get_record('role', array('shortname' => 'student'));
        $this->assertNotEmpty($studentrole);

        // Enrol students.
        $this->assertTrue($this->getDataGenerator()->enrol_user($passstudent->id, $course->id, $studentrole->id));
        $this->assertTrue($this->getDataGenerator()->enrol_user($failstudent->id, $course->id, $studentrole->id));

        // Make a scale and an outcome.
        $scale = $this->getDataGenerator()->create_scale();
        $data = array('courseid' => $course->id,
                      'fullname' => 'Team work',
                      'shortname' => 'Team work',
                      'scaleid' => $scale->id);
        $outcome = $this->getDataGenerator()->create_grade_outcome($data);

        // Make a quiz with the outcome on.
        $quizgenerator = $this->getDataGenerator()->get_plugin_generator('mod_quiz');
        $data = array('course' => $course->id,
                      'outcome_'.$outcome->id => 1,
                      'grade' => 100.0,
                      'questionsperpage' => 0,
                      'sumgrades' => 1,
                      'completion' => COMPLETION_TRACKING_AUTOMATIC,
                      'completionpass' => 1);
        $quiz = $quizgenerator->create_instance($data);
        $cm = get_coursemodule_from_id('quiz', $quiz->cmid);

        // Create a couple of questions.
        $questiongenerator = $this->getDataGenerator()->get_plugin_generator('core_question');

        $cat = $questiongenerator->create_question_category();
        $question = $questiongenerator->create_question('numerical', null, array('category' => $cat->id));
        quiz_add_quiz_question($question->id, $quiz);

        $quizobj = quiz::create($quiz->id, $passstudent->id);

        // Set grade to pass.
        $item = grade_item::fetch(array('courseid' => $course->id, 'itemtype' => 'mod',
                                        'itemmodule' => 'quiz', 'iteminstance' => $quiz->id, 'outcomeid' => null));
        $item->gradepass = 80;
        $item->update();

        // Start the passing attempt.
        $quba = question_engine::make_questions_usage_by_activity('mod_quiz', $quizobj->get_context());
        $quba->set_preferred_behaviour($quizobj->get_quiz()->preferredbehaviour);

        $timenow = time();
        $attempt = quiz_create_attempt($quizobj, 1, false, $timenow, false, $passstudent->id);
        quiz_start_new_attempt($quizobj, $quba, $attempt, 1, $timenow);
        quiz_attempt_save_started($quizobj, $quba, $attempt);

        // Process some responses from the student.
        $attemptobj = quiz_attempt::create($attempt->id);
        $tosubmit = array(1 => array('answer' => '3.14'));
        $attemptobj->process_submitted_actions($timenow, false, $tosubmit);

        // Finish the attempt.
        $attemptobj = quiz_attempt::create($attempt->id);
        $this->assertTrue($attemptobj->has_response_to_at_least_one_graded_question());
        $attemptobj->process_finish($timenow, false);

        // Start the failing attempt.
        $quba = question_engine::make_questions_usage_by_activity('mod_quiz', $quizobj->get_context());
        $quba->set_preferred_behaviour($quizobj->get_quiz()->preferredbehaviour);

        $timenow = time();
        $attempt = quiz_create_attempt($quizobj, 1, false, $timenow, false, $failstudent->id);
        quiz_start_new_attempt($quizobj, $quba, $attempt, 1, $timenow);
        quiz_attempt_save_started($quizobj, $quba, $attempt);

        // Process some responses from the student.
        $attemptobj = quiz_attempt::create($attempt->id);
        $tosubmit = array(1 => array('answer' => '0'));
        $attemptobj->process_submitted_actions($timenow, false, $tosubmit);

        // Finish the attempt.
        $attemptobj = quiz_attempt::create($attempt->id);
        $this->assertTrue($attemptobj->has_response_to_at_least_one_graded_question());
        $attemptobj->process_finish($timenow, false);

        // Check the results.
        $this->assertTrue(quiz_get_completion_state($course, $cm, $passstudent->id, 'return'));
        $this->assertFalse(quiz_get_completion_state($course, $cm, $failstudent->id, 'return'));
    }

    public function test_quiz_get_user_attempts() {
        global $DB;

        $dg = $this->getDataGenerator();
        $quizgen = $dg->get_plugin_generator('mod_quiz');
        $course = $dg->create_course();
        $u1 = $dg->create_user();
        $u2 = $dg->create_user();
        $u3 = $dg->create_user();
        $u4 = $dg->create_user();
        $role = $DB->get_record('role', ['shortname' => 'student']);

        $dg->enrol_user($u1->id, $course->id, $role->id);
        $dg->enrol_user($u2->id, $course->id, $role->id);
        $dg->enrol_user($u3->id, $course->id, $role->id);
        $dg->enrol_user($u4->id, $course->id, $role->id);

        $quiz1 = $quizgen->create_instance(['course' => $course->id, 'sumgrades' => 2]);
        $quiz2 = $quizgen->create_instance(['course' => $course->id, 'sumgrades' => 2]);

        // Questions.
        $questgen = $dg->get_plugin_generator('core_question');
        $quizcat = $questgen->create_question_category();
        $question = $questgen->create_question('numerical', null, ['category' => $quizcat->id]);
        quiz_add_quiz_question($question->id, $quiz1);
        quiz_add_quiz_question($question->id, $quiz2);

        $quizobj1a = quiz::create($quiz1->id, $u1->id);
        $quizobj1b = quiz::create($quiz1->id, $u2->id);
        $quizobj1c = quiz::create($quiz1->id, $u3->id);
        $quizobj1d = quiz::create($quiz1->id, $u4->id);
        $quizobj2a = quiz::create($quiz2->id, $u1->id);

        // Set attempts.
        $quba1a = question_engine::make_questions_usage_by_activity('mod_quiz', $quizobj1a->get_context());
        $quba1a->set_preferred_behaviour($quizobj1a->get_quiz()->preferredbehaviour);
        $quba1b = question_engine::make_questions_usage_by_activity('mod_quiz', $quizobj1b->get_context());
        $quba1b->set_preferred_behaviour($quizobj1b->get_quiz()->preferredbehaviour);
        $quba1c = question_engine::make_questions_usage_by_activity('mod_quiz', $quizobj1c->get_context());
        $quba1c->set_preferred_behaviour($quizobj1c->get_quiz()->preferredbehaviour);
        $quba1d = question_engine::make_questions_usage_by_activity('mod_quiz', $quizobj1d->get_context());
        $quba1d->set_preferred_behaviour($quizobj1d->get_quiz()->preferredbehaviour);
        $quba2a = question_engine::make_questions_usage_by_activity('mod_quiz', $quizobj2a->get_context());
        $quba2a->set_preferred_behaviour($quizobj2a->get_quiz()->preferredbehaviour);

        $timenow = time();

        // User 1 passes quiz 1.
        $attempt = quiz_create_attempt($quizobj1a, 1, false, $timenow, false, $u1->id);
        quiz_start_new_attempt($quizobj1a, $quba1a, $attempt, 1, $timenow);
        quiz_attempt_save_started($quizobj1a, $quba1a, $attempt);
        $attemptobj = quiz_attempt::create($attempt->id);
        $attemptobj->process_submitted_actions($timenow, false, [1 => ['answer' => '3.14']]);
        $attemptobj->process_finish($timenow, false);

        // User 2 goes overdue in quiz 1.
        $attempt = quiz_create_attempt($quizobj1b, 1, false, $timenow, false, $u2->id);
        quiz_start_new_attempt($quizobj1b, $quba1b, $attempt, 1, $timenow);
        quiz_attempt_save_started($quizobj1b, $quba1b, $attempt);
        $attemptobj = quiz_attempt::create($attempt->id);
        $attemptobj->process_going_overdue($timenow, true);

        // User 3 does not finish quiz 1.
        $attempt = quiz_create_attempt($quizobj1c, 1, false, $timenow, false, $u3->id);
        quiz_start_new_attempt($quizobj1c, $quba1c, $attempt, 1, $timenow);
        quiz_attempt_save_started($quizobj1c, $quba1c, $attempt);

        // User 4 abandons the quiz 1.
        $attempt = quiz_create_attempt($quizobj1d, 1, false, $timenow, false, $u4->id);
        quiz_start_new_attempt($quizobj1d, $quba1d, $attempt, 1, $timenow);
        quiz_attempt_save_started($quizobj1d, $quba1d, $attempt);
        $attemptobj = quiz_attempt::create($attempt->id);
        $attemptobj->process_abandon($timenow, true);

        // User 1 attempts the quiz three times (abandon, finish, in progress).
        $quba2a = question_engine::make_questions_usage_by_activity('mod_quiz', $quizobj2a->get_context());
        $quba2a->set_preferred_behaviour($quizobj2a->get_quiz()->preferredbehaviour);

        $attempt = quiz_create_attempt($quizobj2a, 1, false, $timenow, false, $u1->id);
        quiz_start_new_attempt($quizobj2a, $quba2a, $attempt, 1, $timenow);
        quiz_attempt_save_started($quizobj2a, $quba2a, $attempt);
        $attemptobj = quiz_attempt::create($attempt->id);
        $attemptobj->process_abandon($timenow, true);

        $quba2a = question_engine::make_questions_usage_by_activity('mod_quiz', $quizobj2a->get_context());
        $quba2a->set_preferred_behaviour($quizobj2a->get_quiz()->preferredbehaviour);

        $attempt = quiz_create_attempt($quizobj2a, 2, false, $timenow, false, $u1->id);
        quiz_start_new_attempt($quizobj2a, $quba2a, $attempt, 2, $timenow);
        quiz_attempt_save_started($quizobj2a, $quba2a, $attempt);
        $attemptobj = quiz_attempt::create($attempt->id);
        $attemptobj->process_finish($timenow, false);

        $quba2a = question_engine::make_questions_usage_by_activity('mod_quiz', $quizobj2a->get_context());
        $quba2a->set_preferred_behaviour($quizobj2a->get_quiz()->preferredbehaviour);

        $attempt = quiz_create_attempt($quizobj2a, 3, false, $timenow, false, $u1->id);
        quiz_start_new_attempt($quizobj2a, $quba2a, $attempt, 3, $timenow);
        quiz_attempt_save_started($quizobj2a, $quba2a, $attempt);

        // Check for user 1.
        $attempts = quiz_get_user_attempts($quiz1->id, $u1->id, 'all');
        $this->assertCount(1, $attempts);
        $attempt = array_shift($attempts);
        $this->assertEquals(quiz_attempt::FINISHED, $attempt->state);
        $this->assertEquals($u1->id, $attempt->userid);
        $this->assertEquals($quiz1->id, $attempt->quiz);

        $attempts = quiz_get_user_attempts($quiz1->id, $u1->id, 'finished');
        $this->assertCount(1, $attempts);
        $attempt = array_shift($attempts);
        $this->assertEquals(quiz_attempt::FINISHED, $attempt->state);
        $this->assertEquals($u1->id, $attempt->userid);
        $this->assertEquals($quiz1->id, $attempt->quiz);

        $attempts = quiz_get_user_attempts($quiz1->id, $u1->id, 'unfinished');
        $this->assertCount(0, $attempts);

        // Check for user 2.
        $attempts = quiz_get_user_attempts($quiz1->id, $u2->id, 'all');
        $this->assertCount(1, $attempts);
        $attempt = array_shift($attempts);
        $this->assertEquals(quiz_attempt::OVERDUE, $attempt->state);
        $this->assertEquals($u2->id, $attempt->userid);
        $this->assertEquals($quiz1->id, $attempt->quiz);

        $attempts = quiz_get_user_attempts($quiz1->id, $u2->id, 'finished');
        $this->assertCount(0, $attempts);

        $attempts = quiz_get_user_attempts($quiz1->id, $u2->id, 'unfinished');
        $this->assertCount(1, $attempts);
        $attempt = array_shift($attempts);
        $this->assertEquals(quiz_attempt::OVERDUE, $attempt->state);
        $this->assertEquals($u2->id, $attempt->userid);
        $this->assertEquals($quiz1->id, $attempt->quiz);

        // Check for user 3.
        $attempts = quiz_get_user_attempts($quiz1->id, $u3->id, 'all');
        $this->assertCount(1, $attempts);
        $attempt = array_shift($attempts);
        $this->assertEquals(quiz_attempt::IN_PROGRESS, $attempt->state);
        $this->assertEquals($u3->id, $attempt->userid);
        $this->assertEquals($quiz1->id, $attempt->quiz);

        $attempts = quiz_get_user_attempts($quiz1->id, $u3->id, 'finished');
        $this->assertCount(0, $attempts);

        $attempts = quiz_get_user_attempts($quiz1->id, $u3->id, 'unfinished');
        $this->assertCount(1, $attempts);
        $attempt = array_shift($attempts);
        $this->assertEquals(quiz_attempt::IN_PROGRESS, $attempt->state);
        $this->assertEquals($u3->id, $attempt->userid);
        $this->assertEquals($quiz1->id, $attempt->quiz);

        // Check for user 4.
        $attempts = quiz_get_user_attempts($quiz1->id, $u4->id, 'all');
        $this->assertCount(1, $attempts);
        $attempt = array_shift($attempts);
        $this->assertEquals(quiz_attempt::ABANDONED, $attempt->state);
        $this->assertEquals($u4->id, $attempt->userid);
        $this->assertEquals($quiz1->id, $attempt->quiz);

        $attempts = quiz_get_user_attempts($quiz1->id, $u4->id, 'finished');
        $this->assertCount(1, $attempts);
        $attempt = array_shift($attempts);
        $this->assertEquals(quiz_attempt::ABANDONED, $attempt->state);
        $this->assertEquals($u4->id, $attempt->userid);
        $this->assertEquals($quiz1->id, $attempt->quiz);

        $attempts = quiz_get_user_attempts($quiz1->id, $u4->id, 'unfinished');
        $this->assertCount(0, $attempts);

        // Multiple attempts for user 1 in quiz 2.
        $attempts = quiz_get_user_attempts($quiz2->id, $u1->id, 'all');
        $this->assertCount(3, $attempts);
        $attempt = array_shift($attempts);
        $this->assertEquals(quiz_attempt::ABANDONED, $attempt->state);
        $this->assertEquals($u1->id, $attempt->userid);
        $this->assertEquals($quiz2->id, $attempt->quiz);
        $attempt = array_shift($attempts);
        $this->assertEquals(quiz_attempt::FINISHED, $attempt->state);
        $this->assertEquals($u1->id, $attempt->userid);
        $this->assertEquals($quiz2->id, $attempt->quiz);
        $attempt = array_shift($attempts);
        $this->assertEquals(quiz_attempt::IN_PROGRESS, $attempt->state);
        $this->assertEquals($u1->id, $attempt->userid);
        $this->assertEquals($quiz2->id, $attempt->quiz);

        $attempts = quiz_get_user_attempts($quiz2->id, $u1->id, 'finished');
        $this->assertCount(2, $attempts);
        $attempt = array_shift($attempts);
        $this->assertEquals(quiz_attempt::ABANDONED, $attempt->state);
        $attempt = array_shift($attempts);
        $this->assertEquals(quiz_attempt::FINISHED, $attempt->state);

        $attempts = quiz_get_user_attempts($quiz2->id, $u1->id, 'unfinished');
        $this->assertCount(1, $attempts);
        $attempt = array_shift($attempts);

        // Multiple quiz attempts fetched at once.
        $attempts = quiz_get_user_attempts([$quiz1->id, $quiz2->id], $u1->id, 'all');
        $this->assertCount(4, $attempts);
        $attempt = array_shift($attempts);
        $this->assertEquals(quiz_attempt::FINISHED, $attempt->state);
        $this->assertEquals($u1->id, $attempt->userid);
        $this->assertEquals($quiz1->id, $attempt->quiz);
        $attempt = array_shift($attempts);
        $this->assertEquals(quiz_attempt::ABANDONED, $attempt->state);
        $this->assertEquals($u1->id, $attempt->userid);
        $this->assertEquals($quiz2->id, $attempt->quiz);
        $attempt = array_shift($attempts);
        $this->assertEquals(quiz_attempt::FINISHED, $attempt->state);
        $this->assertEquals($u1->id, $attempt->userid);
        $this->assertEquals($quiz2->id, $attempt->quiz);
        $attempt = array_shift($attempts);
        $this->assertEquals(quiz_attempt::IN_PROGRESS, $attempt->state);
        $this->assertEquals($u1->id, $attempt->userid);
        $this->assertEquals($quiz2->id, $attempt->quiz);
    }

}
