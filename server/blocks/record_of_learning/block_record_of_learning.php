<?php
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2010-2013 Totara Learning Solutions LTD
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Alastair Munro <alastair.munro@@totaralms.com>
 * @package totara
 * @subpackage totara_recent_learning
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Record of learning block.
 *
 * Displays courses that are underway.
 */
class block_record_of_learning extends block_base
{

    const COMPONENT = __CLASS__;

    private $nocontent;
    private $body;
    private $count;
    private $include_complete;
    private $show_columns;
    private $sort_column;
    private $sort_ascending;

    public function init()
    {
        $this->title = get_string('recordoflearning', 'block_record_of_learning');
    }

    public function instance_allow_config()
    {
        return true;
    }

    public function specialization()
    {
        $this->body = isset($this->config->body) && !empty($this->config->body['text']) ? $this->config->body['text'] :
            get_string('config_body:default', 'block_record_of_learning');
        $this->count = isset($this->config->count) ? $this->config->count : 5;
        $this->nocontent = isset($this->config->nocontent) && !empty($this->config->nocontent['text']) ?
            $this->config->nocontent['text'] : get_string('config_nocontent:default', 'block_record_of_learning');
        $this->include_complete = isset($this->config->include_complete) && $this->config->include_complete;

        $columns = self::block_record_of_learning_get_columns();

        // When properly configured this array will have boolean values only, but by default it uses the lang string.
        // Which is good, as we want to have a truthy value in here.
        $this->show_columns = isset($this->config->show_columns) && !empty($this->config->show_columns)
            ? $this->config->show_columns
            : $columns;

        // Make sure the sort column sent through is one we have defined in the columns function.
        // If not, set it to null so that we don't start sorting by undefined columns.
        $this->sort_column = isset($this->config->sort_column) && $this->config->sort_column != 'default' && isset($columns[$this->config->sort_column])
            ? $this->config->sort_column
            : null;

        // Default to ascending if anything is wrong with the value, otherwise asc or desc depending on the value.
        if (isset($this->config->sort_direction) && $this->config->sort_direction == false) {
            $this->sort_ascending = false;
        } else {
            $this->sort_ascending = true;
        }
    }

    public function get_content()
    {
        global $USER, $DB, $CFG, $OUTPUT;

        require_once("{$CFG->dirroot}/completion/completion_completion.php");

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->footer = '';

        $completions = completion_info::get_all_courses($USER->id);

        $params = array($USER->id);

        $sql_include = '';
        if (!$this->include_complete) {
            $sql_include =  'AND cc.status < ?';
            $params[] = COMPLETION_STATUS_COMPLETE;
        }

        // Maps the keys from block_record_of_learning() to the database table and column names.
        $map = array(
            'course' => 'c.fullname',
            'status' => 'cc.status',
            'enrolled' => 'cc.timeenrolled',
            'started' => 'cc.timestarted',
            'completed' => 'cc.timecompleted'
        );

        // Set the default sort here.
        $sort = 'ORDER BY c.fullname ASC';
        if ($this->sort_column !== null) {
            $sort = sprintf("ORDER BY %s %s", $map[$this->sort_column], ($this->sort_ascending) ? 'ASC' : 'DESC');
        }

        $sql = "SELECT c.id, c.fullname, cc.timeenrolled, cc.timestarted, cc.timecompleted, cc.status
                  FROM {user_enrolments} ue
             LEFT JOIN {enrol} e ON ue.enrolid = e.id
             LEFT JOIN {course} c ON e.courseid = c.id
             LEFT JOIN {course_completions} cc ON cc.course = c.id AND cc.userid = ue.userid
                 WHERE ue.userid = ? {$sql_include}
              GROUP BY c.id, c.fullname, cc.timeenrolled, cc.timestarted, cc.timecompleted, cc.status {$sort}";

        $courses = $DB->get_records_sql($sql, $params);

        if (isset($this->config->linklocation) && $this->config->linklocation == 'my') {
            $footer = html_writer::link(
                new moodle_url('/my/'),
                get_string('gotomy', 'block_record_of_learning')
            );
        } elseif (isset($this->config->linklocation) && $this->config->linklocation == 'rol.all') {
            $footer = html_writer::link(
                new moodle_url('/totara/plan/record/courses.php', ['userid' => $USER->id, 'status' => 'all']),
                get_string('gotolearning', 'block_record_of_learning')
            );
        } else {
            $footer = html_writer::link(new moodle_url('/totara/plan/record/courses.php',
                array('userid' => $USER->id, 'status' => 'active')), get_string('gotolearning', 'block_record_of_learning'));
        }
        $this->content->footer = $footer;

        if (!$courses) {
            $this->content->text = format_text($this->nocontent, $this->config->nocontent['format'] ?? FORMAT_HTML);
        } else {
            $this->content->text = format_text($this->body, $this->config->body['format'] ?? FORMAT_HTML);

            $table = new html_table();
            $table->attributes['class'] = 'record_of_learning generaltable';

            foreach (self::block_record_of_learning_get_columns() as $column => $string) {
                if ($this->column_shown($column)) {
                    $table->head[] = $string;
                }
            }

            $i = 0;
            foreach ($courses as $course) {
                if (++$i > $this->count) {
                    break;
                }

                $id = $course->id;
                $cells = array();

                if ($this->column_shown('course')) {
                    $name = $course->fullname;
                    $link = html_writer::link(new moodle_url('/course/view.php', array('id' => $id)), $name, array('title' => $name));
                    $cell = new html_table_cell($link);
                    $cell->attributes['class'] = 'course';
                    $cells[] = $cell;
                }

                if ($this->column_shown('status')) {
                    $status = array_key_exists($id, $completions) ? $completions[$id]->status : null;
                    $completion = totara_display_course_progress_bar($USER->id, $course->id, $status);
                    $cell = new html_table_cell($completion);
                    $cell->attributes['class'] = 'status';
                    $cells[] = $cell;
                }

                $date_format = get_string('strftimedate', 'langconfig');

                if ($this->column_shown('enrolled')) {
                    if ($course->timeenrolled == 0) {
                        $enrolled = '';
                    } else {
                        $enrolled = userdate($course->timeenrolled, $date_format);
                    }
                    $cell = new html_table_cell($enrolled);
                    $cell->attributes['class'] = 'enrolled';
                    $cells[] = $cell;
                }

                if ($this->column_shown('started')) {
                    if ($course->timestarted == 0) {
                        $started = '';
                    } else {
                        $started = userdate($course->timestarted, $date_format);
                    }
                    $cell = new html_table_cell($started);
                    $cell->attributes['class'] = 'started';
                    $cells[] = $cell;
                }

                if ($this->column_shown('completed')) {
                    if ($course->timecompleted == 0) {
                        $completed = '';
                    } else {
                        $completed = userdate($course->timecompleted, $date_format);
                    }
                    $cell = new html_table_cell($completed);
                    $cell->attributes['class'] = 'completed';
                    $cells[] = $cell;
                }

                // Display header in cell attribute for responsive theme.
                foreach ($cells as $index => $cell) {
                    $cell->attributes['data-title'] = $table->head[$index];
                }

                $table->data[] = new html_table_row($cells);
            }
            $this->content->text .= $OUTPUT->render($table);
        }
        return $this->content;
    }

    private function column_shown($name)
    {
        return isset($this->show_columns[$name]) && $this->show_columns[$name];
    }

    public static function block_record_of_learning_get_columns()
    {
        return array(
            'course' => get_string('course'),
            'status' => get_string('status'),
            'enrolled' => get_string('enrolled', 'totara_core'),
            'started' => get_string('started', 'totara_core'),
            'completed' => get_string('completed', 'totara_core')
        );
    }
}