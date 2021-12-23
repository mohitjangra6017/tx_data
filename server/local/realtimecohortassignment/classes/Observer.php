<?php
/**
 * Local cohort observer.
 *
 * @package   local_realtimecohortassignment
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2016 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace local_realtimecohortassignment;


global $CFG;

use coding_exception;
use core\event\base;
use core\event\course_completed;
use core\event\user_created;
use core\event\user_loggedin;
use core\event\user_updated;
use dml_exception;
use ReflectionClass;
use ReflectionException;
use stdClass;
use totara_sync_element_user;

require_once($CFG->dirroot . '/cohort/lib.php');
require_once ($CFG->dirroot . '/admin/tool/totara_sync/elements/user.php');

/**
 * Observer for local_realtimecohortassignment plugin.
 *
 * @package local_realtimecohortassignment
 */
class Observer
{

    /** @var string Never update user audiences */
    public const USER_LOGIN_EVENT_NONE = 'none';

    /** @var string Update user audiences if this is the first time this user has logged in */
    public const USER_LOGIN_EVENT_FIRST = 'first';

    /** @var string Update user audiences every time the user logs in */
    public const USER_LOGIN_EVENT_EVERY = 'every';
    /**
     * Update dynamic cohorts for the created/updated user.
     *
     * @param base $event
     * @throws coding_exception
     * @throws dml_exception
     * @throws ReflectionException
     */
    public static function updateDynamicCohorts(base $event)
    {
        global $DB;

        if (!$event instanceof user_loggedin
            && !$event instanceof user_created
            && !$event instanceof user_updated
        ) {
            return;
        }

        $config = get_config('local_realtimecohortassignment');

        if (empty($config->totarasync) && totara_sync_element_user::$isRunning) {
            return;
        }

        $userLoginEvent = $config->event_userlogin ?? static::USER_LOGIN_EVENT_NONE;

        $needsUpdate = false;
        if ($event instanceof user_loggedin) {
            $userid = $event->userid;
            $firstLogin =
                $userLoginEvent === static::USER_LOGIN_EVENT_FIRST
                && get_user_preferences('local_realtimecohortassignment_needsupdate', true, $userid);
            if ($firstLogin) {
                $needsUpdate = true;
            } else {
                $needsUpdate = get_user_preferences('local_realtimecohortassignment_needsupdate', true, $userid);
            }
            if ($userLoginEvent === static::USER_LOGIN_EVENT_NONE) {
                return;
            }
        } else if ($event instanceof user_created || $event instanceof user_updated) {
            $userid = $event->relateduserid;
            $needsUpdate = true;
        } else if ($event instanceof course_completed && get_config('local_realtimecohortassignment', 'event_course_completed')) {
            $userid = $event->relateduserid;
            $needsUpdate = true;
        }


        if ($needsUpdate || $userLoginEvent === static::USER_LOGIN_EVENT_EVERY) {
            $cohorts = $DB->get_records('cohort', ['cohorttype' => \cohort::TYPE_DYNAMIC]);

            foreach ($cohorts as $cohort) {
                if (totara_cohort_is_active($cohort) && !static::hasBrokenRules($cohort)) {
                    \totara_cohort_update_dynamic_cohort_members($cohort->id, $userid);
                }
            }
        }

        set_user_preference('local_realtimecohortassignment_needsupdate', false, $userid);
    }

    /**
     * Returns the supported user login event types.
     * @return array
     */
    public static function getUserLoginEvents()
    {
        return [
            static::USER_LOGIN_EVENT_NONE => get_string('user_login_event_' . static::USER_LOGIN_EVENT_NONE, 'local_realtimecohortassignment'),
            static::USER_LOGIN_EVENT_FIRST => get_string('user_login_event_' . static::USER_LOGIN_EVENT_FIRST, 'local_realtimecohortassignment'),
            static::USER_LOGIN_EVENT_EVERY => get_string('user_login_event_' . static::USER_LOGIN_EVENT_EVERY, 'local_realtimecohortassignment'),
        ];
    }

    /**
     * Indicate if a cohort has broken rules.
     * This code is based on @see totara_cohort_broken_rules
     * @param stdClass $cohort
     * @return bool
     * @throws ReflectionException
     * @throws dml_exception
     */
    protected static function hasBrokenRules(stdClass $cohort): bool
    {
        global $DB, $PAGE;

        // Page context is needed for retrieving rules.
        if (is_null(self::get_page_context())) {
            $PAGE->set_context(\context_system::instance());
        }

        $ruleSets = $DB->get_records(
            'cohort_rulesets',
            [
                'rulecollectionid' => $cohort->activecollectionid,
            ],
            'sortorder'
        );

        foreach ($ruleSets as $ruleSet) {
            $rules = $DB->get_records('cohort_rules', ['rulesetid' => $ruleSet->id]);
            foreach ($rules as $ruleRec) {
                if (!cohort_rules_get_rule_definition($ruleRec->ruletype, $ruleRec->name)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get page context from moodle_page without setting it or triggering unset errors.
     *
     * @return mixed
     * @throws ReflectionException
     */
    private static function get_page_context()
    {
        global $PAGE;
        $pageInspector = new ReflectionClass($PAGE);
        $context = $pageInspector->getProperty('_context');
        $context->setAccessible(true);
        return $context->getValue($PAGE);
    }
}
