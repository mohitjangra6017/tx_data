<?php

namespace block_carousel\output;

defined('MOODLE_INTERNAL') || die();

class renderer extends \plugin_renderer_base {

    public function render_jumpbackin($data) {
        $data['slides'] = $this->render_from_template('block_carousel/'.$data['template'], $data);
        return $this->render_from_template('block_carousel/jumpbackin', $data);
    }

    public function render_recommended($data) {
        $data['slides'] = $this->render_from_template('block_carousel/'.$data['template'], $data);
        return $this->render_from_template('block_carousel/recommended', $data);
    }

    public function render_events($data) {
        return $this->render_from_template('block_carousel/events', $data);
    }

    
    public function render_shoutouts($data) {
        $data['slides'] = $this->render_from_template('block_carousel/'.$data['template'], $data);
        return $this->render_from_template('block_carousel/shoutouts', $data);
    }

    public function render_facetoface($data) {
        $data['slides'] = $this->render_from_template('block_carousel/'.$data['template'], $data);
        return $this->render_from_template('block_carousel/facetoface', $data);
    }
    
    public function render_todolist($data) {
        $data['slides'] = $this->render_from_template('block_carousel/'.$data['template'], $data);
        return $this->render_from_template('block_carousel/todolist', $data);
    }

    public function render_filteredenrolledlist($data) {
        if (isset($data['template'])) {
            $data['slides'] = $this->render_from_template('block_carousel/'.$data['template'], $data);
            return $this->render_from_template('block_carousel/filteredenrolledlist_new', $data);
        }

        return $this->render_from_template('block_carousel/filteredenrolledlist', $data);
    }
    
    /**
     * load cached custom styles for the block instance
     *
     * @param type $data
     */
    public function custom_styles($data) {
        return $this->render_from_template('block_carousel/custom_styles', $data);
    }
    
    
    /**
     * Render what's hot slides
     * 
     * @param type $data
     * @return type
     */
    public function render_whatshot($data) {
        $data['slides'] = $this->render_from_template('block_carousel/'.$data['template'], $data);
        return $this->render_from_template('block_carousel/whatshot', $data);
    }
    
    public function render_curated($data) {
        $data['slides'] = $this->render_from_template('block_carousel/'.$data['template'], $data);
        return $this->render_from_template('block_carousel/curated', $data);
    }
    
    
    /**
     * Render what's new slides
     * 
     * @param type $data
     * @return type
     */
    public function render_whatsnew($data) {
        $data['slides'] = $this->render_from_template('block_carousel/'.$data['template'], $data);
        return $this->render_from_template('block_carousel/whatsnew', $data);
    }
    
    public function render_whatshappening($data) {
        $data['slides'] = $this->render_from_template('block_carousel/'.$data['template'], $data);
        return $this->render_from_template('block_carousel/whatshappening', $data);
    }
    
    public function render_curated_filters($data) {
        return $this->render_from_template('block_carousel/__config_curated_filters', $data);
    }
    
    public function render_curated_preview_list($data) {
        return $this->render_from_template('block_carousel/__config_curated_preview_list', $data);
    }
    
    public function render_cohort_filters($data) {
        return $this->render_from_template('block_carousel/__config_cohort_filters', $data);
    }
    
    public function render_cluster($data) {
        $data['slides'] = $this->render_from_template('block_carousel/'.$data['template'], $data);
        
        return $this->render_from_template('block_carousel/cluster', $data);
    }
    
    public function render_star_ratings($data) {
        return $this->render_from_template('block_carousel/__rating_stars', $data);
    }

    public function get_existing_cohorts($data) {
        return $this->render_from_template('block_carousel/existing_cohort_list', $data);
    }

    public function render_cluster_contents($data) {
        return $this->render_from_template('block_carousel/'.$data['template'], $data);
    }

    public function render_courselets($data) {
        $data['slides'] = $this->render_from_template('block_carousel/'.$data['template'], $data);
        return $this->render_from_template('block_carousel/courselets', $data);
    }

    public function render_courselets_modal_info($data) {
        return $this->render_from_template('block_carousel/__courselet_modal_info', $data);
    }

    public function render_likes($data) {
        return $this->render_from_template('block_carousel/__likes', $data);
    }

    public function render_completion_count($data) {
        return $this->render_from_template('block_carousel/__completion_count', $data);
    }

    public function render_programs($data) {
        $data['slides'] = $this->render_from_template('block_carousel/'.$data['template'], $data);
        return $this->render_from_template('block_carousel/curated', $data);
    }
}
