<?php
/**
 * DEVIOTIS2
 *
 * @author Alex Glover <alex.glover@kineo.com>
 * @package mod_assessment
 *
 * Created: 2019
 */
if (php_sapi_name() == "cli") {
    define('CLI_SCRIPT', true);
}

use mod_assessment\task\update_role_assignments;

require_once dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php';

global $OUTPUT, $PAGE;
require_once dirname(dirname(dirname(__FILE__))) . '/classes/task/update_role_assignments.php';

core_php_time_limit::raise(60 * 60); // 1 hr should be plenty
raise_memory_limit(MEMORY_EXTRA);

if (!CLI_SCRIPT) {
    require_login();

    if (!is_siteadmin()) {
        print_error('accessdenied', 'admin');
        return;
    }

    $context = context_system::instance();

    $PAGE->set_context($context);
    $PAGE->set_url('/mod/assessment/admin/tasks/update_role_assignments.php');
    $PAGE->set_pagelayout('noblocks');
    $confirm = optional_param('confirm', false, PARAM_BOOL);

    $strheading = "Run update_role_assignments task";

    $PAGE->navbar->add($strheading);

    $PAGE->set_title($strheading);
    $PAGE->set_heading($strheading);

    echo $OUTPUT->header();
    echo $OUTPUT->heading($strheading, 1);

    //echo html_writer::div($msg);
    echo html_writer::empty_tag('br');
    echo html_writer::tag('p', 'This task is normally run by cron every 10 mins.');

    if ($confirm) {
        echo "<pre>";
        $task = new update_role_assignments();
        $task->execute();
        echo "</pre>";
    }

    $url = new moodle_url($PAGE->url, array('confirm' => 1));

    echo html_writer::link($url, 'Run task', array('class' => 'link-as-button'));

    echo $OUTPUT->footer();
} else {
    $task = new update_role_assignments();
    $task->execute();
}
