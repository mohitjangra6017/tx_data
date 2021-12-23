<?php

namespace local_leaderboard\Repository;

use dml_exception;
use moodle_database;
use stdClass;

defined('MOODLE_INTERNAL') || die();

abstract class BaseRepository
{
    /** @var moodle_database */
    protected $db;

    /** @var int|mixed */
    protected $id;

    public function __construct(moodle_database $db, $id = 0)
    {
        $this->db = $db;
        $this->id = $id;
    }

    /**
     * @param stdClass $data
     * @return int
     * @throws dml_exception
     */
    public function create(stdClass $data) : int
    {
        $this->id = $this->db->insert_record($this->table, $data, true);
        return $this->id;
    }

    /**
     * @param $data
     * @throws dml_exception
     */
    public function update($data)
    {
        $this->db->update_record($this->table, $data);
    }

    /**
     * @return false|mixed|stdClass
     * @throws dml_exception
     */
    public function get()
    {
        return $this->db->get_record($this->table, array('id' => $this->id));
    }

    /**
     * @return array
     * @throws dml_exception
     */
    public function fetchAll()
    {
        return $this->db->get_records($this->table, []);
    }

    /**
     * @throws dml_exception
     */
    public function delete()
    {
        $this->db->delete_records($this->table, array('id' => $this->id));
    }
}
