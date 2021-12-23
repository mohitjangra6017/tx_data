<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Simon Adams <simon.adams@kineo.com>
 */

namespace local_leaderboard\userdata;


use context;
use core\orm\query\builder;
use totara_userdata\userdata\export;
use totara_userdata\userdata\item;
use totara_userdata\userdata\target_user;

class score extends item
{
    /**
     * @return array|string[]
     */
    public static function get_fullname_string()
    {
        return ['userdataitem-user-score', 'local_leaderboard'];
    }

    /**
     * @inheritDoc
     */
    public static function is_purgeable(int $userstatus)
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public static function is_exportable()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public static function is_countable()
    {
        return true;
    }

    /**
     * @param target_user $user
     * @param context $context
     * @return int
     */
    protected static function count(target_user $user, context $context)
    {
        return builder::table('local_leaderboard_user')
                      ->where('userid', '=', $user->id)
                      ->count();
    }

    /**
     * @param target_user $user
     * @param context $context
     * @return int|export
     */
    protected static function export(target_user $user, context $context)
    {
        $export = new export();
        $export->data =
            builder::table('local_leaderboard_user')
                   ->where('userid', '=', $user->id)
                   ->get()
                   ->all();

        return $export;
    }

    /**
     * @param target_user $user
     * @param context $context
     * @return int
     */
    protected static function purge(target_user $user, context $context)
    {
        builder::table('local_leaderboard_user')
               ->where('userid', '=', $user->id)
               ->delete();

        return item::RESULT_STATUS_SUCCESS;
    }
}