<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

namespace mod_assessment\factory;

defined('MOODLE_INTERNAL') || die();

abstract class entity_factory
{

    abstract public static function create_from_data($data);

    abstract public static function get_entity();

    public static function fetch($params, $strictness = MUST_EXIST)
    {
        global $DB;
        $record = $DB->get_record(static::get_entity()::get_tablename(), $params, '*', $strictness);
        if (!$record) {
            return false;
        }
        return static::create_from_data($record);
    }

    public static function fetch_all($params = [], $sort = 'id'): array
    {
        global $DB;
        $entities = [];

        $records = $DB->get_records(static::get_entity()::get_tablename(), $params, $sort);
        foreach ($records as $record) {
            $entities[$record->id] = static::create_from_data($record);
        }

        return $entities;
    }
}
