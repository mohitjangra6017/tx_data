<?php

/**
 * Observer for rate course block
 *
 * @package    block
 * @subpackage rate_course
 * @copyright  &copy; 2016 Kineo Pacific {@link http://kineo.com.au}
 * @author     tri.le
 * @version    1.0
 */

namespace block_rate_course;

use core\event\course_deleted;
use core\event\user_deleted;
use dml_transaction_exception;
use Exception;
use Throwable;

class observer {
    /**
     * @param course_deleted $event
     * @throws Throwable
     * @throws dml_transaction_exception
     */
    public static function course_deleted(course_deleted $event) {
        global $DB;

        $trans = $DB->start_delegated_transaction();

        try {
            if ($reviewids = $DB->get_fieldset_select('rate_course_review', 'id','course = :course', ['course' => $event->objectid])) {
                [$insql, $inparams] = $DB->get_in_or_equal($reviewids);
                $DB->delete_records_select('rate_course_review_comments', "review_id {$insql}", $inparams);
                $DB->delete_records_select('rate_course_review_likes', "reviewid {$insql}", $inparams);
            }
            $DB->delete_records('rate_course_review', ['course' => $event->objectid]);
            $DB->delete_records('rate_course_course_likes', ['course' => $event->objectid]);
            $DB->delete_records('rate_course_recommendations', ['course' => $event->objectid]);
        } catch (Exception $e) {
            $trans->rollback($e);
        }

        $trans->allow_commit();
    }
}
