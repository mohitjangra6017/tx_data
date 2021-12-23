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


class block_course_recommendations_renderer extends plugin_renderer_base {

    /**
     * @param $table_data
     * @return bool|string
     * @throws coding_exception
     */
    public function render_recommendations($table_data) {
        global $OUTPUT;

        $table = new html_table();
        $table->attributes = $table_data->attributes;
        $table->head = $table_data->head;
        $table->data = $table_data->data;
        $tableobject = $table->export_for_template($OUTPUT);

        return $OUTPUT->render_from_template('core/table', $tableobject);
    }

    /**
     * @param $course
     * @return string
     * @throws coding_exception
     * @throws moodle_exception
     */
    public function render_action_buttons($course) {
        global $CFG, $OUTPUT;

        $disable = html_writer::link(
            new moodle_url($CFG->wwwroot . '/blocks/course_recommendations/disable.php',
            ['courseid' => $course->id]),
            html_writer::img($OUTPUT->image_url('hide', 'block_course_recommendations'), null),
            [
                'class' => 'action-links disable core-show',
                'title' => get_string('disable', 'block_course_recommendations'),
            ]
        );
        $delete = html_writer::link(
            new moodle_url($CFG->wwwroot . '/blocks/course_recommendations/delete.php',
            ['courseid' => $course->id]),
            html_writer::img($OUTPUT->image_url('delete', 'block_course_recommendations'), null),
            [
                'class' => 'action-links delete core-delete',
                'title' => get_string('delete', 'block_course_recommendations')
            ]
        );
        return $disable . $delete;
    }

    /**
     * @param $courseid
     * @return string
     * @throws coding_exception
     * @throws moodle_exception
     */
    public function render_help_icon($courseid) {
        global $CFG, $OUTPUT;

        $helpicon = new stdClass();
        $helpicon->identifier = 'course_recommenders';
        $helpicon->component = 'block_course_recommendations';

        // first get the help image icon
        $src = $OUTPUT->image_url('help');

        $title = get_string($helpicon->identifier, $helpicon->component);

        if (empty($helpicon->linktext)) {
            $alt = get_string('helpprefix2', '', trim($title, ". \t"));
        } else {
            $alt = get_string('helpwiththis');
        }

        $attributes = [
            'src' => $src,
            'alt' => $alt,
            'class' => 'iconhelp'
        ];

        $output = html_writer::empty_tag('img', $attributes);

        // add the link text if given
        if (!empty($helpicon->linktext)) {
            // the spacing has to be done through CSS
            $output .= $helpicon->linktext;
        }

        // now create the link around it - we need https on loginhttps pages
        $url = new moodle_url(
            $CFG->wwwroot.'/blocks/course_recommendations/course_recommenders.php',
            ['courseid' => $courseid]
        );

        // note: this title is displayed only if JS is disabled, otherwise the link will have the new ajax tooltip
        $title = get_string('helpprefix2', '', trim($title, ". \t"));

        $attributes = [
            'href' => $url,
            'title' => $title,
            'aria-haspopup' => 'true',
            'target'=>'_blank'
        ];
        $output = html_writer::tag('a', $output, $attributes);

        // and finally span
        return html_writer::tag('span', $output , array('class' => 'helptooltip'));
    }
}
