<?php

/**
 * Test for block_rate_course webservice
 *
 * @package    block
 * @subpackage rate_course
 * @copyright  &copy; 2015 Nine Lanterns Pty Ltd  {@link http://www.ninelanterns.com.au}
 * @author     tri.le
 * @version    1.0
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->dirroot.'/webservice/tests/helpers.php');
require_once($CFG->dirroot.'/blocks/rate_course/externallib.php');

/**
 * Class block_rate_course_externallib_testcase
 * @group kineo
 * @group tke
 * @group block_rate_course
 */
class block_rate_course_externallib_testcase extends externallib_advanced_testcase {

    function test_create_recommendation_invalid_user_id() {

        $this->resetAfterTest(true);

        $user = $this->getDataGenerator()->create_user();
        $user_id = $user->id + 1;

        $course = $this->getDataGenerator()->create_course();

        try {
            $success = block_rate_course_external::suggest_course($user_id, $course->id);
            $this->assertFalse($success, 'Suggest a course to a non existent users should not success');
        } catch (Exception $ex) {
            $this->assertTrue($ex instanceof invalid_parameter_exception, 'invalid_parameter_exception when user does not exist');
        }
    }

    function test_create_recommendation_invalid_course_id() {

        $this->resetAfterTest(true);

        $user = $this->getDataGenerator()->create_user();

        $course = $this->getDataGenerator()->create_course();
        $course_id = $course->id + 1;

        try {
            $success = block_rate_course_external::suggest_course($user->id, $course_id);
            $this->assertFalse($success, 'Suggest a course to a non existent users should not success');
        } catch (Exception $ex) {
            $this->assertTrue($ex instanceof invalid_parameter_exception, 'invalid_parameter_exception should be thrown if course does not exist');
        }
    }

    function test_create_recommendation_valid() {

        $this->resetAfterTest();

        $user = $this->getDataGenerator()->create_user();
        $course = $this->getDataGenerator()->create_course();

        $success = block_rate_course_external::suggest_course($user->id, $course->id, '');
        $this->assertTrue($success, 'Could suggest a course if all parameters are valid');

        $this->assertTrue(
            block_rate_course::check_already_recommended(0, $course->id, $user->id, 'webservice_skills_assessor'),
            'A course recommendation has to be created'
        );

        // suggest the course again, this need to throw an exception
        try {
            $success = block_rate_course_external::suggest_course($user->id, $course->id, '');
            $this->assertFalse($success, 'Cannot recommend a course again');    // this line should never be reached
        } catch (Exception $ex) {
            $this->assertTrue($ex instanceof invalid_parameter_exception, 'Should throw exception when the course already exist');
        }
    }
}
