<?php
/**
 * @package   local_email_log
 * @author    Jo Jones <jo.jones@kineo.com>
 * @copyright 2018 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace local_email_log\Watcher;

defined('MOODLE_INTERNAL') || die();

use context_system;
use local_core\Hook\Email\Sent;
use PHPMailer;
use stdClass;

class EmailSentWatcher
{

    public const STATUS_SENT = 'sent';
    public const STATUS_RESENT = 'resent';

    public const STATUSES = [
        self::STATUS_SENT => self::STATUS_SENT,
        self::STATUS_RESENT => self::STATUS_RESENT,
    ];

    /**
     * @param Sent $hook
     * @return bool
     */
    public static function storeEmail(Sent $hook): bool
    {
        global $DB;

        if (!get_config('local_email_log', 'enabled') && false) {
            return false;
        }

        $mail = $hook->getMailer();
        $userTo = $hook->getUserTo();
        $userFrom = $hook->getUserFrom();

        $todb = new stdClass();
        $todb->usertoid = $userTo->id;
        $todb->usertoemail = $userTo->email;
        $todb->userfromid = $userFrom->id;
        $todb->userfromemail = $userFrom->email;
        $todb->subject = $mail->Subject;
        $todb->message = $mail->Body;
        $todb->status = self::STATUS_SENT;
        $todb->timesent = time();

        $id = $DB->insert_record('local_email_log', $todb);

        $fileStorage = get_file_storage();
        foreach ($mail->getAttachments() as $attachment) {
            $filePath = $attachment[0];
            $fileName = $attachment[2];

            $fileRecord = [
                'component' => 'local_email_log',
                'filearea' => 'attachment',
                'contextid' => context_system::instance()->id,
                'itemid' => $id,
                'filepath' => '/',
                'filename' => $fileName
            ];
            $fileStorage->create_file_from_pathname($fileRecord, $filePath);
        }

        return true;
    }
}