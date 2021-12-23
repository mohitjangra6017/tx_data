<?php

use core\entity\user;
use core\orm\query\builder;
use core\tenant_orm_helper;
use local_leaderboard\Utils;

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->dirroot . '/user/filters/lib.php');

if (!defined('MAX_BULK_USERS')) {
    define('MAX_BULK_USERS', 2000);
}

/**
 * @param user_filtering $ufiltering
 * @throws dml_exception
 */
function addScoresSelectionAll(\user_filtering $ufiltering)
{
    global $SESSION, $DB, $CFG;

    [$sqlwhere, $params] = $ufiltering->get_sql_filter("id<>:exguest AND deleted <> 1", ['exguest' => $CFG->siteguest]);

    $rs = $DB->get_recordset_select('user', $sqlwhere, $params, 'fullname', 'id,' . $DB->sql_fullname() . ' AS fullname');
    foreach ($rs as $user) {
        if (!isset($SESSION->bulk_user_scores[$user->id])) {
            $SESSION->bulk_user_scores[$user->id] = $user->id;
        }
    }
    $rs->close();
}

/**
 * Given count and time interval, return readable time interval
 * @param $count
 * @param $interval
 * @return string
 */
function formatReadableTimeInterval($count, $interval)
{
    if ($count === 0) {
        return '';
    }
    $time = Utils::FREQUENCY_OPTIONS[$interval];
    // Remove plurality
    if ($count === 1) {
        $time = substr($time, 0, -1);
    }

    return $count . ' ' . $time;
}

/**
 * @param user_filtering $ufiltering
 * @return array
 * @throws coding_exception
 * @throws dml_exception
 */
function getScoresSelectionData(\user_filtering $ufiltering)
{
    global $SESSION, $DB, $USER;

    [$filterSql, $filterParams] = $ufiltering->get_sql_filter();

    $repo =
        user::repository()
            ->as('auser')
            ->filter_by_not_guest()
            ->filter_by_not_deleted()
            ->select_full_name_fields()
            ->order_by_full_name()
            ->limit(MAX_BULK_USERS);

    tenant_orm_helper::restrict_users(
        $repo,
        "auser.id",
        context_user::instance($USER->id)
    );

    // Count the total users before we apply any filter the user has added.
    $total = $repo->count();
    $scount = count($SESSION->bulk_user_scores);
    $repo
        ->where_raw($filterSql, $filterParams)
        ->where_not_in('id', $SESSION->bulk_user_scores ?? []);
    $acount = $repo->count();
    $ausers = $repo
        ->get()
        ->map(
            function ($user) {
                return $user->fullname;
            }
        )
        ->all(true);

    if ($scount) {
        if ($scount < MAX_BULK_USERS) {
            $bulkusers = $SESSION->bulk_user_scores;
        } else {
            $bulkusers = array_slice($SESSION->bulk_user_scores, 0, MAX_BULK_USERS, true);
        }
        [$in, $inparams] = $DB->get_in_or_equal($bulkusers);
        $susers =
            $DB->get_records_select_menu(
                'user',
                "id $in",
                $inparams,
                'fullname',
                'id,' . $DB->sql_fullname() . ' AS fullname'
            );
    }

    return [
        'acount' => $acount,
        'ausers' => $ausers,
        'scount' => $scount,
        'susers' => $susers ?? false,
        'total' => $total,
    ];
}

/**
 * @param int  $count
 * @param int $interval
 * @return float|int (int) Time in seconds
 */
function calculateFrequency(int $count, int $interval)
{
    switch ($interval) {
        case Utils::FREQUENCY_INTERVAL_WEEKS:
            return $count * WEEKSECS;
        case Utils::FREQUENCY_INTERVAL_DAYS:
            return $count * DAYSECS;
        case Utils::FREQUENCY_INTERVAL_HOURS:
            return $count * HOURSECS;
        case Utils::FREQUENCY_INTERVAL_MINS:
            return $count * MINSECS;
        case Utils::FREQUENCY_INTERVAL_SECS:
        default:
            return $count;
    }
}

/**
 * @param int $frequency
 * @return array
 */
function formatFrequency(int $frequency): array
{
    if ($frequency >= WEEKSECS && $frequency % WEEKSECS == 0) {
        $count = $frequency / WEEKSECS;
        $interval = Utils::FREQUENCY_INTERVAL_WEEKS;
    } else if ($frequency >= DAYSECS && $frequency % DAYSECS == 0) {
        $count = $frequency / DAYSECS;
        $interval = Utils::FREQUENCY_INTERVAL_DAYS;
    } else if ($frequency >= HOURSECS && $frequency % HOURSECS == 0) {
        $count = $frequency / HOURSECS;
        $interval = Utils::FREQUENCY_INTERVAL_HOURS;
    } else if ($frequency >= MINSECS && $frequency % MINSECS == 0) {
        $count = $frequency / MINSECS;
        $interval = Utils::FREQUENCY_INTERVAL_MINS;
    } else {
        $count = $frequency;
        $interval = Utils::FREQUENCY_INTERVAL_SECS;
    }

    return [$count, $interval];
}

/**
 * @return array
 * @throws coding_exception
 * @throws dml_exception
 */
function getFormCustomData(): array
{
    global $DB;

    $chooseDots = [0 => get_string('choosedots', 'core')];

    $courseFields = $DB->get_records_select_menu(
        'course_info_field',
        'datatype = \'menu\' OR datatype = \'text\'',
        [],
        '',
        'id,fullname'
    );

    $courseOptions = $chooseDots + $courseFields;

    $progFields = $DB->get_records_select_menu(
        'prog_info_field',
        'datatype = \'menu\' OR datatype = \'text\'',
        [],
        '',
        'id,fullname'
    );
    $progOptions = $chooseDots + $progFields;

    $userFields =
        $DB->get_records_select_menu('user_info_field', 'datatype = \'checkbox\'', [], 'name ASC', 'id, name');
    $userOptions = $chooseDots + $userFields;

    return [
        'courseOptions' => $courseOptions,
        'progOptions' => $progOptions,
        'userOptions' => $userOptions,
    ];
}
