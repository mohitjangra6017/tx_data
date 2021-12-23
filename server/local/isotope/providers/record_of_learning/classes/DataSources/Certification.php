<?php

namespace isotopeprovider_record_of_learning\DataSources;

use dml_exception;

class Certification extends Program
{
    /**
     * @return array
     * @throws dml_exception
     */
    public function getData(): array
    {
        if (!isset($this->filters['userid'])) {
            throw new \Exception("You must set a userid filter.");
        }
        $certifications =
            prog_get_certification_programs(
                $this->filters['userid'],
                'ORDER BY duedate ASC',
                '',
                '',
                false,
                true,
                false
            );
        return $this->categoryCheck($certifications);
    }
}
