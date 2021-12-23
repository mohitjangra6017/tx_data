<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

namespace mod_assessment\factory;

use mod_assessment\entity\assessment_version_assignment_log;
use mod_assessment\model\import_error;
use mod_assessment\model\role;

defined('MOODLE_INTERNAL') || die();

class assessment_version_assignment_log_factory extends entity_factory
{
    public static function create_from_data($data): assessment_version_assignment_log
    {
        if (is_array($data)) {
            $data = (object)$data;
        }

        return new assessment_version_assignment_log(
            $data->importid,
            $data->csvrow,
            $data->learneridraw,
            $data->useridraw,
            $data->skipped,
            $data->timecreated,
            new role($data->role),
            $data->versionid,
            $data->userid ?? null,
            $data->learnerid ?? null,
            $data->timeconfirmed ?? null,
            new import_error($data->errorcode ?? null),
            $data->id ?? null
        );
    }

    public static function get_entity(): string
    {
        return assessment_version_assignment_log::class;
    }
}
