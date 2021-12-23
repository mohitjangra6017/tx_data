<?php
/**
 * @package   local_email_log
 * @author    Jo Jones <jo.jones@kineo.com>
 * @copyright 2018 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

use core\notification;
use local_email_log\Form\Resend;
use local_email_log\Watcher\EmailSentWatcher;
use mod_facetoface\facetoface_user;

global $CFG, $DB, $OUTPUT, $PAGE, $USER;

require_once(dirname(dirname(__DIR__)) . '/config.php');

require_login();

$id = required_param('id', PARAM_INT);
$email = $DB->get_record('local_email_log', ['id' => $id], '*', MUST_EXIST);

$context = context_user::instance($email->usertoid);
require_capability('local/email_log:access', $context, $USER->id);

// Page definition.
$url = new moodle_url('/local/email_log/resend.php', ['id' => $id]);

$title = get_string('page:title', 'local_email_log');
$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());
$PAGE->set_title($title);

$params = [
    'id' => $id,
    'email' => $email->usertoemail,
];

$form = new Resend($url, $params, 'post');
if ($form->is_cancelled()) {
    redirect(new moodle_url('/local/email_log'));
} elseif ($form->is_submitted()) {
    $formData = $form->get_data();

    $userTo = $DB->get_record('user', ['id' => $email->usertoid]);
    // Using F2F get_user as will fallback to core if not F2F.
    $userFrom = facetoface_user::get_user($email->userfromid);
    $userTo->email = $formData->email;

    $fileStorage = get_file_storage();
    if ($files = $fileStorage->get_area_files(
        context_system::instance()->id,
        'local_email_log',
        'attachment',
        $email->id,
        'itemid',
        false
    )) {
        $attachment = reset($files);
        $fileName = $attachment->get_filename();
        $filePath = $attachment->copy_content_to_temp('email_log', time());
    } else {
        $fileName = $filePath = '';
    }

    if (email_to_user($userTo, $userFrom, $email->subject, $email->message, '', $filePath, $fileName)) {
        $email->status = EmailSentWatcher::STATUS_RESENT;
        $DB->update_record('local_email_log', $email);

        notification::success(get_string('messageresentsuccess', 'local_email_log'));
    } else {
        notification::error(get_string('messageresentfail', 'local_email_log'));
    }

    if (!empty($filePath)) {
        @unlink($filePath);
    }

    redirect(new moodle_url('/local/email_log'));
}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('resendtitle', 'local_email_log'));

echo html_writer::tag('p', get_string('resendbody', 'local_email_log'));

$form->display();

echo $OUTPUT->footer();
