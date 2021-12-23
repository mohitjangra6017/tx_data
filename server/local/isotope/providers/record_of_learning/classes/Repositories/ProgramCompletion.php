<?php

namespace isotopeprovider_record_of_learning\Repositories;

use dml_exception;
use moodle_database;
use stdClass;

class ProgramCompletion
{
    /** @var moodle_database */
    private $db;

    public function __construct(moodle_database $db)
    {
        $this->db = $db;
    }

    /**
     * Get the completion record for the specified program and user.
     * @param int $programId
     * @param int $userId
     * @return false|mixed|stdClass
     * @throws dml_exception
     */
    public function get(int $programId, int $userId)
    {
        return $this->db->get_record(
            'prog_completion',
            [
                'programid' => $programId,
                'userid' => $userId,
                'coursesetid' => 0,
            ]
        );
    }
}