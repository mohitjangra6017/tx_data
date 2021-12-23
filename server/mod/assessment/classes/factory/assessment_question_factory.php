<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

namespace mod_assessment\factory;

use mod_assessment\model\question;

class assessment_question_factory extends entity_factory
{

    public static function get_entity(): string
    {
        return question::class;
    }

    public static function create_from_data($data): question
    {
        if (is_array($data)) {
            $data = (object)$data;
        }

        $question = question::class_from_type($data->type);
        $question->set_data($data);
        return $question;
    }

    public static function fetch_from_parentid(int $parentid, int $versionid): array
    {
        global $DB;
        $sql = "SELECT question.*, vq.sortorder
                  FROM {assessment_question} question
                  JOIN {assessment_version_question} vq ON vq.questionid = question.id
                 WHERE vq.parentid = :parentid AND vq.versionid = :versionid
              ORDER BY vq.sortorder";
        $records = $DB->get_records_sql($sql, ['parentid' => $parentid, 'versionid' => $versionid]);

        $entities = [];
        foreach ($records as $record) {
            $entities[$record->id] = self::create_from_data($record);
        }
        return $entities;
    }

    public static function fetch_parent(int $questionid, int $versionid): ?question
    {
        global $DB;
        $sql = "SELECT question.*
                  FROM {assessment_question} question
                  JOIN {assessment_version_question} vq ON vq.parentid = question.id
                 WHERE vq.questionid = :questionid AND vq.versionid = :versionid";
        $params = ['questionid' => $questionid, 'versionid' => $versionid];
        $record = $DB->get_record_sql($sql, $params);
        if (!$record) {
            return null;
        }

        return static::create_from_data($record);
    }
}
