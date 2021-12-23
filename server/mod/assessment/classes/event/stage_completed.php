<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

namespace mod_assessment\event;

use core\event\base;
use mod_assessment\model\stage_completion;

defined('MOODLE_INTERNAL') || die();

class stage_completed extends base
{

    public static function create_from_completion(stage_completion $stagecompletion, $context): base
    {
        $data = [
            'context' => $context,
            'objectid' => $stagecompletion->id,
            'other' => $stagecompletion->attemptid,
            'relateduserid' => $stagecompletion->userid,
        ];

        return self::create($data);
    }

    protected function init()
    {
        $this->data['crud'] = 'u';
        $this->data['edulevel'] = self::LEVEL_PARTICIPATING;
        $this->data['objecttable'] = 'assessment_stage_completion';
    }
}
