<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

namespace mod_assessment\factory;

use mod_assessment\entity\attempt_completion;
use mod_assessment\model\attempt_completion_status;
use mod_assessment\model\role;

defined('MOODLE_INTERNAL') || die();

class attempt_completion_factory extends entity_factory
{

    public static function create_from_data($data): attempt_completion
    {
        if (is_array($data)) {
            $data = (object)($data);
        }

        return new attempt_completion(
            $data->attemptid,
            new role($data->role),
            new attempt_completion_status($data->status),
            $data->id ?? null
        );
    }

    public static function get_entity(): string
    {
        return attempt_completion::class;
    }
}
