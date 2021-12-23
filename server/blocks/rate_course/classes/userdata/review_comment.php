<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Simon Adams <simon.adams@kineo.com>
 */

namespace block_rate_course\userdata;


use context;
use core\orm\query\builder;
use Exception;
use Throwable;
use totara_userdata\userdata\export;
use totara_userdata\userdata\item;
use totara_userdata\userdata\target_user;

class review_comment extends item
{
    /**
     * @return array|string[]
     */
    public static function get_fullname_string() {
        return ['userdataitem-user-review_comment', 'block_rate_course'];
    }

    /**
     * @inheritDoc
     */
    public static function is_purgeable(int $userstatus) {
        return true;
    }

    /**
     * @inheritDoc
     */
    public static function is_exportable() {
        return true;
    }

    /**
     * @inheritDoc
     */
    public static function is_countable() {
        return true;
    }

    /**
     * @param target_user $user
     * @param context $context
     * @return int
     */
    protected static function count(target_user $user, context $context) {
        return builder::table('rate_course_review_comments')
                      ->where('user_id', '=', $user->id)
                      ->count();
    }

    /**
     * @param target_user $user
     * @param context $context
     * @return int|export
     */
    protected static function export(target_user $user, context $context) {
        $export = new export();
        $export->data =
            builder::table('rate_course_review_comments')
                   ->where('user_id', '=', $user->id)
                   ->get()
                   ->all();

        return $export;
    }

    /**
     * @param target_user $user
     * @param context $context
     * @return int
     * @throws Throwable
     */
    protected static function purge(target_user $user, context $context) {

        try {
            builder::table('rate_course_review_comments')
                   ->where('user_id', '=', $user->id)
                   ->delete();
        } catch (Exception $e) {
            return item::RESULT_STATUS_ERROR;
        }

        return item::RESULT_STATUS_SUCCESS;
    }
}