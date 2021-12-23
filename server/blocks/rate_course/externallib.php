<?php

/**
 * The service lib file
 *
 * @package    block
 * @subpackage rate_course
 * @copyright  &copy; 2015 Nine Lanterns Pty Ltd  {@link http://www.ninelanterns.com.au}
 * @author     tri.le
 * @version    1.0
 */

global $CFG;

require_once($CFG->libdir . '/externallib.php');
require_once($CFG->dirroot . '/blocks/moodleblock.class.php');
require_once($CFG->dirroot . '/blocks/rate_course/block_rate_course.php');
require_once($CFG->dirroot . '/totara/core/totara.php');

class block_rate_course_external extends external_api {

    public static function suggest_course_parameters() {
        return new external_function_parameters(array(
            'userid'   => new external_value(PARAM_INT, get_string('webservice_param_userid_desc', 'block_rate_course', VALUE_REQUIRED)),
            'courseid' => new external_value(PARAM_INT, get_string('webservice_param_courseid_desc', 'block_rate_course'), VALUE_REQUIRED),
            'source'   => new external_value(PARAM_TEXT, get_string('webservice_param_source_desc', 'block_rate_course'), VALUE_REQUIRED),
        ));
    }

    public static function suggest_course_returns() {
        return new external_value(PARAM_BOOL, get_string('webservice_return_suggest_course', 'block_rate_course'));
    }

    public static function suggest_course($user_id, $course_id, $source='') {
        global $DB;
        $params = self::validate_parameters(self::suggest_course_parameters(), array('userid' => $user_id, 'courseid' => $course_id, 'source' => $source));

        $transaction = $DB->start_delegated_transaction();

        if (!($user = $DB->get_record('user', array('id' => $params['userid'])))) {
            throw new invalid_parameter_exception('User id does not exist');
        }

        if (!($course = $DB->get_record('course', array('id' => $params['courseid'])))) {
            throw new invalid_parameter_exception('Course id does not exist');
        }

        if ($course->containertype != \container_course\course::get_type()) {
            return false;
        }

        if (!totara_course_is_viewable($course, $user->id)) {
            return false;
        }

        if (empty($params['source'])) {
            $params['source'] = 'skills_assessor';
        }
        $normalised_source = strtolower(preg_replace('/\s/', '_', $params['source']));

        // prefix with webservice if not yet
        if (strpos($normalised_source, 'webservice_')!==0) {
            $normalised_source = 'webservice_'.$normalised_source;
        }

        // has the recommendation already existed?
        if (block_rate_course::check_already_recommended(0, $params['courseid'], $params['userid'], $normalised_source)) {
            throw new invalid_parameter_exception('Recommendation already exists');
        }

        // perform the recommendation
        $data = (object) array(
            'userid' => 0,
            'courseid' => $params['courseid'],
            'useridto' => $params['userid'],
            'source'   => $normalised_source,
        );
        block_rate_course::save_recommendation($data);

        $transaction->allow_commit();

        return true;
    }

}