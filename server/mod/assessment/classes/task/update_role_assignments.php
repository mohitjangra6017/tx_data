<?php
/**
 * DEVIOTIS2
 */

namespace mod_assessment\task;

defined('MOODLE_INTERNAL') || die();

use core\task\scheduled_task;
use mod_assessment\helper\debug_progress_trace;
use mod_assessment\processor\role_assignments;

class update_role_assignments extends scheduled_task
{

    /**
     * Get a descriptive name for this task (shown to admins).
     *
     * @return string
     */
    public function get_name()
    {
        return get_string('task:updateroleassignments', 'mod_assessment');
    }

    public function execute()
    {
        $trace = new debug_progress_trace();
        role_assignments::update_dirty_role_assignments(null, $trace);
    }
}

