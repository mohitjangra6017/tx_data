<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon Adams <simon.adams@kineo.com>
 */

namespace format_tiles\userdata;


use context;
use totara_userdata\userdata\export;
use totara_userdata\userdata\item;
use totara_userdata\userdata\target_user;

class user_preferences extends item
{
    /**
     * @return array|string[]
     */
    public static function get_fullname_string(): array
    {
        return ['userdataitem-user-preferences', 'format_tiles'];
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
        $preference = get_user_preferences('format_tiles_stopjsnav', null, $user->id);
        return $preference === null ? 0 : 1;
    }

    /**
     * @param target_user $user
     * @param context $context
     * @return int|export
     */
    protected static function export(target_user $user, context $context)
    {
        $export = new export();
        $preference = get_user_preferences('format_tiles_stopjsnav', null, $user->id);
        $export->data = ['format_tiles_stopjsnav' => ($preference === null ? 'no' : 'yes')];

        return $export;
    }

    /**
     * @param target_user $user
     * @param context $context
     * @return int
     */
    protected static function purge(target_user $user, context $context): int
    {
        unset_user_preference('format_tiles_stopjsnav', $user->id);

        return item::RESULT_STATUS_SUCCESS;
    }
}