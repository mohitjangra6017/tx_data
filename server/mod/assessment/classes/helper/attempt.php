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

use mod_assessment\model;
use mod_assessment\model\assessment;

class attempt
{

    public const UNLIMITED = -1;

    /**
     * @param assessment $assessment
     * @param int $userid
     * @return boolean
     */
    public static function can_make_new_attempt(assessment $assessment, int $userid): bool
    {
        // If no active version, user cannot make attempt.
        if (!model\version::active_instance($assessment)) {
            return false;
        }

        $maxattempts = self::get_max_attempts($assessment, $userid);
        $userattempts = self::count_user_attempts($assessment, $userid);

        if ($maxattempts == self::UNLIMITED) {
            return true;
        }

        return $maxattempts > $userattempts;
    }

    /**
     * @param assessment $assessment
     * @param int $userid
     * @param bool $archived Whether to count archived attempts only
     * @return int
     */
    public static function count_user_attempts(assessment $assessment, int $userid, $archived = false): int
    {
        return count(model\attempt::instances_for_user($userid, $assessment->id, null, null, $archived));
    }

    /**
     * @param assessment $assessment
     * @param int $userid
     * @return int
     */
    public static function get_max_attempts(assessment $assessment, int $userid): int
    {
        $override = model\override::instance(['assessmentid' => $assessment->id, 'userid' => $userid]);
        if ($override && $assessment->extraattempts) {
            return $override->attempts;
        }

        return $assessment->attempts;
    }

    /**
     * @param assessment $assessment
     * @param int $userid
     * @return int
     *
     * Don't include archived attempts in calculation.
     */
    public static function get_remaining_attempts($assessment, $userid)
    {
        $maxattempts = self::get_max_attempts($assessment, $userid);
        $userattempts = self::count_user_attempts($assessment, $userid);

        if ($maxattempts == self::UNLIMITED) {
            return -1;
        }

        $remaining = $maxattempts - $userattempts;
        if ($remaining < 0) {
            return 0;
        } else {
            return $remaining;
        }
    }

    /**
     * @param int $status
     * @return string
     */
    public static function status($status)
    {
        switch ($status) {
            case model\attempt::STATUS_COMPLETE:
                return \get_string('completed', 'assessment');
            case model\attempt::STATUS_FAILED:
                return \get_string('failed', 'assessment');
            case model\attempt::STATUS_INPROGRESS:
                return \get_string('inprogress', 'assessment');
            case model\attempt::STATUS_NOTSTARTED:
                return \get_string('notstarted', 'assessment');
            case model\attempt::STATUS_TERMINATED:
                return \get_string('terminated', 'assessment');
            default:
                throw new \coding_exception('Invalid status code: ' . $status);
        }
    }

    /**
     * @param model\attempt $attempt
     * @return string
     * @global type $DB
     */
    public static function get_evaluator_fullnames($attempt, $limit = 0)
    {
        global $DB;
        $evaluators = $attempt->get_evaluators($limit);
        $names = [];
        if (empty($evaluators)) {
            return false;
        }
        foreach ($evaluators as $userid => $evaluator) {
            $names[$userid] = fullname($evaluator);
        }

        return $names;
    }

    /**
     * @param model\attempt $attempt
     * @return string
     * @global type $DB
     */
    public static function get_reviewer_fullnames($attempt, $limit = 0)
    {
        global $DB;
        $reviewers = $attempt->get_reviewers($limit);
        $names = [];
        if (empty($reviewers)) {
            return false;
        }
        foreach ($reviewers as $userid => $reviewer) {
            $names[$userid] = fullname($reviewer);
        }

        return $names;
    }
}
