<?php
/**
 * @copyright 2017-2019 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @author Alex Glover <alex.glover@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 *
 * DEVTIOTIS2
 * - Send different message for multiple evaluators vs single
 */

namespace mod_assessment\message;

use core\message\message;
use html_writer;
use mod_assessment\model\assessment;
use moodle_url;
use stdClass;

defined('MOODLE_INTERNAL') || die();

class evaluatorselected extends message
{

    protected int $msgtype;

    protected array $properties = ['msgtype'];

    protected $is_singleevaluator = true;

    public function __construct($evaluator, $learner, assessment $assessment, $is_singleevaluator = true)
    {

        $this->is_singleevaluator = $is_singleevaluator;

        $assessmenturl = new moodle_url('/mod/assessment/view.php', ['id' => $assessment->get_cmid(), 'userid' => $learner->id]);
        $placeholders = (object)[
            'assessmentlink' => html_writer::link($assessmenturl, $assessmenturl->out(false)),
            'assessmentname' => $assessment->name,
            'fullname' => fullname($learner),
        ];

        list($course, $cm) = get_course_and_cm_from_cmid($assessment->get_cmid(), 'assessment');

        $this->component = 'mod_assessment';
        $this->courseid = $course->id;
        $this->name = 'evaluatorselected';

        $this->fullmessageformat = FORMAT_HTML;
        $this->msgtype = TOTARA_MSG_TYPE_UNKNOWN;

        $keyprefix = ($this->is_singleevaluator ? 'evaluatorselected' : 'evaluatorassigned');
        $this->subject = get_string('msg:' . $keyprefix . ':subject', 'assessment', $placeholders);
        $this->fullmessage = get_string('msg:' . $keyprefix . ':body', 'assessment', $placeholders);
        $this->fullmessagehtml = get_string('msg:' . $keyprefix . ':body', 'assessment', $placeholders);

        $this->userfrom = $learner;
        $this->userto = $evaluator;
    }

    public function get_eventobject_for_processor($processorname): stdClass
    {
        $eventdata = parent::get_eventobject_for_processor($processorname);
        foreach ($this->properties as $property) {
            $eventdata->$property = $this->$property;
        }
        return $eventdata;
    }

    public static function send($evaluator, $learner, assessment $assessment, $is_singleevaluator = true): bool
    {
        $message = new self($evaluator, $learner, $assessment, $is_singleevaluator);
        return message_send($message);
    }
}
