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

require_once($CFG->dirroot . '/totara/program/lib.php');
require_once($CFG->dirroot . '/totara/core/js/lib/setup.php');

defined('MOODLE_INTERNAL') || die;

/**
 * Required learning block.
 *
 * Displays learning assigned to the user from programs and certifications.
 */
class block_required_learning extends block_base
{
    private $body;

    private $count;

    private $nocontent;

    private $show;

    private $show_program;

    private $show_rl_button;

    private $show_header;

    private $show_date;

    private $date_format;

    public function init()
    {
        $this->title   = get_string('requiredlearning', 'block_required_learning');
    }

    public function instance_allow_config(): bool
    {
        return true;
    }

    public function specialization()
    {
        $this->count = $this->config->count ?? 5;
        $this->show = $this->config->show ?? 'prog';
        $this->show_program = $this->config->show_program ?? false;
        $this->show_rl_button = $this->config->show_rl_button ?? 1;
        $this->show_header = $this->config->show_header ?? 0;
        $this->show_date = $this->config->show_date ?? 1;
        $this->date_format = $this->config->date_format ?? 'strftimedatetime';

        $this->body = isset($this->config->body)
            ? format_text($this->config->body['text'], $this->config->body['format'])
            : get_string('config_body:default', 'block_required_learning');

        $this->nocontent = isset($this->config->nocontent)
            ? format_text($this->config->nocontent['text'], $this->config->nocontent['format'])
            : get_string('config_nocontent:default', 'block_required_learning');
    }

    public function get_content()
    {
        global $USER, $DB, $OUTPUT;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->items = [];
        $this->content->icons = [];
        $this->content->footer = '';

        $progresstype = isset($this->config->progresstype) ? $this->config->progresstype : 'default';

        $programs = prog_get_required_programs($USER->id, '', '', '', false, false, false);

        if ($this->show_rl_button) {
            $this->content->footer = html_writer::link(new moodle_url('/totara/program/required.php', ['userid' => $USER->id]),
                get_string('gotolearning', 'block_required_learning'));
        }

        if (!$programs) {
            $this->content->text = $this->nocontent;
            return $this->content;
        }

        $this->content->text = $this->body;

        $table = new html_table();
        $table->data = [];
        $table->attributes['class'] = 'required_learning generaltable';
        $this->confirm_show_header_setting($table);


        if ($this->show == 'prog') {
            $i = 0;
            foreach ($programs as $p) {
                if (++$i > $this->count) {
                    break;
                }
                $program = new program($p->id);
                if (prog_is_accessible($p)) {
                    $id = $p->id;
                    $name = $program->fullname;
                    $completion = $this->get_progress($p->id, $USER->id, $progresstype);
                    $duedate = $this->confirm_overdue_datetime_setting($p);
                    $link = html_writer::link(
                        new moodle_url('/totara/program/view.php', ['id' => $id]),
                        $name,
                        ['title' => $name]
                    );
                    $cell1 = new html_table_cell($link);
                    $cell1->attributes['class'] = 'program';
                    $cell2 = new html_table_cell($completion);
                    $cell2->attributes['class'] = 'status';
                    $cell3 = new html_table_cell($duedate);
                    $cell3->attributes['class'] = 'duedate';
                    $table->data[] = new html_table_row([$cell1, $cell3, $cell2]);
                }
            }
        } else if ($this->show == 'course') {
            $i = 0;
            foreach ($programs as $p) {

                if (!prog_is_accessible($p)) {
                    continue;
                }

                $used_courses = [];

                $program = new program($p->id);
                $coursesets = $program->get_content()->get_course_sets();

                if ($this->show_program) {
                    $program_cell = new html_table_cell($program->fullname);
                    $program_cell->colspan = 3;
                    $program_row = new html_table_row([$program_cell]);
                    $program_row->attributes['class'] = 'wrapper';
                }

                $rows = [];

                foreach ($coursesets as $courseset) {
                    /** @var course_set $courseset */

                    // Don't show any courses from a completed course set.
                    if ($courseset->is_courseset_complete($USER->id)) {
                        continue;
                    }

                    foreach ($courseset->get_courses() as $course) {

                        // This course is already showing for this program, don't show it again.
                        if (isset($used_courses[$course->id])) {
                            continue;
                        }

                        if (++$i > $this->count) {
                            continue;
                        }

                        $used_courses[$course->id] = $course->id;

                        $completion = $DB->get_field(
                            'course_completions',
                            'status',
                            ['userid' => $USER->id, 'course' => $course->id]
                        );

                        if (!$completion) {
                            $completion = COMPLETION_STATUS_NOTYETSTARTED;
                        }

                        $icon = totara_display_course_progress_bar($USER->id, $course->id, $completion);

                        $duedate = $this->confirm_overdue_datetime_setting($p);

                        $link = html_writer::link(
                            new moodle_url('/course/view.php', ['id' => $course->id]),
                            $course->fullname,
                            ['title' => $course->fullname]
                        );

                        $cell1 = new html_table_cell($link);
                        $cell1->attributes['class'] = 'program course';
                        $cell2 = new html_table_cell($icon);
                        $cell2->attributes['class'] = 'status';
                        $cell3 = new html_table_cell($duedate);
                        $cell3->attributes['class'] = 'duedate';
                        $rows[] = new html_table_row([$cell1, $cell3, $cell2]);
                    }
                }

                // Make sure we add the program name row first, then add the program course rows.
                if (!empty($rows)) {
                    if ($this->show_program) {
                        $table->data[] = $program_row;
                    }
                    $table->data = array_merge($table->data, $rows);
                }
            }
        }

        $this->content->text .= $OUTPUT->render($table);

        return $this->content;
    }

    /**
     * @param $programid
     * @param $userid
     * @param $type
     * @return string|void
     */
    protected function get_progress($programid, $userid, $type): string
    {
        global $PAGE;

        switch ($type) {
            case 'radial':
                local_js();
                $PAGE->requires->js(new moodle_url('/blocks/required_learning/static/radial.js'));

                $value = (int)prog_display_progress($programid, $userid, CERTIFPATH_CERT, true);
                $progress = $this->get_progress_radial($value);
                break;
            default:
                $progress = prog_display_progress($programid, $userid);
                break;
        }
        return $progress;
    }

    /**
     * Reference: https://medium.com/@andsens/radial-progress-indicator-using-css-a917b80c43f9#.86vmxmdvx
     * Reference: http://jsfiddle.net/andsens/mLA7X/
     * @param $value
     * @return string
     */
    protected function get_progress_radial($value): string
    {
        global $CFG;

        $data = file_get_contents($CFG->dirroot . '/blocks/required_learning/progress/radial.html');
        return str_replace(
            '%PROGRESS%',
            $value,
            $data
        );
    }

    /**
     * @return array
     */
    public function html_attributes(): array
    {
        $attributes = parent::html_attributes();

        if (isset($this->config->progresstype) && $this->config->progresstype == 'radial') {
            $attributes['class'] .= ' radial-progress-bar';
        }

        return $attributes;
    }

    /**
     * @param $p
     * @return lang_string|string
     * @throws coding_exception
     */
    protected function confirm_overdue_datetime_setting($p)
    {
        global $OUTPUT;

        if (empty($p->duedate) || $p->duedate == COMPLETION_TIME_NOT_SET) {
            $duedate = get_string('duedatenotset', 'totara_program');
        } elseif ($this->show_date && $p->duedate < time()) {
            $duedate = userdate($p->duedate, get_string($this->date_format, 'langconfig'), 99, false).
                $OUTPUT->notification(get_string('overdue', 'totara_plan'), 'notifyproblem');
        } elseif (!$this->show_date && $p->duedate < time()) {
            $duedate = $OUTPUT->notification(get_string('overdue', 'totara_plan'), 'notifyproblem');
        } else {
            $duedate = userdate($p->duedate, get_string($this->date_format, 'langconfig'), 99, false);
        }

        return $duedate;
    }

    /**
     * @param $table
     * @throws coding_exception
     */
    protected function confirm_show_header_setting($table)
    {
        if ($this->show_header == 1) {
            if ($this->show == 'course' && !$this->show_program) {
                $table->head = [get_string('required_learning:course', 'block_required_learning')];
            } elseif ($this->show == 'course' && $this->show_program) {
                $table->head = [get_string('required_learning:progandcourse', 'block_required_learning')];
            } else {
                $table->head = [get_string('required_learning:program', 'block_required_learning')];
            }
            $table->head[] = get_string('required_learning:duedate', 'block_required_learning');
            $table->head[] = get_string('required_learning:progress', 'block_required_learning');
        }
    }
}
