<?php
/**
 * @copyright 2016 Kineo
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package Totara
 * @subpackage KineoUS Library
 */

namespace mod_assessment\entity;

use coding_exception;
use moodle_database;
use stdClass;

abstract class SimpleDBO
{

    /** @var int */
    public int $id = 0;

    /** @var int */
    public int $timecreated;

    /** @var int */
    public int $timemodified;

    public function __construct()
    {
        // Validate child.
        if (empty(static::TABLE)) {
            throw new coding_exception('Database TABLE must be defined!');
        }
    }

    /**
     * Completely remove the object from the db
     *
     * @return bool
     * @global moodle_database $DB
     */
    public function delete(): bool
    {
        global $DB;
        return $DB->delete_records(static::TABLE, ['id' => $this->id]);
    }

    /**
     * Return the current record from the database
     *
     * @return stdClass
     * @global moodle_database $DB
     */
    public function get_current_record(): stdClass
    {
        global $DB;
        return $DB->get_record(static::TABLE, ['id' => $this->id]);
    }

    /**
     * Get an instance of the object
     *
     * @param array $conditions
     * @param int $strictness
     * @return static | null
     * @global moodle_database $DB
     */
    public static function instance(array $conditions, $strictness = IGNORE_MISSING): ?SimpleDBO
    {
        global $DB;
        if (!$data = $DB->get_record(static::TABLE, $conditions, '*', $strictness)) {
            return null;
        }

        $object = new static();
        $object->set_data($data);
        return $object;
    }

    /**
     * Get array of object instances, indexed by record id
     *
     * @param array $conditions
     * @return static[]
     * @global moodle_database $DB
     */
    public static function instances(array $conditions): array
    {
        global $DB;
        $instances = [];
        $records = $DB->get_records(static::TABLE, $conditions);
        foreach ($records as $data) {
            $object = new static();
            $object->set_data($data);
            $instances[$object->id] = $object;
        }
        return $instances;
    }

    /**
     * Return an object model. If no record found matching conditions, will return a blank object pre-populated with conditions
     *
     * @param array $parameters
     * @return static | null
     */
    public static function make(array $parameters): ?SimpleDBO
    {
        $model = static::instance($parameters);
        if (!$model) {
            $model = new static();
            $model->set_data($parameters);
        }
        return $model;
    }

    /**
     * Postprocess save
     *
     * @param bool $isupdate
     */
    protected function postsave($isupdate = false)
    {
    }

    /**
     * Preprocess save
     *
     * @param bool $isupdate
     */
    protected function presave(&$isupdate = false)
    {
    }

    /**
     * Save the object to the db
     *
     * @return self
     * @global moodle_database $DB
     */
    public function save(): SimpleDBO
    {
        global $DB;

        $isupdate = !empty($this->id);

        $this->timemodified = time();
        $this->presave($isupdate);

        if (empty($this->id)) {
            $this->timecreated = time();
            $this->id = $DB->insert_record(static::TABLE, $this);
        } else {
            $DB->update_record(static::TABLE, $this);
        }

        $this->postsave($isupdate);
        return $this;
    }

    /**
     * Set parameters in the object
     *
     * @param $data
     * @param bool $usesetter Flags data to use model setters
     * @return self
     */
    public function set_data($data, $usesetter = false): SimpleDBO
    {
        foreach ($data as $field => $value) {
            $function = "set_{$field}";
            if ($usesetter && method_exists($this, $function) && !is_null($value)) {
                $this->$function($value);
            } else {
                $this->$field = $value;
            }
        }
        return $this;
    }
}
