<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon Adams <simon.adams@kineo.com>
 */

namespace mod_assessment\userdata;


use context;
use core\orm\query\builder;
use Exception;
use totara_userdata\userdata\export;
use totara_userdata\userdata\item;
use totara_userdata\userdata\target_user;

class version_assignment extends item
{
    /**
     * @return array|string[]
     */
    public static function get_fullname_string(): array
    {
        return ['userdataitem-user-version-assignment', 'mod_assessment'];
    }

    /**
     * @inheritDoc
     */
    public static function is_purgeable(int $userstatus): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public static function is_exportable(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public static function is_countable(): bool
    {
        return true;
    }

    /**
     * @param target_user $user
     * @param context $context
     * @return int
     */
    protected static function count(target_user $user, context $context): int
    {
        return builder::table('assessment_version_assignment')
                      ->where('learnerid', '=', $user->id)
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
        $data = [];

        $data['assignments'] = builder::table('assessment_version_assignment')
                              ->where('learnerid', '=', $user->id)
                              ->get();

        $data['logs'] = builder::table('assessment_version_assignment_log')
                       ->where('learnerid', '=', $user->id)
                       ->get();

        $export->data = $data;

        return $export;
    }

    /**
     * @param target_user $user
     * @param context $context
     * @return int
     */
    protected static function purge(target_user $user, context $context): int
    {
        global $DB;

        $trans = $DB->start_delegated_transaction();

        try {
            builder::table('assessment_version_assignment')
                   ->where('learnerid', '=', $user->id)
                   ->delete();

            builder::table('assessment_version_assignment_log')
                   ->where('learnerid', '=', $user->id)
                   ->delete();
        } catch (Exception $e) {
            $trans->rollback();
            return item::RESULT_STATUS_ERROR;
        }

        $trans->allow_commit();

        return item::RESULT_STATUS_SUCCESS;
    }
}