<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

namespace mod_assessment\factory;

use mod_assessment\model\version;

class assessment_version_factory extends entity_factory
{

    public static function get_entity(): string
    {
        return version::class;
    }

    public static function create_from_data($data): ?version
    {
        if (is_array($data)) {
            $data = (object)$data;
        }

        return version::make((array)$data);
    }
}
