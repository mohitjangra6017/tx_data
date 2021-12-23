<?php

namespace block_carousel\carouseltype;

use block_carousel\helper;

require_once "$CFG->libdir/completionlib.php";

class cluster extends base {
    function generate($data) {
        global $PAGE, $USER;
        $PAGE->requires->js_call_amd('block_carousel/curated', 'init_cluster', [$this->instance->id]);
        $renderer = $PAGE->get_renderer('block_carousel');

        // MYOHAS-117
        $hide_completed_course = $this->config->hidecompletedcourses;

        $blockinstanceid = $this->instance->id;

        $clusters = \block_carousel\get_cluster_slides($blockinstanceid);


        $all_cluster_courses = null;
        
        foreach ($clusters as $cluster) {
            $courseids = [];
            foreach ($cluster->courses as $course) {
                $all_cluster_courses[] = $course;
                // MYOHAS-117
                if($hide_completed_course) {
                    $completion = new \completion_info($course);
                    if($completion->is_course_complete($USER->id)) {
                        continue;
                    }
                }
                $courseids[] = $course->id;
            }
            // MYOHAS-117
            // if hide complete courses checkbox is checked
            // and all course in the cluster is hidden
            // hide the entire cluster
            if(empty($courseids)) {
                continue;
            }
            $cluster_slide = [];
            $cluster_slide['blockinstanceid'] = $blockinstanceid;
            $cluster_slide['id'] = $cluster->id;
            $cluster_slide['name'] = $cluster->name;
            $cluster_slide['thumbnail'] = $cluster->thumbnail;

            // don't count the completed coures in the cluster count if we are hiding completed courses
            $cluster_slide['clustercount'] = $hide_completed_course ? count($courseids) : count($cluster->courses);

            $cluster_slide['courseids'] = !empty($courseids) ? implode(",", $courseids) : null;

            $data['clusters'][] = $cluster_slide;
        }

        if(empty($data['clusters'])) {
            return '';
        }

        if (!empty($this->config->counttype) && !empty($all_cluster_courses)) {
            $data['course_counter'] = helper::get_course_count_or_completion_percentage($all_cluster_courses, $this->config->counttype);
        }
        $data['template'] = $data['template'].'_nested';

        return $renderer->render_cluster($data);
    }
}