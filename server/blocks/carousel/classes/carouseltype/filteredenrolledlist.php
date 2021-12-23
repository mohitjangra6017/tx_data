<?php

namespace block_carousel\carouseltype;

use block_carousel\constants;
use \block_carousel\helper;

class filteredenrolledlist extends base {
    function generate($data) {
        global $PAGE, $USER, $DB;
        $renderer = $PAGE->get_renderer('block_carousel');

        //this is a special carousel type that has it's own template. Remove the default+sitewide template setting
        unset($data['template']);

        $data['title'] = empty($data['title']) ? get_string('type:' . constants::BKC_FILTERED_ENROLLED_LIST, 'block_carousel') : $data['title'];

        $pluginconfig = get_config('block_carousel');

        $enrolledcourses = helper::get_enrolled_courses($USER->id, $this->config);

        if (empty($enrolledcourses)) {
            return $renderer->render_filteredenrolledlist($data);
        }

        $thresholddata = array();
        if (!empty($this->config->lowerthreshold) && !empty($this->config->upperthreshold)) {
            $thresholddata = helper::get_course_threshold($enrolledcourses, $this->config);
        }

        $subheadingdata = array();
        if (!empty($this->config->subheading)) {
            $subheadingdata = helper::get_course_subheading($enrolledcourses, $this->config);
        }

        $shortsummarydata = array();
        if (!empty($this->config->shortsummary)) {
            $shortsummarydata = helper::get_course_summary($enrolledcourses, $this->config);
        }

        $namefield = 'fullname';
        if (!empty($this->config->namefield)) {
            $namefield = $this->config->namefield;
        }

        foreach ($enrolledcourses as $course) {
            if (!empty($this->config->lowerthreshold)
            && !empty($this->config->upperthreshold)
            && isset($thresholddata[$this->config->lowerthreshold . 'c' . $course->courseid]->data)
            && isset($thresholddata[$this->config->upperthreshold . 'c' . $course->courseid]->data)
            ) {
                $upper = (float)$thresholddata[$this->config->upperthreshold . 'c' . $course->courseid]->data;
                $lower = (float)$thresholddata[$this->config->lowerthreshold . 'c' . $course->courseid]->data;
            } else if (isset($pluginconfig->progress_threshold_lower) && isset($pluginconfig->progress_threshold_upper)) {
                $upper = (float)$pluginconfig->progress_threshold_upper;
                $lower = (float)$pluginconfig->progress_threshold_lower;
            }

            $buttonlabel = empty($pluginconfig->buttonlabel_fallback) ? get_string('btn_launch', 'block_carousel') : $pluginconfig->buttonlabel_fallback;
            if (!empty($upper)) {
                if ((float)$course->progress < $lower) {
                    $buttonlabel = empty($pluginconfig->buttonlabel_lowerunreached) ? get_string('btn_getstarted', 'block_carousel') : $pluginconfig->buttonlabel_lowerunreached;
                    ;
                } else if ((float)$course->progress < $upper) {
                    $buttonlabel = empty($pluginconfig->buttonlabel_lowerreached) ? get_string('btn_continue', 'block_carousel') : $pluginconfig->buttonlabel_lowerreached;
                } else {
                    $buttonlabel = empty($pluginconfig->buttonlabel_upperreached) ? get_string('btn_finishitoff', 'block_carousel') : $pluginconfig->buttonlabel_upperreached;
                }
            }

            $subheading = '';
            if (isset($subheadingdata[$course->courseid]->data)) {
                $subheading = $subheadingdata[$course->courseid]->data;
            }

            $shortsummary = '';
            if (isset($shortsummarydata[$course->courseid]->data)) {
                $shortsummary = $shortsummarydata[$course->courseid]->data;
            }

            [$iconurl, $iconalt] = totara_icon_url_and_alt('course', $course->icon, 'icon');

            if (!$course->timecompleted) {
                if (!empty($this->config->netflix)) {
                    $data['slides'][] = $this->get_course_slide_data($course, get_string('type:' . constants::BKC_FILTERED_ENROLLED_LIST, 'block_carousel'), $data);
                } else {
                    $data['slides'][] =
                    [
                        'title' => $course->$namefield,
                        'subheading' => $subheading,
                        'shortsummary' => $shortsummary,
                        'url' => '/course/view.php?id=' . $course->courseid,
                        'thumbnail' => $iconurl->out(),
                        'progress' => round($course->progress, 0),
                        'remaining' => helper::calculate_remaining_time($course, $this->config),
                        'buttonlabel' => $buttonlabel
                    ];
                }
            }
        }

        if (!empty($this->config->netflix)) {
            $data['template'] = '_red'; 
        }


        if (!empty($this->config->allowtagsfilter)) {
            $listoftags = $this->config->listoftags;

            $slides = $data['slides'];
            foreach ($slides as $key => $slide) {
                $result = array_intersect($slide['tags'], $listoftags);

                if ($this->config->tagsfiltertype === 'include') {
                    if (empty($result)) {
                        unset($data['slides'][$key]);
                    }
                } else {
                    if (!empty($result)) {
                        unset($data['slides'][$key]);
                    }
                }
            }
        }
        
        return $renderer->render_filteredenrolledlist($data);
    }

    public static function extend_form(\MoodleQuickForm $form, \stdClass $block_instance)
    {
        global $DB, $CFG;

        $records = $DB->get_records('course_info_field');

        $infofields = ['' => ''];
        foreach ($records as $record) {
            $infofields[$record->shortname] = $record->fullname;
        }

        $form->addElement(
            'select',
            'config_captionshortname',
            get_string('captionshortname', 'block_carousel'),
            $infofields
        );
        $form->setDefault('config_captionshortname', 'caption');
        $form->hideIf('config_captionshortname', 'config_carouseltype', 'neq', constants::BKC_FILTERED_ENROLLED_LIST);

        $form->addElement('advcheckbox', 'config_netflix', get_string('netflix', 'block_carousel'));
        $form->setDefault('config_netflix', 1);
        $form->hideIf('config_netflix', 'config_carouseltype', 'neq', constants::BKC_FILTERED_ENROLLED_LIST);

        $form->addElement('advcheckbox', 'config_allenrolments', get_string('allenrolments', 'block_carousel'));
        $form->setDefault('config_allenrolments', 1);
        $form->hideIf('config_allenrolments', 'config_carouseltype', 'neq', constants::BKC_FILTERED_ENROLLED_LIST);

        $records = $DB->get_records_sql(
            <<<SQL
    SELECT cp.*
      FROM "ttr_config_plugins" cp
     WHERE cp.plugin LIKE 'enrol_%'
       AND cp.name = 'version'
SQL
        );

        $enrolmenttypes = [];

        foreach ($records as $record) {
            $enrolmenttypes[substr($record->plugin, 6)] = get_string('pluginname', $record->plugin);
        }

        $multiselect =
            $form->addElement(
                'select',
                'config_enrolmenttype',
                get_string('enrolmenttype', 'block_carousel'),
                $enrolmenttypes
            );
        $multiselect->setMultiple(true);
        $form->hideIf('config_enrolmenttype', 'config_allenrolments', 'checked');

        if (file_exists($CFG->dirroot . '/totara/customfield/field/duration/custom_duration.php')) {
            $records = $DB->get_records('course_info_field', ['datatype' => 'duration']);
            $durationfields = [];
            foreach ($records as $record) {
                $durationfields[$record->shortname] = $record->fullname;
            }
            $form->addElement(
                'select',
                'config_coursedurationestimate',
                get_string('coursedurationestimate', 'block_carousel'),
                $durationfields
            );
            $form->hideIf('config_coursedurationestimate', 'config_carouseltype', 'neq', constants::BKC_FILTERED_ENROLLED_LIST);
        }

        $form->addElement(
            'select',
            'config_lowerthreshold',
            get_string('lowerthreshold', 'block_carousel'),
            $infofields
        );
        $form->hideIf('config_lowerthreshold', 'config_carouseltype', 'neq', constants::BKC_FILTERED_ENROLLED_LIST);

        $form->addElement(
            'select',
            'config_upperthreshold',
            get_string('upperthreshold', 'block_carousel'),
            $infofields
        );
        $form->hideIf('config_upperthreshold', 'config_carouseltype', 'neq', constants::BKC_FILTERED_ENROLLED_LIST);

        $form->addElement(
            'select',
            'config_namefield',
            get_string('namefield', 'block_carousel'),
            ['fullname' => 'Course full name', 'shortname' => 'Course short name']
        );
        $form->hideIf('config_namefield', 'config_carouseltype', 'neq', constants::BKC_FILTERED_ENROLLED_LIST);

        $form->addElement('select', 'config_subheading', get_string('subheading', 'block_carousel'), $infofields);
        $form->hideIf('config_subheading', 'config_carouseltype', 'neq', constants::BKC_FILTERED_ENROLLED_LIST);

        $form->addElement('select', 'config_shortsummary', get_string('shortsummary', 'block_carousel'), $infofields);
        $form->hideIf('config_shortsummary', 'config_carouseltype', 'neq', constants::BKC_FILTERED_ENROLLED_LIST);

        $form->addElement('advcheckbox', 'config_allowtagsfilter', get_string('allowtagsfilter', 'block_carousel'));
        $form->hideIf('config_allowtagsfilter', 'config_carouseltype', 'neq', constants::BKC_FILTERED_ENROLLED_LIST);

        $tagsfiltertype = [
            'include' => 'include',
            'exclude' => 'exclude',
        ];

        $form->addElement(
            'select',
            'config_tagsfiltertype',
            get_string('tagsfiltertype', 'block_carousel'),
            $tagsfiltertype
        );
        $form->hideIf('config_allowtagsfilter', 'config_carouseltype', 'neq', constants::BKC_FILTERED_ENROLLED_LIST);
        $form->hideIf('config_tagsfiltertype', 'config_allowtagsfilter', 'notchecked');

        $sql = <<<SQL
SELECT DISTINCT t.name
FROM "ttr_tag_instance" i
JOIN "ttr_tag" t ON t.id = i.tagid
WHERE i.component = 'core' AND i.itemtype = 'course'
SQL;
        $records = $DB->get_records_sql($sql);

        $listoftags = [];
        foreach ($records as $record) {
            $listoftags[$record->name] = $record->name;
        }

        $multiselect =
            $form->addElement('select', 'config_listoftags', get_string('listoftags', 'block_carousel'), $listoftags);
        $multiselect->setMultiple(true);
        $form->hideIf('config_allowtagsfilter', 'config_carouseltype', 'neq', constants::BKC_FILTERED_ENROLLED_LIST);
        $form->hideIf('config_listoftags', 'config_allowtagsfilter', 'notchecked');

        $form->addElement(
            'select',
            'config_sortfield',
            get_string('sortfield', 'block_carousel'),
            array_merge(['lastaccess.timeaccess' => 'Last course access'], $infofields)
        );
        $form->hideIf('config_sortfield', 'config_carouseltype', 'neq', constants::BKC_FILTERED_ENROLLED_LIST);

        $form->addElement(
            'select',
            'config_sortdirection',
            get_string('sortdirection', 'block_carousel'),
            ['DESC' => 'Descending', 'ASC' => 'Ascending']
        );
        $form->hideIf('config_sortdirection', 'config_carouseltype', 'neq', constants::BKC_FILTERED_ENROLLED_LIST);
    }
}