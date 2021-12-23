<?php

namespace block_carousel\carouseltype;

use stdClass;
use block_carousel\helper;

require_once($CFG->dirroot . '/blocks/carousel/lib.php');
require_once($CFG->dirroot . '/blocks/carousel/curatedlib.php');

abstract class base {
    public $config;
    public $instance;

    function __construct($config, $instance) {
        $this->config = $config;
        $this->instance = $instance;
    }

    abstract function generate($data);

    public static function extend_form(\MoodleQuickForm $form, stdClass $block_instance) {
        // Empty, child classes can implement this if they want.
    }

    /**
     * Prepare course slide context
     *
     * @param stdClass[] $courses array of courses object records
     * @param string $carousel_type carousel type as defined in \block_carousel\constants
     * @param array $data data context to be passed. The function will add the slide context upon this data array
     * @return array
     */
    public function build_course_slide_context($courses, $carousel_type, $data) {
        if (!empty($this->config->counttype) && !empty($courses)) {
            $data['course_counter'] = helper::get_course_count_or_completion_percentage($courses, $this->config->counttype);
        }

        foreach ($courses as $course) {
            $slides = $this->get_course_slide_data($course, $carousel_type, $data);
            if (!empty($slides)) {
                $data['slides'][] = $slides;
            }
        }

        $data['hidecardexpanddetails'] = get_config('block_carousel', 'hidecardexpanddetails');

        $data['title'] = empty($data['title']) ? $carousel_type : $data['title'];

        return $data;
    }

    /**
     * Get course slide data
     * Moved to its own function (from build_course_slide_context) so that it can be re-used
     * by cluster
     * 
     * @param stdClass $course record object of course
     * @param string $carousel_type carousel type as defined in \block_carousel\constants
     * @return array the data of the course slide, used for mustache template to render a slide
     */
    protected function get_course_slide_data($course, $carousel_type, $data) {
        global $USER, $DB, $PAGE;

        $courseid = !empty($course->courseid) ? $course->courseid : $course->id;

        // get course additional details
        // TODO - Instead of getting this per course
        // Get all these details in the individual query
        // This will optimise the performance
        $addtional_details = helper::get_course_additional_details($courseid, $USER->id);

        $iscomplete = !empty($addtional_details->timecompleted) ? 1 : 0;

        if (!empty($this->config->hidecompletedcourses)) {
            if ($iscomplete) {
                return false;
            }
        }

        $card_size = $data['cardsize'];

        $title = empty($course->title) ? $course->fullname : $course->title;

        // VODHAS-1949
        // TODO: Add a config to make this toggleable
        $displayinmodal = !empty($course->displayinmodal) ? 1 : 0;
        
        $image = course_get_image($courseid);

        list($custom_fields, $custom_fields_details) = $this->get_course_customfields($courseid);
        list($user_custom_fields, $user_custom_fields_details) = $this->get_user_customfields();

        if (!empty($course->summary)) {
            $context = \context_course::instance($course->id);
            $full_summary = file_rewrite_pluginfile_urls($course->summary, 'pluginfile.php', $context->id, 'course', 'summary', 0);
            $full_summary = format_text($full_summary, $course->summaryformat);
        }

        $item = [
            'title' => $this->get_item_shorttitle($card_size, $title),
            'fulltitle' => $title,
            'description' => helper::get_journey_type($course->format),
            'caption' => !empty($course->caption) ? $course->caption : null,
            'url' => '/course/view.php?id=' . $courseid,
            'thumbnail' => new \moodle_url($image->out(), ['preview' => 'block_carousel_large', 'theme' => $PAGE->theme->name]),
            'courseid' => $courseid,
            'summary' => !empty($course->summary) ? $this->get_item_shortsummary($card_size, $course->summary) : null,
            'fullsummary' => $full_summary ?? null,
            'iscomplete' => $iscomplete,
            'coursetype' => !empty($addtional_details->coursetype) ? $addtional_details->coursetype : null,
            'cardsize' => !empty($this->config->cardsize) ? $this->config->cardsize : null,
            'custom_fields' => $custom_fields,
            'custom_fields_details' => $custom_fields_details,
            'user_custom_fields' => $user_custom_fields,
            'user_custom_fields_details' => $user_custom_fields_details,
            'carouseltype' => $carousel_type,
            'displayinmodal' => $displayinmodal,
            'showcompletionicon' => empty($this->config->hidecompletedcourses) ? true : false
        ];

        if (!empty($addtional_details)) {
            $item['tags'] = $this->get_course_tags($addtional_details);
            $item['icon'] = $this->get_course_icon($addtional_details, $course->format);
            $item['rating'] = $addtional_details->course_rating;
            $item['likes'] = $addtional_details->course_likes;
            $item['completions'] = $addtional_details->course_completions;
        }

        if (!empty($course->format)  && $course->format == 'singleactivity' && !isset($course->displayinmodal)) {
            list($displayinmodal, $courselet_url) = $this->get_courselet_data($course, $courseid, $displayinmodal, $carousel_type);
            $item['courselet_url'] = $courselet_url;
            $item['displayinmodal'] = $displayinmodal;
        }

        return $item;
    }

    protected function get_courselet_data($course, $courseid, $displayinmodal, $carousel_type) {
        global $DB;

        $courselet_url = '';

        // get course modules
        $sql = "SELECT cm.id, m.name, cm.instance,
                CASE 
                    WHEN m.name = 'url' THEN 1
                    WHEN m.name = 'resource' THEN 1
                ELSE 0
                END AS displayinmodal
                    FROM {course_modules} cm
                LEFT JOIN {modules} m 
                    ON m.id = cm.module 
                    JOIN {course_format_options} cfo
                    ON cfo.courseid = cm.course AND cfo.value = m.name 
                    WHERE cm.course = :courseid";

        $course_module = $DB->get_record_sql($sql, ['courseid' => $courseid]);
        if (!empty($course_module)) {
            $displayinmodal = $course_module->displayinmodal;
            
            $courselet_url = helper::get_courselet_link($course,$course_module->name, $course_module->id, $course_module->instance, ($carousel_type == get_string('type:'.\block_carousel\constants::BKC_WHATS_HAPPENING, 'block_carousel')));
        }

        return array($displayinmodal, $courselet_url);
    }

    /**
     * Get the truncated title
     */
    protected function get_item_shorttitle($card_size, $title) {
        $strip_title_length = ($card_size == \block_carousel\constants::BKC_CARD_LARGE) ? \block_carousel\constants::BKC_TITLE_LENGHT_LARGE_CARD : \block_carousel\constants::BKC_TITLE_LENGHT_SMALL_CARD;
        return substr(strip_tags($title), 0, $strip_title_length) . (strlen($title) > $strip_title_length ? '...' : '');
    }

    /**
     * get the truncated summary
     */
    protected function get_item_shortsummary($card_size, $summary) {
        $strip_summary_length = ($card_size == \block_carousel\constants::BKC_CARD_LARGE) ? \block_carousel\constants::BKC_SUMMARY_LENGHT_LARGE_CARD : \block_carousel\constants::BKC_SUMMARY_LENGHT_SMALL_CARD;
        return substr(strip_tags($summary), 0, $strip_summary_length) . (strlen($summary) > $strip_summary_length ? '...' : '');
    }

    /**
     * get the selected customfield of a user for the course item
     */
    protected function get_user_customfields() {
        global $USER;
        // User custom fields in the config
        // for cards
        $userfield_shortnames = helper::get_carousel_custom_field_shortnames($this->config, 'usercustomfields');
        $user_custom_fields = helper::get_user_custom_field_data($USER->id, $userfield_shortnames);
        // for details
        $userfield_shortnames_details = helper::get_carousel_custom_field_shortnames($this->config, 'usercustomfieldsdetails');
        $user_custom_fields_details = helper::get_user_custom_field_data($USER->id, $userfield_shortnames_details);

        return array($user_custom_fields, $user_custom_fields_details);
    }

    /**
     * Get the selected customfields of a course item
     */
    protected function get_course_customfields($courseid) {
        // Custom fields set in the config
        // for cards
        $shortname = helper::get_carousel_custom_field_shortnames($this->config, 'coursecustomfields');
        $custom_fields = helper::get_course_custom_field_data($courseid, $shortname);
        // for details
        $shortname_details = helper::get_carousel_custom_field_shortnames($this->config, 'coursecustomfieldsdetails');
        $custom_fields_details = helper::get_course_custom_field_data($courseid, $shortname_details);

        // hot new courses
        if (class_exists('\block_whats_hot\helper')) {
            $hot_courses = \block_whats_hot\helper::get_hot_courses();
        } else {
            $hot_courses = array();
        }
        $new_courses = helper::get_new_courses($this->config);
        if (in_array($courseid, array_keys($hot_courses))) {
            array_unshift($custom_fields, get_string('hot', 'block_carousel'));
            array_unshift($custom_fields_details, get_string('hot', 'block_carousel'));
        }
        if (in_array($courseid, array_keys($new_courses))) {
            array_unshift($custom_fields, get_string('new', 'block_carousel'));
            array_unshift($custom_fields_details, get_string('new', 'block_carousel'));
        }

        return array($custom_fields, $custom_fields_details);
    }

    /**
     * Get course tags for carousel item
     */
    protected function get_course_tags($addtional_details) {
        $tags = [];

        if (!empty($addtional_details->course_tags)) {
            // tags
            $tags = explode(",", $addtional_details->course_tags);

            // shuffle tags
            shuffle($tags);
        }

        return $tags;
    }

    /**
     * Get carousel item icon
     */
    protected function get_course_icon($addtional_details, $courseformat) {
        // default icon
        $icon = \html_writer::tag('i', null, ['class' => 'far fa-desktop']);

        if (!empty($addtional_details->icon)) {
            // Check if course has a selected icon
            $icon = totara_icon_picker_preview('course', $addtional_details->icon);
        } else if ($courseformat == 'singleactivity') {
            // Vodafone courselet icon
            $icon = \html_writer::tag('i', null, ['class' => 'fas fa-paperclip']);
        }

        return $icon;
    }
    
    protected function get_blockplugin_config($plugin) {
        $config = new \stdClass();

        foreach ($this->config as $key => $value) {
            if (strpos($key, $plugin . '_') === 0) {
                $configname = substr($key, strlen($plugin . '_'));
                $config->$configname = $value;
            }
        }

        return $config;
    }

}