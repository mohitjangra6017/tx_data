<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2019 Kineo
 */

namespace mod_assessment\rb\display;

use mod_assessment\factory\assessment_question_factory;
use mod_assessment\model\question;
use rb_column;
use reportbuilder;
use stdClass;
use totara_reportbuilder\rb\display\base;

class assessment_answer extends base
{

    public static function display($value, $format, stdClass $row, rb_column $column, reportbuilder $report): ?string
    {
        $extra = self::get_extrafields_row($row, $column);
        $question = assessment_question_factory::fetch(['id' => $extra->questionid]);
        return $question->report_display($value, $format, $row, $column, $report);
    }

}
