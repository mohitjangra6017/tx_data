<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Simon Adams <simon.adams@kineo.com>
 */

namespace block_rate_course\userdata;


use context;
use core\orm\query\builder;
use dml_transaction_exception;
use Exception;
use Throwable;
use totara_userdata\userdata\export;
use totara_userdata\userdata\item;
use totara_userdata\userdata\target_user;

class review_like extends item {

    /**
     * @return array|string[]
     */
    public static function get_fullname_string() {
        return ['userdataitem-user-review_like', 'block_rate_course'];
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
        return builder::table('rate_course_review_likes')
                      ->where('userid', '=', $user->id)
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
            builder::table('rate_course_review_likes')
                   ->where('userid', '=', $user->id)
                   ->get()
                   ->all();

        return $export;
    }

    /**
     * Purge review likes and alter count in the rate_course_review table
     * @param target_user $user
     * @param context $context
     * @return int
     * @throws Throwable
     * @throws dml_transaction_exception
     */
    protected static function purge(target_user $user, context $context) {
        global $DB;

        $review_likes = builder::table('rate_course_review_likes')
            ->select('reviewid')
            ->where('userid', '=', $user->id)
            ->fetch();

        $trans = $DB->start_delegated_transaction();
        try {
            foreach ($review_likes as $review_like) {
                $review = builder::table('rate_course_review')->find($review_like->reviewid);
                $review->reviewlikes > 0 ? $review->reviewlikes -- : $review->reviewlikes;
                builder::table('rate_course_review')->update_record($review);
            }

            builder::table('rate_course_review_likes')
                   ->where('userid', '=', $user->id)
                   ->delete();
        } catch (Exception $e) {
            $trans->rollback($e);
            return item::RESULT_STATUS_ERROR;
        }

        $trans->allow_commit();

        return item::RESULT_STATUS_SUCCESS;
    }
}