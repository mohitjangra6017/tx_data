<?php
/**
 * External API
 *
 * @package   block_related_courses
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2016 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

defined('MOODLE_INTERNAL') || die;


/**
 * Class block_related_courses_external
 */
class block_related_courses_external extends external_api
{
    /**
     * @return external_function_parameters
     */
    public static function enrol_parameters(): external_function_parameters
    {
        return new external_function_parameters(
            [
                'id' => new external_value(PARAM_INT, 'Course id'),
            ]
        );
    }

    /**
     * @return external_value
     */
    public static function enrol_returns(): external_value
    {
        return new external_value(PARAM_BOOL, 'Return true on success');
    }

    /**
     * Enrol the current user into a course. Same logic as require_login() enrolments
     *
     * @param int $id id of the course.
     * @return bool true on success, false on failure
     */
    public static function enrol(int $id): bool
    {
        global $USER;

        $enrolled = false;

        if (!totara_course_is_viewable($id, $USER->id)) {
            return $enrolled;
        }

        // First ask all enabled enrol instances in course if they want to auto enrol user.
        $enrolInstances = enrol_get_instances($id, true);
        $enrols = enrol_get_plugins(true);
        foreach ($enrolInstances as $instance) {
            // Get a duration for the enrolment, a timestamp in the future, 0 (always) or false.
            $until = $enrols[$instance->enrol]->try_autoenrol($instance, true);
            if ($until !== false) {
                if ($until == 0) {
                    $until = ENROL_MAX_TIMESTAMP;
                }
                $USER->enrol['enrolled'][$id] = $until;
                $enrolled =  true;
                break;
            }
        }

        return $enrolled;
    }
}