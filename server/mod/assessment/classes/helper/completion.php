<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

namespace mod_assessment\helper;

use mod_assessment\model;
use mod_assessment\model\assessment;

class completion
{

    /**
     * @param assessment $assessment
     * @param int $learnerid
     * @return bool
     */
    public static function is_complete(assessment $assessment, int $learnerid): bool
    {

        $grade = grade::get_best_grade($assessment, $learnerid);

        if ($assessment->statusrequired == $assessment::STATUS_COMPLETE) {
            $attempts = model\attempt::instances_for_user($learnerid, $assessment->id); // Don't include archived attempts.
            $attempts = array_filter($attempts, function ($attempt) {
                return $attempt->is_complete();
            });
            return !empty($attempts);
        } elseif ($assessment->statusrequired == $assessment::STATUS_PASSED) {
            return $grade >= $assessment->gradepass;
        }

        return false;
    }

    /**
     * @param \mod_assessment\model\attempt $attempt
     * @param int $role
     * @return bool
     */
    public static function is_role_complete(model\attempt $attempt, int $role): bool
    {
        $version = model\version::instance(['id' => $attempt->versionid], MUST_EXIST);

        // Get stages required for completion for role.
        $versionstages = model\version_stage::instances(['versionid' => $version->id]);
        $requiredstages = array_filter($versionstages, function (model\version_stage $versionstage) use ($role) {
            return self::is_stage_required($versionstage, $role);
        });

        // Find any uncompleted stages.
        $incompletestages = array_filter($requiredstages, function (model\version_stage $versionstage) use ($attempt, $role) {
            $completion = model\stage_completion::instance([
                'attemptid' => $attempt->id,
                'stageid' => $versionstage->stageid,
                'role' => $role
            ]);
            return !($completion && $completion->timecompleted);
        });

        return count($incompletestages) == 0;
    }

    /**
     * @param model\version_stage $versionstage
     * @param int $role
     * @return false|int|string
     */
    public static function is_stage_required(model\version_stage $versionstage, int $role)
    {
        $perms = $versionstage->get_stage_permissions();
        if (!isset($perms[$role])) {
            return false;
        }
        return $perms[$role] & model\question_perms::CAN_ANSWER;
    }
}
