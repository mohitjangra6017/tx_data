<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_credly\entity;

use core\orm\entity\repository;
use core\orm\query\builder;
use core\orm\query\field;
use core\orm\query\raw_field;
use stdClass;

class BadgeIssueRepository extends repository
{
    /**
     * Reimplemented this function to provide the correct return type.
     * @param int $id
     * @return BadgeIssue|null
     */
    public function find(int $id): ?BadgeIssue
    {
        return parent::find($id);
    }

    /**
     * Reimplemented this function to provide the correct return type.
     * @param int $id
     * @return BadgeIssue
     */
    public function find_or_fail(int $id): BadgeIssue
    {
        return parent::find_or_fail($id);
    }

    public function findByUserIdAndBadgeId(int $userId, int $badgeId): ?BadgeIssue
    {
        return builder::table($this->get_table())
            ->where('userid', $userId)
            ->where('badgeid', $badgeId)
            ->map_to(BadgeIssue::class)
            ->one(false);
    }

    /**
     * @param int $userId
     * @return BadgeIssue[]
     */
    public function findManyByUserId(int $userId): array
    {
        return builder::table($this->get_table())
            ->where('userid', $userId)
            ->map_to(BadgeIssue::class)
            ->fetch();
    }

    /**
     * @param int $badgeId
     * @return BadgeIssue[]
     */
    public function findManyByBadgeId(int $badgeId): array
    {
        return builder::table($this->get_table())
            ->where('badgeid', $badgeId)
            ->map_to(BadgeIssue::class)
            ->fetch();
    }

    /**
     * @param int $programId
     * @return BadgeIssue[]
     */
    public function findManyByProgramId(int $programId): array
    {
        return builder::table($this->get_table())
            ->where('programid', $programId)
            ->map_to(BadgeIssue::class)
            ->fetch();
    }

    /**
     * @param int $certficationId
     * @param int $userId
     * @return ?BadgeIssue
     */
    public function findByUserIdAndCertificationId(int $certficationId, int $userId): ?BadgeIssue
    {
        return builder::table($this->get_table())
                      ->where('certificationid', $certficationId)
                      ->where('userid', $userId)
                      ->map_to(BadgeIssue::class)
                      ->one();
    }

    /**
     * @return BadgeIssue[]
     */
    public function findCertificationBadgeIssuesToBeReplaced(): array
    {
        return builder::table($this->get_table())
                      ->where_not_null('certificationid')
                      ->where('status', '=', BadgeIssue::STATUS_REPLACE)
                      ->where('timeexpires', '>', time())
                      ->map_to(BadgeIssue::class)
                      ->fetch();
    }

    /**
     * @param $learningId
     * @return builder
     */
    public function getCourseIssueBuilder($learningId): builder
    {
        return builder::table('course_completions', 'comp')
                      ->select(['comp.id', 'comp.course AS courseid'])
                      ->left_join([BadgeIssue::TABLE, 'bi'], function (builder $builder) {
                          $builder->where_field('bi.userid', '=', 'comp.userid')
                                  ->where_field('bi.courseid', '=', 'comp.course');
                      })
                      ->join(['course', 'c'], 'comp.course', '=', 'c.id')
                      ->join([Badge::TABLE, 'b'], 'b.courseid', '=', 'comp.course')
                      ->where('comp.status', '>=', COMPLETION_STATUS_COMPLETE)
                      ->when($learningId !== null, function (builder $builder) use ($learningId) {
                          $builder->where('comp.course', $learningId);
                      });
    }

    /**
     * @param $learningId
     * @return builder
     */
    public function getProgIssueBuilder($learningId): builder
    {
        global $CFG;
        require_once $CFG->dirroot . '/totara/program/lib.php';

        return builder::table('prog_completion', 'comp')
                      ->select(['comp.id', 'comp.programid'])
                      ->left_join([BadgeIssue::TABLE, 'bi'], function (builder $builder) {
                          $builder->where_field('bi.userid', '=', 'comp.userid')
                                  ->where_field('bi.programid', '=', 'comp.programid');
                      })
                      ->join(['prog', 'p'], 'comp.programid', '=', 'p.id')
                      ->join([Badge::TABLE, 'b'], 'b.programid', '=', 'comp.programid')
                      ->where('comp.coursesetid', 0)
                      ->where('comp.status', STATUS_PROGRAM_COMPLETE)
                      ->when($learningId !== null, function (builder $builder) use ($learningId) {
                          $builder->where('comp.programid', $learningId);
                      });
    }

    /**
     * @param $learningId
     * @return builder
     */
    public function getCertificationIssueBuilder($learningId): builder
    {
        global $CFG;
        require_once $CFG->dirroot . '/totara/certification/lib.php';

        return builder::table('certif_completion', 'comp')
                      ->select(['comp.id', 'comp.certifid AS certificationid', 'comp.timeexpires'])
                      ->left_join([BadgeIssue::TABLE, 'bi'], function (builder $builder) {
                          $builder->where_field('bi.userid', '=', 'comp.userid')
                                  ->where_field('bi.certificationid', '=', 'comp.certifid');
                      })
                      ->join(['prog', 'p'], 'comp.certifid', '=', 'p.certifid')
                      ->join([Badge::TABLE, 'b'], 'b.certificationid', '=', 'comp.certifid')
                      ->where('comp.status', CERTIFSTATUS_COMPLETED)
                      ->where('comp.timeexpires', '>', time())
                      ->when($learningId !== null, function (builder $builder) use ($learningId) {
                          $builder->where('comp.certifid', $learningId);
                      });
    }
    /**
     * Return all badges that are yet to be issued to learners.
     * Passing both User ID and Learning ID will return an array with only 0 or 1 results, if a badge needs to be issued.
     * Omitting User ID and/or Learning ID will return an array of 0 or more results.
     * @param string $learningType
     * @param int|null $userId
     * @param int|null $learningId
     * @return BadgeIssue[]
     */
    public function findBadgesToBeIssuedByLearningType(string $learningType, ?int $userId = null, ?int $learningId = null): array
    {
        switch ($learningType) {
            case 'course':
                $builder = $this->getCourseIssueBuilder($learningId);
                break;
            case 'program':
                $builder = $this->getProgIssueBuilder($learningId);
                break;
            case 'certification':
                $builder = $this->getCertificationIssueBuilder($learningId);
                break;
            default:
                throw new \InvalidArgumentException("Only 'course' or 'program' are valid learning types");
        }

        return $builder->add_select(['comp.userid', 'comp.timecompleted AS issuetime', 'b.id AS badgeid'])
            ->join(['user', 'u'], 'comp.userid', '=', 'u.id')
            ->where_null('bi.id')
            ->where('u.suspended', 0)
            ->where('u.deleted', 0)
            ->when($userId !== null, function (builder $builder) use ($userId) {
                $builder->where('comp.userid', $userId);
            })
            // When the global opt-out is enabled, we need to check if the user has chosen to opt-out.
            // If the user has no preference, or the preference is 0, then they want badge issues.
            ->when(get_config('local_credly', 'allow_opt_out'), function (builder $builder) {
                $builder->left_join(['user_preferences', 'up'], function (builder $builder) {
                    $builder->where_field('u.id', '=', 'up.userid')
                            ->where('up.name', 'local_credly_opt_out');
                })
                    ->where(function (builder $builder) {
                        $builder->where('up.value', '0')
                                ->or_where_null('up.id');
                    });
            })
            ->map_to(function ($item) {
                // This is the id of the course/prog/certification completion not the badge issue id so get rid of it.
                unset($item->id);
                return new BadgeIssue($item);
            })
            ->fetch();
    }

    /**
     * @return BadgeIssue[]
     */
    public function findUnsuccessfulBadgeIssues(): array
    {
        return builder::table(BadgeIssue::TABLE)
                      ->where('status', BadgeIssue::STATUS_RECOVERABLE_FAILURE)
                      ->order_by('id')
                      ->map_to(BadgeIssue::class)
                      ->fetch();
    }
}