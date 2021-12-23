<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon Adams <simon.adams@kineo.com>
 */

namespace local_credly\userdata;

use core\orm\query\builder;
use local_credly\entity\BadgeIssue;
use local_credly\entity\BadgeIssueLog;
use totara_userdata\userdata\export;
use totara_userdata\userdata\item;
use totara_userdata\userdata\target_user;

class Issue extends item
{
    /**
     * @return array|string[]
     */
    public static function get_fullname_string()
    {
        return ['userdataitem-issue', 'local_credly'];
    }

    public static function is_countable()
    {
        return true;
    }

    protected static function count(target_user $user, \context $context)
    {
        return builder::table(BadgeIssue::TABLE, 'bi')
            ->left_join([BadgeIssueLog::TABLE, 'bil'], 'bil.badgeissueid', '=', 'bi.id')
            ->where('bi.userid', $user->id)
            ->count();
    }

    public static function is_exportable()
    {
        return true;
    }

    protected static function export(target_user $user, \context $context)
    {
        $export = new export();
        $export->data = builder::table(BadgeIssue::TABLE, 'bi')
                               ->select(['bi.*', 'bil.*'])
                               ->left_join([BadgeIssueLog::TABLE, 'bil'], 'bil.badgeissueid', '=', 'bi.id')
                               ->where('bi.userid', $user->id)
                               ->get()
                               ->all();
        return $export;
    }


    public static function is_purgeable(int $userstatus)
    {
        return true;
    }

    protected static function purge(target_user $user, \context $context)
    {
        global $DB;

        $badgeIssueIds = builder::table(BadgeIssue::TABLE)
                                ->select('id')
                                ->where('userid', $user->id)
                                ->map_to(function ($item) {
                                    return $item->id;
                                })
                                ->fetch();

        $trans = $DB->start_delegated_transaction();

        try {
            builder::table(BadgeIssueLog::TABLE)
                   ->where_in('badgeissueid', $badgeIssueIds)
                   ->delete();

            builder::table(BadgeIssue::TABLE)
                ->where('userid', $user->id)
                ->delete();

            $trans->allow_commit();
            return item::RESULT_STATUS_SUCCESS;

        } catch (\Throwable $t) {
            $trans->rollback($t);
            return item::RESULT_STATUS_ERROR;
        }
    }
}