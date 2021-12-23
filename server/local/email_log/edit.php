<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Simon Adams <simon.adams@kineo.com>
 */

namespace local_email_log;

use context_system;
use core\output\notification;
use moodle_url;

require_once('../../config.php');

global $PAGE, $OUTPUT;

$context = context_system::instance();
require_login();
require_capability('moodle/site:config', $context);
$baseUrl = new moodle_url('/local/email_log/edit.php');

$title = get_string('pluginname', 'local_email_log');
$PAGE->set_context($context);
$PAGE->set_url($baseUrl);
$PAGE->set_title($title);

$form = new Form\Config();
$form->set_data(get_config('local_email_log'));

if ($form->is_cancelled()) {
    redirect($baseUrl, get_string('settings:not_saved', 'local_email_log'), notification::NOTIFY_SUCCESS);
} elseif ($form->is_submitted() && $form->is_validated()) {
    $form->process_data();
    redirect($baseUrl, get_string('settings:saved', 'local_email_log'), notification::NOTIFY_SUCCESS);
}

echo $OUTPUT->header();
echo $OUTPUT->heading($title, 2);
$form->display();
echo $OUTPUT->footer();
