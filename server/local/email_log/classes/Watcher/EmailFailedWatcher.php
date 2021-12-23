<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Simon Adams <simon.adams@kineo.com>
 */

namespace local_email_log\Watcher;

defined('MOODLE_INTERNAL') || die();

use core_user;
use local_core\Hook\Email\Failed;
use stdClass;
use totara_job\job_assignment;

class EmailFailedWatcher
{
    /**
     * @var bool Keeps track if we just sent an email about a failed email. If that fails, do not send another one.
     */
    protected static bool $handlingEmail = false;

    /**
     * @var stdClass
     */
    protected static stdClass $config;

    /**
     * Can be called both by an event handler, or by a cleanup task after events are triggered.
     * We make sure there is infinite loop protection so that we don't keep triggering the same failed email multiple times.
     * @param Failed $hook
     */
    public static function handleFailedEmail(Failed $hook): void
    {
        if (static::$handlingEmail) {
            return;
        }

        static::$config = get_config('local_email_log');

        if (!static::$config || empty(static::$config->enabled)) {
            return;
        }

        if (static::$config->notifiedusers == 'manager') {
            static::sendToManager($hook->getUserTo()->id);
        } elseif (static::$config->notifiedusers == 'profilefield') {
            static::sendToAuxEmail($hook->getUserTo()->id);
        }
    }

    /**
     * Sends a failed email to the given user's first manager.
     * @param int $userId
     */
    private static function sendToManager(int $userId): void
    {
        global $DB;

        $managerIds = job_assignment::get_all_manager_userids($userId);
        if (empty($managerIds)) {
            return;
        }

        $managerId = reset($managerIds);
        $userTo = $DB->get_record('user', ['id' => $managerId]);

        $userFrom = core_user::get_noreply_user();
        static::$handlingEmail = true;
        email_to_user($userTo, $userFrom, static::$config->subject, static::$config->body, static::$config->body);
        static::$handlingEmail = false;
    }

    /**
     * Sends a failed email to the given user, if that user has their "auxiliary email address" profile field filled in.
     * @param int $userId
     */
    private static function sendToAuxEmail(int $userId): void
    {
        global $DB, $CFG;

        require_once "{$CFG->dirroot}/user/profile/lib.php";

        $userTo = $DB->get_record('user', ['id' => $userId]);
        profile_load_custom_fields($userTo);

        $auxEmailField = static::$config->profilefield;

        if (empty($userTo->profile[$auxEmailField])) {
            return;
        }

        $auxEmailIsValid = validate_email($userTo->profile[$auxEmailField]) &&
            !email_is_not_allowed($userTo->profile[$auxEmailField]);

        if (!$auxEmailIsValid) {
            return;
        }

        $userTo->email = $userTo->profile[$auxEmailField];
        $userFrom = core_user::get_noreply_user();
        static::$handlingEmail = true;
        email_to_user($userTo, $userFrom, static::$config->subject, static::$config->body, static::$config->body);
        static::$handlingEmail = false;
    }
}
