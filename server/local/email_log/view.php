<?php
/**
 * Ajax call to view an email
 *
 * @package    local_email_log
 * @copyright  &copy; 2021 Kineo Pty Ltd  {@link http://kineo.com/au}
 * @author     tri.le
 * @version    1.0
*/

define('AJAX_SCRIPT', true);
require_once ('../../config.php');
require_once ('../../totara/reportbuilder/lib.php');
global $DB;

$id = required_param('id', PARAM_INT);

require_login();
$report = reportbuilder::create_embedded('email_log');
$email = $DB->get_record('local_email_log', ['id' => $id], '*', MUST_EXIST);

echo json_encode([
    'header' => $email->subject,
    'content'=> $email->message,
]);