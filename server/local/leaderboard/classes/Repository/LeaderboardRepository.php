<?php

namespace local_leaderboard\Repository;

use dml_exception;
use stdClass;

defined('MOODLE_INTERNAL') || die();

class LeaderboardRepository extends BaseRepository
{
    protected $table = 'local_leaderboard';

    /**
     * @param stdClass $data
     * @return int
     * @throws dml_exception
     */
    public function create(stdClass $data): int
    {
        if ($existing = $this->getDeletedByEventName($data->eventname)) {
            $data->id = $existing->id;
            $data->deleted = 0;
            $this->db->update_record($this->table, $data);
            $this->id = $data->id;
        } else {
            $this->id = $this->db->insert_record($this->table, $data, true);
        }

        return $this->id;
    }

    /**
     * @param string $eventName
     * @return false|mixed|stdClass
     * @throws dml_exception
     */
    protected function getDeletedByEventName(string $eventName)
    {
        return $this->db->get_record($this->table, ['eventname' => $eventName]);
    }

    /**
     * @return bool
     * @throws dml_exception
     */
    public function softDelete(): bool
    {
        return $this->db->set_field($this->table, 'deleted', 1, ['id' => $this->id]);
    }

    /**
     * @return array
     * @throws dml_exception
     */
    public function fetchAll(): array
    {
        return $this->db->get_records($this->table, ['deleted' => 0]);
    }
}
