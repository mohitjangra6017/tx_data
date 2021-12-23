<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2019 Kineo
 */

namespace mod_assessment\rb\display;

use context_system;
use html_writer;
use mod_assessment\helper\role;
use mod_assessment\model\assessment;
use mod_assessment\model\attempt;
use moodle_url;
use rb_column;
use reportbuilder;
use stdClass;
use totara_reportbuilder\rb\display\base;

class assessment_view_summary extends base
{

    public static function display($value, $format, stdClass $row, rb_column $column, reportbuilder $report): string
    {
        global $USER;

        $extra = self::get_extrafields_row($row, $column);

        if (empty($extra->userid)) {
            return '';
        }

        $assessment = assessment::instance(['id' => $value]);
        $archived = (bool)$report->get_param_value('archived');
        $activerole = role::get_assessment_role($assessment, $USER->id, $extra->userid, $archived);

        $url = new moodle_url('/mod/assessment/view.php', ['id' => $assessment->get_cmid(), 'userid' => $extra->userid]);
        $labelkey = 'viewsummary';
        $a = '';
        $returnempty = false;

        if ($archived) {
            $url->param('archived', $archived);
        }

        // Evaluators and Reviewers can click through to the summary.
        if (!in_array($activerole, [role::EVALUATOR, role::REVIEWER])) {
            $returnempty = true;

            // Admins can view attempt as an evaluator, if there is a valid one assigned and it is completed.
            if ($activerole == role::ADMIN && has_capability('mod/assessment:viewasanotherrole', context_system::instance())) {
                if ($attempts = attempt::instances_for_user($extra->userid, $assessment->id, null, null, $archived)) {
                    $latest = array_pop($attempts);
                    if ($latest->is_complete() && $latest->is_evaluator_valid()) {
                        $url->param('viewasrole', role::EVALUATOR);
                        $labelkey = 'viewsummaryasx';
                        $a = get_string('roleevaluator', 'assessment');
                        $returnempty = false;
                    }
                }
            }
        }

        if ($returnempty) {
            return '';
        }

        return html_writer::link(
            $url, get_string($labelkey, 'rb_source_assessment', $a),
            ['class' => 'btn btn-primary link-as-button']
        );
    }

}
