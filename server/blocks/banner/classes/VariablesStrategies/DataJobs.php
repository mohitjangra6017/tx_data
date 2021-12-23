<?php

namespace block_banner\VariablesStrategies;

use dml_exception;
use dml_missing_record_exception;
use totara_job\job_assignment;


defined('MOODLE_INTERNAL') || die;

class DataJobs extends Data
{

    /**
     * @return string
     * @throws dml_exception
     */
    protected function getUserPos(): string
    {
        global $DB;

        $assignment = $this->loadJobAssignment();
        if (!isset($assignment)) {
            return '';
        }
        $positionid = $assignment->positionid;
        if (empty($positionid)) {
            return '';
        }

        return $DB->get_field('pos', 'fullname', ['id' => $positionid]);
    }

    /**
     * @return string
     * @throws dml_exception
     */
    protected function getUserOrg(): string
    {
        global $DB;

        $assignment = $this->loadJobAssignment();
        if (!isset($assignment)) {
            return '';
        }
        $organisationid = $assignment->organisationid;
        if (empty($organisationid)) {
            return '';
        }

        return $DB->get_field('org', 'fullname', ['id' => $organisationid]);
    }

    /**
     * @return string
     * @throws dml_exception
     */
    protected function getManagerName(): string
    {
        global $DB;

        $assignment = $this->loadJobAssignment();
        if (!isset($assignment)) {
            return '';
        }
        $managerId = $assignment->managerid;
        if (empty($managerId)) {
            return '';
        }

        $manager = $DB->get_record('user', ['id' => $managerId]);
        if (empty($manager)) {
            return '';
        }

        return fullname($manager);
    }

    /**
     * @return job_assignment|null
     * @throws dml_missing_record_exception
     */
    protected function loadJobAssignment(): ?job_assignment
    {
        if (is_null($this->assignment)) {
            $this->assignment = job_assignment::get_first($this->user->id, false);
        }

        return $this->assignment;
    }
}