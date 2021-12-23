<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

namespace mod_assessment\message;

use core\message\message;
use html_writer;
use mod_assessment\model\assessment;
use moodle_url;
use stdClass;

defined('MOODLE_INTERNAL') || die();

class completion extends message
{

    protected int $msgtype;

    protected array $properties = ['msgtype'];

    public function __construct($manager, $learner, assessment $assessment)
    {

        $courseurl = new moodle_url('/course/view.php', ['id' => $assessment->course]);
        $placeholders = (object)[
            'courselink' => html_writer::link($courseurl, $courseurl->out(false)),
            'assessmentname' => $assessment->name,
            'fullname' => fullname($learner),
        ];

        list($course, $cm) = get_course_and_cm_from_cmid($assessment->get_cmid(), 'assessment');

        $this->component = 'mod_assessment';
        $this->courseid = $course->id;
        $this->name = 'completion';

        $this->fullmessageformat = FORMAT_HTML;
        $this->msgtype = TOTARA_MSG_TYPE_UNKNOWN;

        $this->subject = get_string('msg:completion:subject', 'assessment', $placeholders);
        $this->fullmessage = get_string('msg:completion:body', 'assessment', $placeholders);
        $this->fullmessagehtml = get_string('msg:completion:body', 'assessment', $placeholders);

        $this->userfrom = $learner;
        $this->userto = $manager;
    }

    public function get_eventobject_for_processor($processorname): stdClass
    {
        $eventdata = parent::get_eventobject_for_processor($processorname);
        foreach ($this->properties as $property) {
            $eventdata->$property = $this->$property;
        }
        return $eventdata;
    }

    public static function send($manager, $learner, assessment $assessment): bool
    {
        $message = new self($manager, $learner, $assessment);
        return message_send($message);
    }
}
