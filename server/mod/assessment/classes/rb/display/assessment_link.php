<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2019 Kineo
 */

namespace mod_assessment\rb\display;

use html_writer;
use moodle_url;
use rb_column;
use reportbuilder;
use stdClass;
use totara_reportbuilder\rb\display\base;

class assessment_link extends base
{

    public static function display($value, $format, stdClass $row, rb_column $column, reportbuilder $report): string
    {
        $isexport = ($format !== 'html');
        if ($isexport) {
            return $value;
        }

        $extra = self::get_extrafields_row($row, $column);
        return html_writer::link(new moodle_url('/mod/assessment/view.php', ['id' => $extra->cmid, 'userid' => $extra->userid]), $value);
    }

}
