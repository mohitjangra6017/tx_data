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

namespace mod_assessment\model;

use mod_assessment\entity\SimpleDBO;
use mod_assessment\helper;
use moodle_database;

defined('MOODLE_INTERNAL') || die();

class version_stage extends SimpleDBO
{

    public const TABLE = 'assessment_version_stage';

    /** @var int */
    public int $locked;

    /** @var int $sortorder */
    public int $sortorder;

    /** @var int $stageid */
    public int $stageid;

    /** @var int $sortorder */
    public int $versionid;

    public static function get_next_sortorder(int $versionid): int
    {
        global $DB;
        $sql = "SELECT sortorder+1
                  FROM (SELECT 0 AS sortorder UNION SELECT sortorder FROM {" . self::TABLE . "}) avq
                 WHERE NOT EXISTS ( SELECT NULL
                                      FROM ( SELECT sortorder FROM {" . self::TABLE . "} WHERE versionid = :versionid ) avq2
                                     WHERE avq2.sortorder = avq.sortorder+1 )
              ORDER BY sortorder";
        $sortorder = $DB->get_field_sql($sql, ['versionid' => $versionid], IGNORE_MULTIPLE);
        return $sortorder ? $sortorder : 1;
    }

    /**
     * Sets sortorder to earliest available slot
     *
     * @return self
     * @global moodle_database $DB
     */
    public function calculate_sortorder(): version_stage
    {
        $this->sortorder = self::get_next_sortorder($this->versionid);
        return $this;
    }

    public function get_locked(): int
    {
        return $this->locked;
    }

    public function get_sortorder(): int
    {
        return $this->sortorder;
    }

    public function get_stageid(): int
    {
        return $this->stageid;
    }

    public function get_versionid(): int
    {
        return $this->versionid;
    }

    public function set_locked(int $locked): self
    {
        $this->locked = $locked;
        return $this;
    }

    /**
     * @param int $stageid
     * @return self
     */
    public function set_stageid($stageid): version_stage
    {
        $this->stageid = $stageid;
        return $this;
    }

    /**
     * @param int $versionid
     * @return self
     */
    public function set_versionid($versionid): version_stage
    {
        $this->versionid = $versionid;
        return $this;
    }

    /**
     *
     * @param bool $include_non_questions (for non-questions view = can answer)
     * @return array
     */
    public function get_stage_permissions($include_non_questions = false): array
    {
        $perms = [helper\role::EVALUATOR => 0, helper\role::LEARNER => 0, helper\role::REVIEWER => 0];
        $questions = question::instances_from_versionstage($this);
        foreach ($questions as $question) {
            if (!$question->is_question() && !$include_non_questions) {
                continue;
            }

            $perms[helper\role::EVALUATOR] = $perms[helper\role::EVALUATOR] | $question->evaluatorperms;
            $perms[helper\role::LEARNER] = $perms[helper\role::LEARNER] | $question->learnerperms;
            $perms[helper\role::REVIEWER] = $perms[helper\role::REVIEWER] | $question->reviewerperms;
        }
        return $perms;
    }

    /**
     * @return bool[]
     */
    public function get_roles_cananswer(): array
    {
        $roles = [];
        $questions = question::instances_from_versionstage($this);
        foreach ($questions as $question) {
            if (!$question->is_question()) {
                continue;
            }
            if ($question->check_permission(new role(helper\role::LEARNER), question_perms::CAN_ANSWER)) {
                $roles[helper\role::LEARNER] = true;
            }
            if ($question->check_permission(new role(helper\role::EVALUATOR), question_perms::CAN_ANSWER)) {
                $roles[helper\role::EVALUATOR] = true;
            }
            if ($question->check_permission(new role(helper\role::REVIEWER), question_perms::CAN_ANSWER)) {
                $roles[helper\role::REVIEWER] = true;
            }
        }
        return $roles;
    }
}
