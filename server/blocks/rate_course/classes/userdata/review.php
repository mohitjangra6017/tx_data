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

class review extends item {

    /**
     * @return array|string[]
     */
    public static function get_fullname_string() {
        return ['userdataitem-user-review', 'block_rate_course'];
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
        return builder::table('rate_course_review')
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
            builder::table('rate_course_review')
                   ->where('userid', '=', $user->id)
                   ->get()
                   ->all();

        return $export;
    }

    /**
     * Purge user review(s) and any likes and comments attached to it/them
     *
     * @param target_user $user
     * @param context $context
     * @return int
     * @throws Throwable
     * @throws dml_transaction_exception
     */
    protected static function purge(target_user $user, context $context) {

        global $DB;

        $reviews = builder::table('rate_course_review')
            ->where('userid', '=', $user->id)
            ->fetch();

        $trans = $DB->start_delegated_transaction();

        try {
            foreach ($reviews as $review) {
                builder::table('rate_course_review_likes')
                       ->where('reviewid', '=', $review->id)
                       ->delete();
                builder::table('rate_course_review_comments')
                       ->where('review_id', '=', $review->id)
                       ->delete();
                builder::table('rate_course_review')
                       ->where('id', '=', $review->id)
                       ->delete();
            }
        } catch (Exception $e) {
            $trans->rollback($e);
            return item::RESULT_STATUS_ERROR;
        }

        $trans->allow_commit();

        return item::RESULT_STATUS_SUCCESS;
    }
}