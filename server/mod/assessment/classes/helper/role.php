<?php
/**
 * @copyright 2017-2019 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @author Alex Glover <alex.glover@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

namespace mod_assessment\helper;

use coding_exception;
use context_module;
use Exception;
use mod_assessment\model;
use mod_assessment\model\assessment;

class role
{

    public const ADMIN = 100;
    public const EVALUATOR = 80;
    public const REVIEWER = 50;
    public const LEARNER = 10;

    /**
     * @return array
     */
    public static function get_roles(): array
    {
        return [
            self::EVALUATOR => get_string('roleevaluator', 'mod_assessment'),
            self::REVIEWER => get_string('rolereviewer', 'mod_assessment'),
            self::LEARNER => get_string('rolelearner', 'mod_assessment'),
        ];
    }

    /**
     * @param assessment $assessment
     * @param int $userid
     * @param null $learnerid
     * @param bool $archived Whether to only check archived attempts (otherwise checks non-archived).
     * @return int
     * @throws coding_exception
     */
    public static function get_assessment_role(assessment $assessment, int $userid, $learnerid = null, $archived = false): ?int
    {
        if ($userid == $learnerid) {
            return self::LEARNER;
        }

        if (is_siteadmin()) {
            return self::ADMIN;
        }

        $context = context_module::instance($assessment->get_cmid());
        $can_edit_assessment = has_capability('mod/assessment:editinstance', $context);

        if ($learnerid) {
            // When user has multiple roles (e.g. evaluator is reviewer), evaluator role is prioritised.
            $attempts = model\attempt::instances_for_user($learnerid, $assessment->id, [$userid], null, $archived);
            if (!empty($attempts)) {
                return self::EVALUATOR;
            }
            $attempts = model\attempt::instances_for_user($learnerid, $assessment->id, null, [$userid], $archived);
            if (!empty($attempts)) {
                return self::REVIEWER;
            }
            if ($can_edit_assessment) {
                return self::ADMIN;
            }
            return null;
        } else {
            if ($can_edit_assessment) {
                return self::ADMIN;
            }
            return self::LEARNER;
        }
    }

    /**
     * Get a matrix of valid roles for the specified attempt.
     *
     * @param \mod_assessment\model\attempt $attempt
     * @param int $userid
     * @return array booleans keyed by role ids
     * @throws Exception
     */
    public static function get_attempt_roles(model\attempt $attempt, int $userid): array
    {
        $isself = $userid == $attempt->userid;
        $roles_info = [
            self::LEARNER => $isself,
            self::EVALUATOR => !$isself && ($attempt->is_single_evaluator() ? self::is_user_evaluator($userid, $attempt->id) : $attempt->is_evaluator_valid($userid)),
            self::REVIEWER => !$isself && ($attempt->is_single_reviewer() ? self::is_user_reviewer($userid, $attempt->id) : $attempt->is_reviewer_valid($userid)),
        ];

        $valid_roles = [];
        foreach ($roles_info as $role => $has_role) {
            if ($has_role) {
                $valid_roles[] = $role;
            }
        }

        if (!empty($valid_roles)) {
            return $valid_roles;
        }

        throw new Exception(get_string('norole', 'mod_assessment'));
    }

    /**
     * DEVIOTIS2
     * the concept of "other role" is no longer valid now we have evaluator AND reviewer,
     * so now we return all others roles that can participate in the asessment.
     *
     * @param int $role
     * @return string|array
     */
    public static function get_other_roles(int $role)
    {

        $roles = [
            self::LEARNER,
            self::EVALUATOR,
            self::REVIEWER
        ];
        $roles = array_combine($roles, $roles);

        if (!isset($roles[$role])) {
            return [self::ADMIN];
        }

        unset($roles[$role]);
        return $roles;
    }

    /**
     * @param int $role
     * @return string
     * @throws coding_exception
     */
    public static function get_string(int $role): string
    {
        switch ($role) {
            case self::ADMIN:
                return get_string('roleadmin', 'assessment');
            case self::EVALUATOR:
                return get_string('roleevaluator', 'assessment');
            case self::LEARNER:
                return get_string('rolelearner', 'assessment');
            case self::REVIEWER:
                return get_string('rolereviewer', 'assessment');
            default:
                throw new coding_exception('Invalid role: ' . $role);
        }
    }

    /**
     * @param model\role $role $role
     * @return bool
     */
    public static function is_read_only_role(model\role $role): bool
    {
        $roles = model\role::get_read_only_roles();
        return in_array($role->value(), array_keys($roles));
    }

    /**
     *
     * @param int|object $user
     * @param int $role
     * @param int $attemptid
     * @return bool
     */
    public static function is_user_assigned_role($user, int $role, $attemptid = 0): bool
    {
        global $DB, $USER;

        if (is_null($user)) {
            $user = $USER;
        }

        $userid = (is_scalar($user) ? $user : $user->id);
        $params = ['userid' => $userid, 'role' => $role];
        if ($attemptid > 0) {
            $params['attemptid'] = $attemptid;
        }
        return $DB->record_exists('assessment_attempt_assignments', $params);
    }

    /**
     *
     * @param int|object $user
     * @param int $attemptid
     * @return boolean
     */
    public static function is_user_evaluator($user = null, $attemptid = 0): bool
    {
        return self::is_user_assigned_role($user, self::EVALUATOR, $attemptid);
    }

    /**
     *
     * @param int|object $user
     * @param int $attemptid
     * @return bool
     */
    public static function is_user_reviewer($user = null, $attemptid = 0): bool
    {
        return self::is_user_assigned_role($user, self::REVIEWER, $attemptid);
    }
}
