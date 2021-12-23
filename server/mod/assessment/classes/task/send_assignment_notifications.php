<?php
/**
 * DEVIOTIS2
 *
 * Any evaluators who have yet to be notified will be sent notification emails informing them of their role assignment.
 */

namespace mod_assessment\task;

defined('MOODLE_INTERNAL') || die();

use core\task\scheduled_task;
use mod_assessment\helper\debug_progress_trace;
use mod_assessment\processor\role_assignments;

class send_assignment_notifications extends scheduled_task
{

    /**
     * Get a descriptive name for this task (shown to admins).
     *
     * @return string
     */
    public function get_name(): string
    {
        return get_string('task:sendassignmentnotifications', 'mod_assessment');
    }

    public function execute()
    {
        $trace = new debug_progress_trace();
        role_assignments::notify_unnotified_evaluators($trace);
    }
}

