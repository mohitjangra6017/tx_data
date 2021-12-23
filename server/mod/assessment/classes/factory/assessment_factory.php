<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

namespace mod_assessment\factory;

use mod_assessment\model\assessment;

defined('MOODLE_INTERNAL') || die();

class assessment_factory extends entity_factory
{

    public static function create_from_data($data): assessment
    {
        $assessment = new assessment();
        $assessment->set_data($data);
        return $assessment;
    }

    public static function get_entity(): string
    {
        return assessment::class;
    }
}
