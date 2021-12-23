<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

namespace mod_assessment\entity;

use Exception;
use stdClass;

abstract class base
{

    public function exists(): bool
    {
        return !empty($this->id);
    }

    public function delete()
    {
        global $DB;

        if (!$this->exists()) {
            throw new Exception('Cannot delete record that does not exist');
        }

        $DB->delete_records(static::get_tablename(), ['id' => $this->id]);
    }

    public function save()
    {
        global $DB;

        if ($this->exists()) {
            $DB->update_record(static::get_tablename(), $this->export_for_database());
        } else {
            $this->id = $DB->insert_record(static::get_tablename(), $this->export_for_database());
        }
    }

    abstract public static function get_tablename();

    abstract public function export_for_database(): stdClass;

}
