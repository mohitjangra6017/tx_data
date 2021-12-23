<?php
/**
 * Program element
 *
 * @package   customcertelement_program
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2019 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace customcertelement_program;

use mod_customcert\element_helper;

defined('MOODLE_INTERNAL') || die;

class element extends \mod_customcert\element
{
    /**
     * @inheritDoc
     */
    public function render($pdf, $preview, $user)
    {
        element_helper::render_content($pdf, $this, $this->renderProgram($preview));
    }

    /**
     * @inheritDoc
     */
    public function render_html()
    {
        global $USER;

        return element_helper::render_html_content($this, $this->renderProgram(true));
    }


    /**
     * Render the program name or the name of the certificate element if preview mode is set
     *
     * @param bool $preview
     * @return string
     */
    protected function renderProgram($preview = false)
    {
        return $preview ? $this->name : $this->getProgram();
    }

    /**
     * Return the name of the program that the certificate activity is within.
     *
     * @return object[]
     */
    protected function getProgram()
    {
        global $DB;

        return $DB->get_field_sql(
            "SELECT p.fullname
               FROM {prog_courseset_course} pcc
               JOIN {prog_courseset} pc ON pc.id = pcc.coursesetid
               JOIN {prog} p ON p.id = pc.programid
              WHERE pcc.courseid = ?
           ORDER BY pc.programid",
            [element_helper::get_courseid($this->get_id())],
            IGNORE_MULTIPLE
        );
    }
}