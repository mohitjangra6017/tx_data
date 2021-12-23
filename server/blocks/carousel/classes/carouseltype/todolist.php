<?php

namespace block_carousel\carouseltype;

use block_carousel\constants;

class todolist extends base
{
    function generate($data)
    {
        global $PAGE, $USER, $DB, $CFG;
        $renderer = $PAGE->get_renderer('block_carousel');

        $todofeed = [];

        $config = $this->get_blockplugin_config(constants::BKC_TODOLIST);

        require_once($CFG->dirroot . '/blocks/enrol_accordion/classes/carousel_todo.php');
        $todofeed = \block_enrol_accordion\todolist::fetch($config, $USER->id);

        $count = 0;

        $todocourses = [];

        foreach ($todofeed as $section => $courses) {
            if ($courses['toggle_status'] !== \block_enrol_accordion::BLOCK_ENROL_ACCORDION_HIDDEN) {
                foreach ($courses['courses'] as $course) {
                    $todocourses[] = (object)$course;
                    $count += 1;
                }
            }
        }

        $data = $this->build_course_slide_context(
            $todocourses,
            get_string('type:' . constants::BKC_TODOLIST, 'block_carousel'),
            $data
        );

        $data['coursecount'] = $count;

        return $renderer->render_todolist($data);
    }

    public static function extend_form(\MoodleQuickForm $form, \stdClass $block_instance)
    {
        global $CFG;

        if (!file_exists($CFG->dirroot . '/blocks/enrol_accordion/version.php')) {
            return;
        }

        require_once($CFG->dirroot . '/blocks/enrol_accordion/block_enrol_accordion.php');

        $modes = [
            block_enrol_accordion::BLOCK_ENROL_ACCORDION_DISPLAYCOURSES_ENROLLED => get_string(
                'mode_enrolled',
                'block_enrol_accordion'
            ),
            block_enrol_accordion::BLOCK_ENROL_ACCORDION_DISPLAYCOURSES_CATALOGUE => get_string(
                'mode_catalogue',
                'block_enrol_accordion'
            ),
            block_enrol_accordion::BLOCK_ENROL_ACCORDION_DISPLAYCOURSES_CATALOGUE_SELFONLY => get_string(
                'mode_catalogue_selfonly',
                'block_enrol_accordion'
            ),
        ];

        $form->addElement(
            'select',
            'config_todolist_displaycourses',
            get_string('displaycourses', 'block_enrol_accordion'),
            $modes
        );
        $form->setType('config_todolist_displaycourses', PARAM_INT);
        $form->hideIf('config_todolist_displaycourses', 'config_carouseltype', 'neq', constants::BKC_TODOLIST);

        // no of course displayed in each section
        $nos = [];
        for ($i = 1; $i <= 20; $i++) {
            $nos[$i] = $i;
        }
        $nos[block_enrol_accordion::ALL_RECORDS] = get_string('allRecords', 'block_enrol_accordion');
        $form->addElement(
            'select',
            'config_todolist_no_course_per_section',
            get_string('course_per_section', 'block_enrol_accordion'),
            $nos
        )->setSelected(block_enrol_accordion::BLOCK_ENROL_ACCORDION_DEFAULT_RECORDS_PER_PAGE);
        $form->setType('config_todolist_no_course_per_secion', PARAM_INT);
        $form->hideIf('config_todolist_no_course_per_section', 'config_carouseltype', 'neq', constants::BKC_TODOLIST);

        // accordion display
        $display_modes = [
            block_enrol_accordion::BLOCK_ENROL_TO_DO => get_string('display_type_to_do', 'block_enrol_accordion'),
            block_enrol_accordion::BLOCK_ENROL_COURSE_CATALOGUE => get_string(
                'display_type_coursecatalogue',
                'block_enrol_accordion'
            ),
        ];
        $form->addElement(
            'select',
            'config_todolist_display_type',
            get_string('display_type', 'block_enrol_accordion'),
            $display_modes
        );
        $form->hideIf('config_todolist_display_type', 'config_carouseltype', 'neq', constants::BKC_TODOLIST);

        // course catalogue group by
        $course_catalogue_groupby = [
            block_enrol_accordion::GROUP_BY_CATEGORY => get_string('group_by_category', 'block_enrol_accordion'),
            block_enrol_accordion::GROUP_BY_TAG => get_string('group_by_tag', 'block_enrol_accordion'),
        ];
        $form->addElement(
            'select',
            'config_todolist_catalogue_group_by',
            get_string('course_catalogue_groupby', 'block_enrol_accordion'),
            $course_catalogue_groupby
        );
        $form->setType('config_todolist_catalogue_group_by', PARAM_INT);
        $form->hideIf('config_todolist_catalogue_group_by', 'config_carouseltype', 'neq', constants::BKC_TODOLIST);

        $records = $DB->get_records_select('course_info_field', "datatype IN ('menu', 'multiselect')");

        $tags = [];

        foreach ($records as $record) {
            $tags[$record->shortname] = $record->fullname;
        }

        $form->addElement(
            'select',
            'config_todolist_catalogue_group_by_tag',
            get_string('config_catalogue_group_by_tag', 'block_enrol_accordion'),
            $tags
        );
        $form->hideIf('config_todolist_catalogue_group_by_tag', 'config_carouseltype', 'neq', constants::BKC_TODOLIST);

        $form->addElement(
            'advcheckbox',
            'config_todolist_course_info_duration',
            get_string('course_duration', 'block_enrol_accordion'),
            null,
            ['group' => 1]
        );
        $form->hideIf('config_todolist_course_info_duration', 'config_carouseltype', 'neq', constants::BKC_TODOLIST);

        $form->addElement(
            'advcheckbox',
            'config_todolist_course_info_enrolled_method',
            get_string('enrolled_method', 'block_enrol_accordion'),
            null,
            ['group' => 1]
        );
        $form->hideIf('config_todolist_course_info_enrolled_method', 'config_carouseltype', 'neq', constants::BKC_TODOLIST);
        $form->addElement(
            'advcheckbox',
            'config_todolist_course_info_due_date',
            get_string('course_due_date', 'block_enrol_accordion'),
            null,
            ['group' => 1]
        );
        $form->hideIf('config_todolist_course_info_due_date', 'config_carouseltype', 'neq', constants::BKC_TODOLIST);
        $form->addElement(
            'advcheckbox',
            'config_todolist_course_info_coursecompletion_date',
            get_string('course_completiondate', 'block_enrol_accordion'),
            null,
            ['group' => 1]
        );
        $form->hideIf(
            'config_todolist_course_info_coursecompletion_date',
            'config_carouseltype',
            'neq',
            constants::BKC_TODOLIST
        );

        $datemodes = [
            block_enrol_accordion::BLOCK_ENROL_ACCORDION_USE_MINDATES => get_string(
                'usemindates',
                'block_enrol_accordion'
            ),
            block_enrol_accordion::BLOCK_ENROL_ACCORDION_USE_MAXDATES => get_string(
                'usemaxdates',
                'block_enrol_accordion'
            ),
        ];
        $form->addElement(
            'select',
            'config_todolist_datemode',
            get_string('datemode', 'block_enrol_accordion'),
            $datemodes
        );
        $form->hideIf('config_todolist_datemode', 'config_carouseltype', 'neq', constants::BKC_TODOLIST);

        // default states of each accordion
        $default_modes = [
            block_enrol_accordion::BLOCK_ENROL_ACCORDION_HIDDEN => get_string(
                'default_hidden',
                'block_enrol_accordion'
            ),
            block_enrol_accordion::BLOCK_ENROL_ACCORDION_EXPANDED => get_string(
                'default_expanded',
                'block_enrol_accordion'
            ),
        ];
        $form->addElement(
            'select',
            'config_todolist_accordion_state_completion_overdue',
            get_string('completion_section', 'block_enrol_accordion'),
            $default_modes
        );
        $form->setType('config_todolist_accordion_state_completion_overdue', PARAM_INT);
        $form->hideIf(
            'config_todolist_accordion_state_completion_overdue',
            'config_carouseltype',
            'neq',
            constants::BKC_TODOLIST
        );

        $form->addElement(
            'select',
            'config_todolist_accordion_state_enrolled',
            get_string('enrolled_section', 'block_enrol_accordion'),
            $default_modes
        );
        $form->setType('config_todolist_accordion_state_enrolled', PARAM_INT);
        $form->hideIf('config_todolist_accordion_state_enrolled', 'config_carouseltype', 'neq', constants::BKC_TODOLIST);

        $form->addElement(
            'select',
            'config_todolist_accordion_state_suggested',
            get_string('suggested_section', 'block_enrol_accordion'),
            $default_modes
        );
        $form->setType('config_todolist_accordion_state_suggested', PARAM_INT);
        $form->hideIf('config_todolist_accordion_state_suggested', 'config_carouseltype', 'neq', constants::BKC_TODOLIST);

        $catalogue_modes = [
            block_enrol_accordion::BLOCK_ENROL_ACCORDION_HIDDEN => get_string(
                'default_hidden',
                'block_enrol_accordion'
            ),
            block_enrol_accordion::BLOCK_ENROL_ACCORDION_COLLAPSED => get_string(
                'default_collapsed',
                'block_enrol_accordion'
            ),
            block_enrol_accordion::BLOCK_ENROL_ACCORDION_EXPANDED => get_string(
                'default_expanded',
                'block_enrol_accordion'
            ),
            block_enrol_accordion::BLOCK_ENROL_ACCORDION_COLLAPSE_ON_EMPTY => get_string(
                'default_collapse_empty',
                'block_enrol_accordion'
            ),
            block_enrol_accordion::BLOCK_ENROL_ACCORDION_HIDDEN_IF_EMPTY => get_string(
                'default_hidden_if_empty',
                'block_enrol_accordion'
            ),
        ];
        $form->addElement(
            'select',
            'config_todolist_accordion_state_course_catalogue',
            get_string('course_catalogue_section', 'block_enrol_accordion'),
            $catalogue_modes
        );
        $form->setType('config_todolist_accordion_state_course_catalogue', PARAM_INT);
        $form->hideIf(
            'config_todolist_accordion_state_course_catalogue',
            'config_carouseltype',
            'neq',
            constants::BKC_TODOLIST
        );

        // Sort order options
        $sort_order = [
            block_enrol_accordion::BLOCK_ENROL_ACCORDION_SORT_DEFAULT => get_string(
                'sortdefault',
                'block_enrol_accordion'
            ),
            block_enrol_accordion::BLOCK_ENROL_ACCORDION_SORT_ALPHABETICAL => get_string(
                'sortalpha',
                'block_enrol_accordion'
            ),
        ];
        $form->addElement(
            'select',
            'config_todolist_catsort',
            get_string('catsort', 'block_enrol_accordion'),
            $sort_order
        );
        $form->setType('config_todolist_catsort', PARAM_INT);
        $form->hideIf('config_todolist_catsort', 'config_carouseltype', 'neq', constants::BKC_TODOLIST);
        $form->addElement(
            'select',
            'config_todolist_coursesort',
            get_string('coursesort', 'block_enrol_accordion'),
            $sort_order
        );
        $form->setType('config_todolist_coursesort', PARAM_INT);
        $form->hideIf('config_todolist_coursesort', 'config_carouseltype', 'neq', constants::BKC_TODOLIST);
    }
}