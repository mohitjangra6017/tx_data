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

use coding_exception;
use context_module;
use Exception;
use mod_assessment\entity\SimpleDBO;
use mod_assessment\factory\assessment_version_assignment_factory;
use mod_assessment\helper\role;
use mod_assessment\processor\role_assignments;
use moodle_database;
use moodle_exception;

defined('MOODLE_INTERNAL') || die();

class version extends SimpleDBO
{

    public const TABLE = 'assessment_version';

    /** @var int $assessmentid */
    public int $assessmentid;

    /** @var int $operator */
    public int $operator = 0;

    /** @var int|null $timeclosed */
    public ?int $timeclosed;

    /** @var int|null $timeopened */
    public ?int $timeopened;

    /** @var int $version */
    public int $version;

    /** @var bool $singleevaluator */
    public bool $singleevaluator;

    /** @var bool $singlereviewer */
    public bool $singlereviewer;

    public static function get_tablename(): string
    {
        return self::TABLE;
    }

    /**
     * @param assessment $assessment
     * @return bool|self
     * @global moodle_database $DB
     */
    public static function active_instance(assessment $assessment)
    {
        global $DB;
        $record = $DB->get_record_select(
            self::TABLE,
            "assessmentid = :assessmentid AND timeopened > 0 AND timeclosed IS NULL",
            ['assessmentid' => $assessment->id]
        );
        if (!$record) {
            return false;
        }

        $instance = new self();
        $instance->set_data($record);
        return $instance;
    }

    /**
     * Retrieve the default version for editing - returns the open version or the latest draft if none open
     *
     * @param assessment $assessment
     * @return self
     * @global moodle_database $DB
     */
    public static function instance_for_edit(assessment $assessment)
    {
        global $DB;

        // Get active.
        $instance = self::active_instance($assessment);
        if ($instance) {
            return $instance;
        }

        // Get latest.
        $records = $DB->get_records(self::TABLE, ['assessmentid' => $assessment->id], 'id DESC', '*', 0, 1);
        if (!$records) {
            throw new moodle_exception('invalidrecord', null, null, self::TABLE);
        }
        $record = current($records);

        $instance = new self();
        $instance->set_data($record);

        return $instance;
    }

    public static function instances_from_assessment(assessment $assessment): array
    {
        global $DB;

        $instances = [];
        $records = $DB->get_records(self::TABLE, ['assessmentid' => $assessment->id], 'version');

        if ($records) {
            foreach ($records as $record) {
                $instance = new self();
                $instances[] = $instance->set_data($record);
            }
        }

        return $instances;
    }

    protected function presave(&$isupdate = false)
    {
        global $DB;
        if (!$isupdate) {
            $versioncount = $DB->count_records(static::TABLE, ['assessmentid' => $this->assessmentid]);
            $this->version = $versioncount + 1;

            // Set defaults.
            if (!$this->operator) {
                $this->operator = rule::OP_AND;
            }
        }
    }

    public function activate()
    {
        // Check if evaluator rules are set.
        if (!$this->has_evaluator_rules() && !$this->has_evaluator_assignments()) {
            throw new Exception(get_string('error_noevaluatorrules', 'assessment'));
        }

        // Check reviewer rules are set if any reviewer question permissions exist.
        if ($this->has_question_permissions_for_reviewer() && !$this->has_reviewer_rules() && !$this->has_reviewer_assignments()) {
            throw new Exception(get_string('error_noreviewerrules', 'assessment'));
        }

        // Check if first stage is locked.
        $firststage = version_stage::instance(['versionid' => $this->get_id(), 'sortorder' => 1]);
        if ($firststage->get_locked()) {
            throw new Exception(get_string('error_stagelockonfirststage', 'assessment'));
        }

        $this->timeopened = time();
        $this->save();

        // For assessments versions with multiple evaluators/reviewers, update any role assignments for enrolled learners
        // @todo: if we are logging, add a logfile_progress_trace
        //\mod_assessment\processor\role_assignments::mark_role_assignments_dirty(0, $this->assessmentid); // don't do it immediately (waits for cron)
        $assessment = assessment::instance(['id' => $this->assessmentid], MUST_EXIST);
        role_assignments::update_role_assignments($assessment); // do it immediately
    }

    public function deactivate()
    {
        $assessment = assessment::instance(['id' => $this->assessmentid], MUST_EXIST);
        $context = context_module::instance($assessment->get_cmid());
        require_capability('mod/assessment:editinstance', $context);

        // Close version.
        $this->set_timeclosed(time())->save();

        // Deactivate active attempts.
        $activeattempts = attempt::instances(['versionid' => $this->id, 'status' => attempt::STATUS_INPROGRESS]);
        foreach ($activeattempts as $activeattempt) {
            $activeattempt->set_status(attempt::STATUS_TERMINATED)->save();
        }

        // Remove unstarted attempts.
        $unstartedattempts = attempt::instances(['versionid' => $this->id, 'status' => attempt::STATUS_NOTSTARTED]);
        foreach ($unstartedattempts as $unstartedattempt) {
            $unstartedattempt->delete();
        }
    }

    public function get_id(): ?int
    {
        return $this->id;
    }

    public function get_assessmentid(): int
    {
        return $this->assessmentid;
    }

    /**
     * @return string
     */
    public function get_status(): string
    {
        if ($this->is_closed()) {
            return 'closed';
        }
        if ($this->is_active()) {
            return 'active';
        }
        if ($this->is_draft()) {
            return 'draft';
        }
        return 'unknown';
    }

    /**
     * @return bool
     */
    public function is_active(): bool
    {
        return !empty($this->timeopened) && empty($this->timeclosed);
    }

    /**
     * @return bool
     */
    public function is_closed(): bool
    {
        return !empty($this->timeclosed);
    }

    /**
     * @return bool
     */
    public function is_draft(): bool
    {
        return empty($this->timeopened);
    }

    /**
     * @return bool
     */
    public function is_multistage(): bool
    {
        static $ismultistage;
        if (!isset($ismultistage)) {
            $ismultistage = (count(version_stage::instances(['versionid' => $this->id])) > 1);
        }
        return $ismultistage;
    }

    /**
     * @param int $assessmentid
     * @return self
     */
    public function set_assessmentid(int $assessmentid): version
    {
        $this->assessmentid = $assessmentid;
        return $this;
    }

    /**
     * @duplicates ruleset::set_operator
     * @param int $operator
     * @return self
     * @throws coding_exception
     */
    public function set_operator(int $operator): version
    {
        // Validate operator.
        if (!in_array($operator, [rule::OP_AND, rule::OP_OR])) {
            throw new coding_exception('Invalid operator for evaluator ruleset(global): (' . $operator . ')');
        }
        $this->operator = $operator;
        return $this;
    }

    /**
     * @param int $timeclosed
     * @return self
     */
    public function set_timeclosed(int $timeclosed): version
    {
        $this->timeclosed = $timeclosed;
        return $this;
    }

    /**
     * @param int $timeopened
     * @return self
     */
    public function set_timeopened(int $timeopened): version
    {
        $this->timeopened = $timeopened;
        return $this;
    }

    /**
     * @param int $version
     * @return self
     */
    public function set_version(int $version): version
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @param bool $singleevaluator
     * @return self
     */
    public function set_singleevaluator(bool $singleevaluator): version
    {
        $this->singleevaluator = $singleevaluator;
        return $this;
    }

    /**
     * @param bool $singlereviewer
     * @return self
     */
    public function set_singlereviewer(bool $singlereviewer): version
    {
        $this->singlereviewer = $singlereviewer;
        return $this;
    }

    /**
     * Whether this version has any rules defined for the specified role
     *
     * @param int $role see \mod_assessment\helper\role constants
     * @return bool
     */
    public function has_rules_for_role(int $role): bool
    {
        global $DB;
        return $DB->record_exists(ruleset::TABLE, ['role' => $role, 'versionid' => $this->id]);
    }

    public function has_evaluator_assignments(): bool
    {
        return assessment_version_assignment_factory::exists(['versionid' => $this->get_id(), 'role' => role::EVALUATOR]);
    }

    /**
     * Whether this version has any rules defined for the evaluator role
     *
     * @return bool
     */
    public function has_evaluator_rules(): bool
    {
        return $this->has_rules_for_role(role::EVALUATOR);
    }

    public function has_reviewer_assignments(): bool
    {
        return assessment_version_assignment_factory::exists(['versionid' => $this->get_id(), 'role' => role::REVIEWER]);
    }

    /**
     * Whether this version has any rules defined for the reviewer role
     *
     * @return bool
     */
    public function has_reviewer_rules(): bool
    {
        return $this->has_rules_for_role(role::REVIEWER);
    }

    /**
     * Whether this version has any question permissions configured for the specified role
     *
     * @param int $role see \mod_assessment\helper\role constants
     * @return bool
     */
    public function has_question_permissions_for_role(int $role): bool
    {
        global $DB;

        $has_perms = false;

        $permsfield = '';
        switch ($role) {
            case role::LEARNER:
                $permsfield = 'learnerperms';
                break;
            case role::EVALUATOR:
                $permsfield = 'evaluatorperms';
                break;
            case role::REVIEWER:
                $permsfield = 'reviewerperms';
                break;
        }

        if (!empty($permsfield)) {
            $sql = "SELECT q.{$permsfield}
                    FROM {" . question::TABLE . "} q
                    JOIN {" . version_question::TABLE . "} vq ON vq.questionid = q.id AND vq.versionid = :versionid
                    WHERE q.{$permsfield} > 0";
            $has_perms = $DB->record_exists_sql($sql, ['versionid' => $this->id]);
        }

        return $has_perms;
    }

    /**
     * Whether this version has any question permissions defined for the reviewer role
     *
     * @return bool
     */
    public function has_question_permissions_for_reviewer(): bool
    {
        return $this->has_question_permissions_for_role(role::REVIEWER);
    }

    /**
     * Whether this version has any question permissions defined for the evaluator role
     *
     * @return bool
     */
    public function has_question_permissions_for_evaluator(): bool
    {
        return $this->has_question_permissions_for_role(role::EVALUATOR);
    }

    /**
     * Whether this version has any question permissions defined for the learner role
     *
     * @return bool
     */
    public function has_question_permissions_for_learner(): bool
    {
        return $this->has_question_permissions_for_role(role::LEARNER);
    }
}
