<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

namespace mod_assessment\event;

use context_module;
use core\event\base;
use mod_assessment\model\attempt;

defined('MOODLE_INTERNAL') || die();

class attempt_created extends base
{

    public static function create_from_attempt(attempt $attempt, context_module $context): base
    {
        $data = [
            'context' => $context,
            'objectid' => $attempt->get_id(),
            'relateduserid' => $attempt->get_userid(),
            'other' => [
                'versionid' => $attempt->get_versionid(),
            ],
        ];
        return self::create($data);
    }

    protected function init()
    {
        $this->data['crud'] = 'c';
        $this->data['edulevel'] = self::LEVEL_PARTICIPATING;
        $this->data['objecttable'] = attempt::TABLE;
    }
}
