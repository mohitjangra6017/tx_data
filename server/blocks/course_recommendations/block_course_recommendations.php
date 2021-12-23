<?php

/**
 * Comment
 *
 * @package    block
 * @subpackage course_recommendations
 * @copyright  &copy; 2017 CG Kineo {@link http://www.kineo.com}
 * @author     kaushtuv.gurung
 * @version    1.0
 */
global $CFG;

require_once($CFG->dirroot .'/blocks/course_recommendations/lib.php');

class block_course_recommendations extends block_base {

    /**
     * @throws coding_exception
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_course_recommendations');
    }

    /**
     * @return bool
     */
    public function has_config() {
        return false;
    }

    /**
     * @return bool
     */
    public function instance_allow_multiple() {
        return true;
    }

    /**
     * @return array|bool[]
     */
    public function applicable_formats() {
        return array('all' => true);
    }

    /**
     * @throws coding_exception
     */
    public function specialization() {
        if (empty($this->config)) {
            $this->config = new stdClass();
        }
        
        if(!empty($this->config->displayname)) {
            $this->title = $this->config->displayname;
        }
        
        if(empty($this->config->blockinfo)) {
            $this->config->blockinfo = get_string('blockinfo_default','block_course_recommendations');
        }
    }

    /**
     * @return stdClass
     * @throws coding_exception
     * @throws moodle_exception
     */
    public function get_content() {
        global $PAGE, $USER;

        $PAGE->requires->css('/blocks/course_recommendations/styles.css');

        if ($this->content !== null) {
            return $this->content;
        }
        
        if (empty($this->config)) {
            $this->config = new stdClass();
        }

        if ($recommendations = get_course_recommendations($USER->id)) {
            $this->content = new stdClass();
            $this->content->text = html_writer::div($this->config->blockinfo, 'block-info') . $this->build_recommendation_list($recommendations);
            $this->content->footer = '';
        }


        return $this->content;
    }

    /**
     * @param $recommendations
     * @return mixed
     * @throws coding_exception
     * @throws moodle_exception
     */
    private function build_recommendation_list($recommendations) {
        global $PAGE, $CFG;

        $output = $PAGE->get_renderer('block_course_recommendations');

        $table = new stdClass();
        $table->attributes['class'] = 'totaratable course-recommendations';
        $table->head = [
            get_string('th_course','block_course_recommendations'),
            get_string('th_total_recommendations','block_course_recommendations'),
            get_string('th_actions','block_course_recommendations')
        ];

        $data = [];
        foreach ($recommendations as $r) {
            $data[] = [
                html_writer::link(
                    new moodle_url($CFG->wwwroot . '/course/view.php', ['id' => $r->id]),
                    $r->fullname
                ),
                $r->frequency . $output->render_help_icon($r->id),
                $output->render_action_buttons($r)
            ];
        }

        $table->data = $data;

        return $output->render_recommendations($table);
    }
}
