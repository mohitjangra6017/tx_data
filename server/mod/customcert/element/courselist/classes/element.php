<?php
/**
 * Course list element
 *
 * @package   customcertelement_courselist
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2019 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace customcertelement_courselist;

use html_writer;
use mod_customcert\element_helper;

defined('MOODLE_INTERNAL') || die;

class element extends \mod_customcert\element
{
    /**
     * @inheritDoc
     */
    public function render($pdf, $preview, $user)
    {
        element_helper::render_content($pdf, $this, $this->renderCourses($user, $preview));
    }

    /**
     * @inheritDoc
     */
    public function render_html()
    {
        global $USER;

        return element_helper::render_html_content($this, $this->renderCourses($USER, true));
    }

    /**
     * Render the list of courses or the name of the certificate element if preview mode is set
     *
     * @param object $user
     * @param bool $preview
     * @return string
     */
    protected function renderCourses($user, $preview = false)
    {
        return $preview ? $this->name : implode(html_writer::empty_tag('br'), $this->getCourses($user));
    }

    /**
     * Return the list of the courses that the learner has completed as part of the program that the certificate activity is within.
     *
     * @param object $user
     * @return string[]
     */
    protected function getCourses($user)
    {
        global $DB;

        return $DB->get_fieldset_sql(
            "SELECT c.fullname
               FROM {prog_courseset_course} pcc
               JOIN {prog_courseset} pc ON pc.id = pcc.coursesetid
               JOIN {prog_courseset} pc2 ON pc.programid = pc2.programid
               JOIN {prog_courseset_course} pcc2 ON pcc2.coursesetid = pc2.id
               JOIN {course} c ON c.id = pcc2.courseid
               JOIN {course_completions} cc ON cc.course = c.id AND cc.userid = ?
              WHERE pcc.courseid = ? AND cc.timecompleted > 0
           ORDER BY pc.programid, pc2.sortorder, pcc2.id",
            [$user->id, element_helper::get_courseid($this->get_id())]
        );

    }
}