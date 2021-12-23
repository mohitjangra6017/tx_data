<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon Adams <simon.adams@kineo.com>
 */

namespace logstore_xapi\userdata;


use context;
use core\orm\query\builder;
use totara_userdata\userdata\export;
use totara_userdata\userdata\item;
use totara_userdata\userdata\target_user;

class failed_log extends item
{
    /**
     * @return array|string[]
     */
    public static function get_fullname_string()
    {
        return ['userdataitem-logstore-xapi-failed-log', 'logstore_xapi'];
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
        return builder::table('logstore_xapi_failed_log')
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
            builder::table('logstore_xapi_failed_log')
                   ->where('userid', '=', $user->id)
                   ->get()
                   ->all();

        return $export;
    }

    /**
     * The purge behaviour has been copied from standard logstore
     * @param target_user $user
     * @param context $context
     * @return int
     */
    protected static function purge(target_user $user, context $context)
    {
        builder::table('logstore_xapi_failed_log')
               ->where('userid', '=', $user->id)
               ->update(['ip' => '', 'other' => '']);

        return item::RESULT_STATUS_SUCCESS;
    }
}