<?php

namespace block_carousel\carouseltype;

use block_carousel\constants;

class curated extends base
{
    function generate($data)
    {
        global $PAGE;
        $renderer = $PAGE->get_renderer('block_carousel');

        $blockinstanceid = $this->instance->id;

        $cluster = \block_carousel\get_clusters($blockinstanceid);
        // since curated only has one custer
        // grab the first record
        $first_record = array_values($cluster);
        $curated = array_shift($first_record);

        $courseids = !empty($curated->courseids) ? explode(',', trim($curated->courseids)) : [];
        $tagids = !empty($curated->tagids) ? explode(',', trim($curated->tagids)) : [];

        if (empty($courseids) && empty($tagids)) {
            return null;
        }

        $final_curated_courses = \block_carousel\get_carousel_curated_course(
            $courseids,
            $tagids,
            constants::BKC_TAG_JOIN_AND,
            $this->config
        );

        $data = $this->build_course_slide_context(
            $final_curated_courses,
            get_string('type:' . constants::BKC_CURATED, 'block_carousel'),
            $data
        );


        if (empty($data['slides']) && !empty($this->config->hidecompletedcourses)) {
            return false;
        }

        return $renderer->render_curated($data);
    }

    public static function extend_form(\MoodleQuickForm $form, \stdClass $block_instance)
    {
        global $DB, $PAGE;

// TOTDEV-758
        $core_fields = [
            'c.sortorder' => get_string('coursesortorder', 'block_carousel'),
            'c.fullname' => get_string('coursefullname', 'block_carousel'),
            'lastaccess.timeaccess' => get_string('lastcourseaccess', 'block_carousel'),
        ];

// Custom fields
        $custom_fields = $DB->get_records_menu('course_info_field', null, 'fullname ASC', 'shortname, fullname');

        $sort_fields = $core_fields + $custom_fields;
        asort($sort_fields);

        $form->addElement('select', 'config_coursesortfield', get_string('sortfield', 'block_carousel'), $sort_fields);
        $form->hideIf('config_coursesortfield', 'config_carouseltype', 'eq', constants::BKC_EVENTS);
        $form->hideIf('config_coursesortfield', 'config_carouseltype', 'eq', constants::BKC_WHATS_HOT);
        $form->hideIf('config_coursesortfield', 'config_carouseltype', 'eq', constants::BKC_WHATS_NEW);
        $form->hideIf('config_coursesortfield', 'config_carouseltype', 'eq', constants::BKC_TODOLIST);
        $form->hideIf('config_coursesortfield', 'config_carouseltype', 'eq', constants::BKC_FILTERED_ENROLLED_LIST);
        $form->hideIf('config_coursesortfield', 'config_carouseltype', 'eq', constants::BKC_WHATS_HAPPENING);
        $form->hideIf('config_coursesortfield', 'config_carouseltype', 'eq', constants::BKC_FACETOFACE);
        $form->hideIf('config_coursesortfield', 'config_carouseltype', 'eq', constants::BKC_CLUSTER);
        $form->hideIf('config_coursesortfield', 'config_carouseltype', 'eq', constants::BKC_SHOUTOUTS);
        $form->hideIf('config_coursesortfield', 'config_carouseltype', 'eq', constants::BKC_COURSELETS);
        $form->hideIf('config_coursesortfield', 'config_carouseltype', 'eq', constants::BKC_PROGRAMS);

        $sort_direction = [
            'ASC' => get_string('ascending', 'block_carousel'),
            'DESC' => get_string('descending', 'block_carousel'),
        ];
        $form->addElement(
            'select',
            'config_coursesortdirection',
            get_string('sortdirection', 'block_carousel'),
            $sort_direction
        );
        $form->hideIf('config_coursesortdirection', 'config_carouseltype', 'eq', constants::BKC_EVENTS);
        $form->hideIf('config_coursesortdirection', 'config_carouseltype', 'eq', constants::BKC_WHATS_HOT);
        $form->hideIf('config_coursesortdirection', 'config_carouseltype', 'eq', constants::BKC_WHATS_NEW);
        $form->hideIf('config_coursesortdirection', 'config_carouseltype', 'eq', constants::BKC_TODOLIST);
        $form->hideIf('config_coursesortdirection', 'config_carouseltype', 'eq', constants::BKC_FILTERED_ENROLLED_LIST);
        $form->hideIf('config_coursesortdirection', 'config_carouseltype', 'eq', constants::BKC_WHATS_HAPPENING);
        $form->hideIf('config_coursesortdirection', 'config_carouseltype', 'eq', constants::BKC_FACETOFACE);
        $form->hideIf('config_coursesortdirection', 'config_carouseltype', 'eq', constants::BKC_CLUSTER);
        $form->hideIf('config_coursesortdirection', 'config_carouseltype', 'eq', constants::BKC_SHOUTOUTS);
        $form->hideIf('config_coursesortdirection', 'config_carouseltype', 'eq', constants::BKC_COURSELETS);
        $form->hideIf('config_coursesortdirection', 'config_carouseltype', 'eq', constants::BKC_PROGRAMS);

// Curated
        $form->addElement(
            'button',
            'addcurated_content',
            get_string('curatecontent', 'block_carousel'),
            ['id' => 'btn-curated-config-trigger']
        );
        $form->hideIf('addcurated_content', 'config_carouseltype', 'eq', constants::BKC_JUMPBACKIN);
        $form->hideIf('addcurated_content', 'config_carouseltype', 'eq', constants::BKC_RECOMMENDED);
        $form->hideIf('addcurated_content', 'config_carouseltype', 'eq', constants::BKC_EVENTS);
        $form->hideIf('addcurated_content', 'config_carouseltype', 'eq', constants::BKC_SHOUTOUTS);
        $form->hideIf('addcurated_content', 'config_carouseltype', 'eq', constants::BKC_TODOLIST);
        $form->hideIf('addcurated_content', 'config_carouseltype', 'eq', constants::BKC_FILTERED_ENROLLED_LIST);
        $form->hideIf('addcurated_content', 'config_carouseltype', 'eq', constants::BKC_WHATS_HOT);
        $form->hideIf('addcurated_content', 'config_carouseltype', 'eq', constants::BKC_WHATS_NEW);
        $form->hideIf('addcurated_content', 'config_carouseltype', 'eq', constants::BKC_WHATS_HAPPENING);
        $form->hideIf('addcurated_content', 'config_carouseltype', 'eq', constants::BKC_FACETOFACE);
        $form->hideIf('addcurated_content', 'config_carouseltype', 'eq', constants::BKC_COURSELETS);
        $form->hideIf('addcurated_content', 'config_carouseltype', 'eq', constants::BKC_PROGRAMS);


        $PAGE->requires->js_call_amd('block_carousel/curated', 'init', [$block_instance->id]);
    }

}