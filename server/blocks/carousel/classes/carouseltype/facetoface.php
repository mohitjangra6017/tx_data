<?php

namespace block_carousel\carouseltype;

use block_carousel\constants;
use block_carousel\helper;
use container_course\course;

class facetoface extends base {
    function generate($data) {
        global $DB, $PAGE, $USER;

        $renderer = $PAGE->get_renderer('block_carousel');

        $facetoface = [];

        $limit = !empty($this->config->facetofacelimit) ? $this->config->facetofacelimit : constants::BKC_DEFAULT_RECORD_LIMIT;

        // get course filter if set
        $course_filter = !empty($this->config->mappedtocourse) ? $this->config->mappedtocourse : '';
        if (empty($course_filter)) {
            // try to see if there is a global setting set for this
            $course_filter = get_config('block_carousel', 'mappedtocourse');
        }
        // get f2f filter if set
        $f2f_filter = !empty($this->config->mappedtof2f) ? $this->config->mappedtof2f : '';
        if (empty($f2f_filter)) {
            // try to see if there is a global setting set for this
            $f2f_filter = get_config('block_carousel', 'mappedtof2f');
        }
        // then set set values from user profile or $USER object
        $course_mapped_value = !empty($USER->profile[$course_filter]) ? $USER->profile[$course_filter] : null;
        $f2f_mapped_value = !empty($USER->profile[$f2f_filter]) ? $USER->profile[$f2f_filter] : null;

        $current_time = time();

        // get custom title field id
        $title_select = "";
        $title_join = "";
        if (!empty($this->config->facetofacesessiontitle)) {
            $fieldid = $DB->get_field_select('facetoface_session_info_field', 'id', 'shortname = :title', ['title' => 'title']);
            $title_select = "fsid.data AS sessiontitle,";
            $title_join = "LEFT JOIN {facetoface_session_info_data} fsid
                                  ON fsid.facetofacesessionid = s.id AND fsid.fieldid = $fieldid";
        }

        [$v_sql, $v_params] = totara_visibility_where(null, 'c.id', 'c.visible', 'c.audiencevisible', 'c');

        $sql = "SELECT d.id,
                        c.id AS courseid,
                        c.fullname AS coursename,
                        c.summary,
                        c.format,
                        f.name AS fullname,
                        f.id AS f2fid,
                        s.id AS sessionid,
                        {$title_select}
                        d.sessiontimezone,
                        d.timestart,
                        d.timefinish
                    FROM {facetoface_sessions_dates} d
            INNER JOIN {facetoface_sessions} s ON s.id = d.sessionid
            INNER JOIN {facetoface} f ON f.id = s.facetoface
            INNER JOIN {course} c ON f.course = c.id
            INNER JOIN {facetoface_signups} fs ON fs.sessionid = s.id AND fs.userid = :userid
            INNER JOIN {facetoface_signups_status} fss ON fss.signupid = fs.id AND fss.superceded = :superceded AND fss.statuscode = :booked
                        {$title_join}
                    WHERE s.cancelledstatus = 0
                    AND d.timestart >= {$current_time}
                    AND c.containertype = :containertype
                    AND {$v_sql}
            ORDER BY d.timestart, d.timefinish ASC
                    LIMIT {$limit}
        ";

        // VODHAS-2008
        $params = [
            'booked' => 70, // MDL_F2F_STATUS_BOOKED = 70
            'superceded' => 0,
            'userid' => $USER->id
        ];

        $params['containertype'] = course::get_type();
        $params += $v_params;
        
        $facetoface = $DB->get_records_sql($sql, $params);

        // ['coursefilter','coursemapped_value', 'f2f_filter', 'f2fmapped_value']
        $filter_params = [
            'coursefilter' => $course_filter,
            'coursemapped_value' => $course_mapped_value,
            'f2f_filter' => $f2f_filter,
            'f2fmapped_value' => $f2f_mapped_value
        ];
        foreach ($facetoface as $event) {
            // Check if this facetoface session
            // should be visible to the user based on the 
            // custom field mapping
            if (!helper::is_session_visible_to_user($event->courseid, $event->sessionid, $filter_params)) {
                continue;
            }

            if (isset($event->sessiontitle) && !empty($event->sessiontitle)) {
                $event->fullname = $event->sessiontitle;
            }

            $slide = $this->get_course_slide_data($event, get_string('type:' . constants::BKC_FACETOFACE, 'block_carousel'), $data);
            // clear the custom fields
            // Otherwise it will overload the space
            $slide['custom_fields'][] = userdate($event->timestart, get_string('strftimedayshort'));

            // Prepare F2F related data
            $slide['icon'] = \html_writer::tag('i', null, ['class' => 'far fa-user']);
            $data['slides'][] = $slide;
        }

        $data['title'] = empty($data['title']) ? get_string('type:' . constants::BKC_FACETOFACE, 'block_carousel') : $data['title'];
        return $renderer->render_facetoface($data);
    }

    public static function extend_form(\MoodleQuickForm $form, \stdClass $block_instance)
    {
        $form->addElement(
            'text',
            'config_facetofacelimit',
            get_string('facetofacelimit', 'block_carousel'),
            ['oninput' => "this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"]
        ); // only allow numeric values
        $form->addHelpButton('config_facetofacelimit', 'whatshotlimit', 'block_carousel');
        $form->setType('config_facetofacelimit', PARAM_INT);
        $form->setDefault('config_facetofacelimit', constants::BKC_DEFAULT_RECORD_LIMIT);
        $form->hideIf(
            'config_facetofacelimit',
            'config_carouseltype',
            'neq',
            constants::BKC_FACETOFACE
        );

        $form->addElement(
            'text',
            'config_facetofacesessiontitle',
            get_string('facetofacesessiontitle', 'block_carousel')
        );
        $form->addHelpButton('config_facetofacesessiontitle', 'facetofacesessiontitle', 'block_carousel');
        $form->setType('config_facetofacesessiontitle', PARAM_TEXT);
        $form->hideIf(
            'config_facetofacesessiontitle',
            'config_carouseltype',
            'neq',
            constants::BKC_FACETOFACE
        );

        $form->addElement('text', 'config_mappedtof2f', get_string('mappedtof2f', 'block_carousel'));
        $form->addHelpButton('config_mappedtof2f', 'mappedtof2f', 'block_carousel');
        $form->setType('config_mappedtof2f', PARAM_TEXT);
        $form->hideIf('config_mappedtof2f', 'config_carouseltype', 'neq', constants::BKC_FACETOFACE);

        $form->addElement('text', 'config_mappedtocourse', get_string('mappedtocourse', 'block_carousel'));
        $form->addHelpButton('config_mappedtocourse', 'mappedtocourse', 'block_carousel');
        $form->setType('config_mappedtocourse', PARAM_TEXT);
        $form->hideIf(
            'config_mappedtocourse',
            'config_carouseltype',
            'neq',
            constants::BKC_FACETOFACE
        );
    }

}