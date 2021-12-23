<?php

namespace isotopeprovider_programs\DataSources;

global $CFG;

use dml_exception;

require_once($CFG->dirroot . '/totara/program/lib.php');

class Certification extends Program
{
    /**
     * @return array|mixed
     * @throws dml_exception
     */
    public function getData()
    {
        if (!isset($this->filters['userid'])) {
            throw new \Exception("You must set a userid filter.");
        }
        $certifications = prog_get_certification_programs($this->filters['userid'], '', '', '', false, true, false);
        return $this->categoryCheck($certifications);
    }
}
