<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

namespace mod_assessment\factory;

use mod_assessment\entity\assessment_version_assignment;
use mod_assessment\entity\assessment_version_assignment_log;
use mod_assessment\model\role;

defined('MOODLE_INTERNAL') || die();

class assessment_version_assignment_factory extends entity_factory
{

    public static function create_clone(assessment_version_assignment $assignment, int $versionid = null): assessment_version_assignment
    {
        $data = $assignment->export_for_database();
        unset($data->id);

        if ($versionid) {
            $data->versionid = $versionid;
        }

        return self::create_from_data($data);
    }

    public static function create_from_data($data): assessment_version_assignment
    {
        if (is_array($data)) {
            $data = (object)$data;
        }

        return new assessment_version_assignment(
            $data->userid,
            new role($data->role),
            $data->learnerid,
            $data->versionid,
            $data->id ?? null
        );
    }

    public static function get_entity(): string
    {
        return assessment_version_assignment::class;
    }

    public static function create_from_log(assessment_version_assignment_log $log)
    {
        if ($log->is_skipped()) {
            return null;
        }

        if ($log->is_confirmed()) {
            return self::fetch(['userid' => $log->get_userid(), 'learnerid' => $log->get_learnerid(), 'versionid' => $log->get_versionid()]);
        }

        return new assessment_version_assignment(
            $log->get_userid(),
            $log->get_role(),
            $log->get_learnerid(),
            $log->get_versionid()
        );
    }

    public static function exists($conditions = []): bool
    {
        global $DB;
        return $DB->record_exists(self::get_entity()::get_tablename(), $conditions);
    }
}
