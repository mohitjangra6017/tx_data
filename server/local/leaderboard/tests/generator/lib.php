<?php
/**
 * Test Generator library.
 *
 * @package   local_score
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2016 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

defined('MOODLE_INTERNAL') || die;

class local_leaderboard_generator extends component_generator_base
{

    /**
     * Setup a score event
     *
     * @param $record
     * @return mixed
     * @throws dml_exception
     */
    public function createScore($record)
    {
        global $DB;

        $record = (array) $record;

        $score = new stdClass();

        $score->plugin = isset($record['plugin']) ? $record['plugin'] : 'core';
        $score->eventname = isset($record['eventname']) ? $record['eventname'] : '';
        $score->score = isset($record['score']) ? $record['score'] : null;
        $score->frequency = isset($record['frequency']) ? $record['frequency'] : null;
        $score->usegrade = isset($record['usegrade']) ? $record['usegrade'] : 0;
        $score->timemodified = isset($record['timemodified']) ? $record['timemodified'] : time();
        $score->id = $DB->insert_record('local_leaderboard', $score);

        return $DB->get_record('local_leaderboard', ['id' => $score->id]);
    }

    /**
     * Setup A course multiplier for course custom fields to use
     *
     * @return bool|int
     * @throws dml_exception
     */
    public function createCourseMultiplier()
    {
        global $DB;

        $field = new stdClass();
        $field->fullname = 'multiplier';
        $field->shortname = 'multiplier';
        $field->datatype = 'text';
        $field->description = 'mulitplier field';
        $field->sortorder = 1;
        $field->hidden = 0;
        $field->locked = 0;
        $field->required = 0;
        $field->forceunique = 0;
        $field->defaultdata = 1;
        $field->param1 = 30;
        $field->param2 = 2048;

        $fieldId = $DB->insert_record('course_info_field', $field);

        set_config('coursemultiplierfieldid', $fieldId, 'local_leaderboard');

        return $fieldId;
    }
}