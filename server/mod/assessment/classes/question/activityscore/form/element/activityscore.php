<?php
/**
 * @copyright 2019 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Alex Glover <alex.glover@kineo.com>
 * @package mod_assessment
 * @subpackage assquestion_activityscore
 */

namespace mod_assessment\question\activityscore\form\element;

use cm_info;
use mod_assessment\model\assessment;
use mod_assessment\model\version;
use mod_assessment\question\element;
use renderer_base;
use totara_form\form\element\text;

class activityscore extends text
{

    use element;

    /**
     * @param renderer_base $output
     * @return array
     */
    public function export_for_template(renderer_base $output): array
    {
        global $CFG;

        require_once $CFG->libdir . '/gradelib.php';

        $role = $this->get_model()->get_current_data('role')['role'];
        $versionid = $this->get_model()->get_current_data('versionid')['versionid'];

        $version = version::instance(['id' => $versionid], MUST_EXIST);
        $assessment = assessment::instance(['id' => $version->assessmentid], MUST_EXIST);

        $attempt = $this->get_attempt($version);
        $question = $this->get_question();

        $items = [];

        $activity_ids = $question->get_activity_ids();

        foreach ($activity_ids as $cmid) {
            $cminfo = cm_info::create((object)['id' => $cmid, 'course' => $assessment->course], $attempt->userid);
            $grades_info = grade_get_grades($cminfo->course, 'mod', $cminfo->modname, $cminfo->instance, $attempt->userid);
            $score = get_string('unavailable', 'assquestion_activityscore');
            $score_long = $score;
            if (!empty($grades_info)) {
                $item = array_pop($grades_info->items);
                if (isset($item->grades[$attempt->userid])) {
                    $grade = $item->grades[$attempt->userid];
                    $score = $grade->str_grade;
                    $score_long = $grade->str_long_grade;
                }
            }
            $items[] = ['activityname' => $cminfo->name, 'score' => $score, 'scorelong' => $score_long];
        }

        $result = parent::export_for_template($output);
        $result = array_merge($result, [
            'form_item_template' => 'assquestion_activityscore/element_activityscore',
            'items' => $items,
        ]);

        // Get visibility for current role
        $params = $this->export_default_template_params($question, $attempt, $role, $this->get_role());
        $result = array_merge($result, $params);

        return $result;
    }

    /**
     * @return array
     */
    public function get_data(): array
    {
        return [$this->get_name() => null];
    }

    public function get_field_value()
    {
        return null;
    }
}
