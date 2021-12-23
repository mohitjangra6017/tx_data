<?php

namespace local_leaderboard;

use coding_exception;
use container_course\course;
use context_user;
use core\orm\query\builder;
use core\orm\query\exceptions\record_not_found_exception;
use core\tenant_orm_helper;
use dml_exception;
use lang_string;
use local_leaderboard\event\adhoc_leaderboard;

defined('MOODLE_INTERNAL') || die;

class Utils
{
    public const EVENT_NAME_ADHOC = '\\' . adhoc_leaderboard::class;

    public const FREQUENCY_INTERVAL_WEEKS = 0;
    public const FREQUENCY_INTERVAL_DAYS = 1;
    public const FREQUENCY_INTERVAL_HOURS = 2;
    public const FREQUENCY_INTERVAL_MINS = 3;
    public const FREQUENCY_INTERVAL_SECS = 4;

    public const FREQUENCY_OPTIONS = [
        self::FREQUENCY_INTERVAL_WEEKS => 'weeks',
        self::FREQUENCY_INTERVAL_DAYS => 'days',
        self::FREQUENCY_INTERVAL_HOURS => 'hours',
        self::FREQUENCY_INTERVAL_MINS => 'minutes',
        self::FREQUENCY_INTERVAL_SECS => 'seconds',
    ];

    public const GRADE_EVENTS = [
        '\mod_quiz\event\attempt_submitted',
        '\mod_assign\event\submission_graded',
        '\mod_lesson\event\essay_assessed',
        '\mod_scorm\event\status_submitted',
    ];

    /**
     * Return the total score for the given userid
     * @param int $userid
     * @param null|int $scoreid
     * @return int
     * @throws dml_exception
     */
    public static function getUserScore(int $userid, $scoreid = null): int
    {
        global $DB;

        return (int)$DB->get_field_sql(
            'SELECT sum(score)
           FROM {local_leaderboard_user}
          WHERE userid = :userid AND COALESCE (:scoreid, leaderboardid) = leaderboardid',
            ['userid' => $userid, 'scoreid' => $scoreid]
        );
    }

    /**
     * @return bool
     */
    public static function isActiveUsersOnly(): bool
    {
        return !get_config('local_leaderboard', 'activeonly');
    }

    /**
     * Userids configured by custom profile field for exclusion from reporting
     * @return array
     * @throws dml_exception
     */
    public static function getExcludedUserIds(): array
    {
        global $DB;

        $sql = "SELECT uid.userid 
                FROM {config_plugins} AS plug 
                JOIN {user_info_field} AS field ON field.id = " . $DB->sql_cast_char2int('plug.value') . " AND plug.name = 'excludeusersfieldid'  
                JOIN {user_info_data} AS uid ON uid.fieldid = field.id 
                WHERE uid.data = '1'";

        if ($excludedUserids = $DB->get_fieldset_sql($sql)) {
            return $excludedUserids;
        } else {
            return [];
        }
    }

    /**
     * @param int $userId
     * @return lang_string|string|null
     * @throws record_not_found_exception
     * @throws coding_exception
     * @throws dml_exception
     */
    public static function getUserRank(int $userId)
    {
        global $USER;

        $excludedUserids = self::getExcludedUserIds();

        if (in_array($USER->id, $excludedUserids)) {
            return get_string('excluded', 'local_leaderboard');
        }

        $subquery =
            self::getRankSubQuery($excludedUserids)
                ->where('auser.suspended', '=', 0)
                ->where('auser.deleted', '=', 0);

        return builder::table($subquery, 'lboard')
                      ->select('ranking')
                      ->where('userid', '=', $userId)
                      ->value('ranking');
    }

    /**
     * @param array $excludedUserids
     * @return builder
     */
    private static function getRankSubQuery(array $excludedUserids): builder
    {
        return builder::table('local_leaderboard_user', 'lboard')
                      ->select('userid')
                      ->add_select_raw('DENSE_RANK() OVER (ORDER BY SUM(score) DESC) AS ranking')
                      ->join(['user', 'auser'], 'lboard.userid', '=', 'auser.id')
                      ->when(
                          true,
                          function (builder $builder) {
                              global $USER;
                              tenant_orm_helper::restrict_users(
                                  $builder,
                                  'auser.id',
                                  context_user::instance($USER->id)
                              );
                          }
                      )
                      ->when(
                          !empty($excludedUserids),
                          function (builder $builder) use ($excludedUserids) {
                              $builder->where_not_in('auser.id', $excludedUserids);
                          }
                      )
                      ->group_by('userid');
    }

    /**
     * Get lowest rank from all user scores
     * @return mixed
     * @throws dml_exception
     */
    public static function getLowestRank()
    {
        $excludedUserids = self::getExcludedUserIds();

        if (!get_config('local_leaderboard', 'rankuserswithscores')) {
            return builder::table('user', 'auser')
                          ->when(
                              !empty($excludedUserids),
                              function (builder $builder) use ($excludedUserids) {
                                  $builder->where_not_in('auser.id', $excludedUserids);
                              }
                          )
                          ->when(
                              self::isActiveUsersOnly(),
                              function (builder $builder) {
                                  $builder->where('auser.suspended', '=', 0)
                                          ->where('auser.deleted', '=', 0);
                              }
                          )
                          ->when(
                              true,
                              function (builder $builder) {
                                  global $USER;
                                  tenant_orm_helper::restrict_users(
                                      $builder,
                                      'auser.id',
                                      context_user::instance($USER->id)
                                  );
                              }
                          )
                          ->count();
        } else {
            $subQuery = self::getRankSubQuery($excludedUserids)
                            ->when(
                                self::isActiveUsersOnly(),
                                function (builder $builder) {
                                    $builder->where('auser.suspended', '=', 0)
                                            ->where('auser.deleted', '=', 0);
                                }
                            );
            return builder::table($subQuery, 'lboard')
                          ->select('max(ranking)')
                          ->value('max(ranking)');
        }
    }

    /**
     * @return array
     * @throws dml_exception
     */
    public static function getConfiguredEvents(): array
    {
        global $DB;
        return $DB->get_records_sql_menu("SELECT eventname, id FROM {local_leaderboard} where deleted = ?", [0]);
    }

    /**
     * @param int $courseId
     * @param int $userId
     * @return bool
     * @throws coding_exception
     * @throws dml_exception
     */
    public static function canScoreAgain(int $courseId, int $userId): bool
    {
        global $DB;

        [$inSql, $params] =
            $DB->get_in_or_equal([CERTIFSTATUS_ASSIGNED, CERTIFSTATUS_INPROGRESS, CERTIFSTATUS_EXPIRED]);
        $params[] = $courseId;
        $params[] = course::get_type();
        $params[] = $userId;

        $sql = "SELECT *
                FROM {course} AS course
                    JOIN {prog_courseset_course} AS csc ON csc.courseid = course.id
                    JOIN {prog_courseset} AS cs ON cs.id = csc.coursesetid
                    JOIN {prog} AS prog ON prog.id = cs.programid
                    JOIN {prog_user_assignment} AS progass ON progass.programid = prog.id
                    JOIN {certif} AS certif ON certif.id = prog.certifid
                    JOIN {certif_completion} AS cc ON cc.certifid = certif.id AND cc.userid = progass.userid
                WHERE cc.status {$inSql} 
                    AND course.id = ?
                    AND course.containertype = ?
                    AND progass.userid = ?";

        return $DB->record_exists_sql($sql, $params);
    }
}
