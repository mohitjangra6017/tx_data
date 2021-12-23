<?php

namespace block_course_recommendations\userdata;

use context;
use totara_userdata\userdata\export;
use totara_userdata\userdata\item;
use totara_userdata\userdata\target_user;

defined('MOODLE_INTERNAL') || die();

/**
 * Handles course recommendation ignore user data.
 */
class block_course_recommendations_ignore extends item
{

    /**
     * @return array parameters of get_string($identifier, $component) to get full item name and optionally help.
     */
    public static function get_fullname_string()
    {
        return ['block_course_recommendations_ignore', 'block_course_recommendations'];
    }

    /**
     * @param int $userstatus target_user::STATUS_ACTIVE, target_user::STATUS_DELETED or target_user::STATUS_SUSPENDED
     * @return bool
     */
    public static function is_purgeable(int $userstatus)
    {
        return true;
    }

    /**
     * @param target_user $user
     * @param context $context restriction for purging e.g., system context for everything, course context for purging one course
     * @return int result self::RESULT_STATUS_SUCCESS, self::RESULT_STATUS_ERROR or self::RESULT_STATUS_SKIPPED
     */
    protected static function purge(target_user $user, context $context)
    {
        global $DB;

        $records = $DB->get_records('block_course_recommendations_ignore', ['userid' => $user->id]);

        $active = ($user->status == target_user::STATUS_ACTIVE);

        if ($active) {
            $transaction = $DB->start_delegated_transaction();
        }

        foreach ($records as $record) {
            $DB->delete_records('block_course_recommendations_ignore', ['id' => $record->id]);
        }

        if ($active) {
            $transaction->allow_commit();
        }

        return self::RESULT_STATUS_SUCCESS;
    }

    /**
     * @return array
     */
    public static function get_compatible_context_levels()
    {
        return [CONTEXT_SYSTEM, CONTEXT_COURSECAT, CONTEXT_COURSE];
    }
    
    /**
     * @return bool
     */
    public static function is_exportable()
    {
        return true;
    }

    /**
     * @param target_user $user
     * @param context $context restriction for exporting i.e., system context for everything and course context for course export
     * @return export|int result object or integer error code self::RESULT_STATUS_ERROR or self::RESULT_STATUS_SKIPPED
     */
    protected static function export(target_user $user, context $context)
    {
        global $DB;

        $records = $DB->get_records('block_course_recommendations_ignore', ['userid' => $user->id]);

        $export = new export();

        foreach ($records as $record) {
            $record = (array) $record;

            // Remove any fields we don't want to export.
            unset($record['id']);

            $export->data[] = $record;
        }

        return $export;
    }

    /**
     * @return bool
     */
    public static function is_countable()
    {
        return true;
    }

    /**
     * @param target_user $user
     * @param context $context restriction for counting i.e., system context for everything and course context for course data
     * @return int amount of data or negative integer status code (self::RESULT_STATUS_ERROR or self::RESULT_STATUS_SKIPPED)
     */
    protected static function count(target_user $user, context $context)
    {
        global $DB;

        return $DB->count_records('block_course_recommendations_ignore', ['userid' => $user->id]);
    }
}