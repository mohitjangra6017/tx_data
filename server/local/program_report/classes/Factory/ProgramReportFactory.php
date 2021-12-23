<?php
/**
 * Program Embedded Report
 *
 * @package   local_program_report
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2019 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace local_program_report\Factory;

use rb_config;
use rb_global_restriction_set;
use reportbuilder;
use stdClass;

defined('MOODLE_INTERNAL') || die;

class ProgramReportFactory
{
    public static function create($programId): reportbuilder
    {
        global $DB;

        $shortName = 'prog_courseset_completion';

        if ($templateReport = $DB->get_record('report_builder', ['shortname' => $shortName, 'embedded' => 1])) {

            $DB->delete_records('report_builder_columns', ['reportid' => $templateReport->id, 'type' => 'coursesetcompletion']);

            $sort = $DB->get_field('report_builder_columns', 'max(sortorder) id', ['reportid' => $templateReport->id]);

            $coursesetSQL = <<<SQL
SELECT pcc.*, pc.label, p.fullname progname, c.fullname coursename, c.id as courseid, pc.certifpath, p.certifid
FROM {prog_courseset} pc
INNER JOIN {prog_courseset_course} pcc
    ON pcc.coursesetid = pc.id
INNER JOIN {prog} p
    ON p.id = pc.programid
INNER JOIN {course} c
    ON c.id = pcc.courseid
WHERE p.id = ?
ORDER BY p.sortorder, programid, certifpath, pc.sortorder, pcc.id
SQL;

            $coursesetCourses = $DB->get_records_sql($coursesetSQL, [ $programId ]);
            foreach($coursesetCourses as $coursesetCourse) {
                if (empty($coursesetCourse->certifid)) {
                    $prefix = get_string('prefix:program', 'rb_source_prog_courseset_completion');
                } elseif ($coursesetCourse->certifpath == 1) {
                    $prefix = get_string('prefix:certification', 'rb_source_prog_courseset_completion');
                } else {
                    $prefix = get_string('prefix:recertification', 'rb_source_prog_courseset_completion');
                }

                $columnName = "({$prefix}) - {$coursesetCourse->label} - {$coursesetCourse->coursename}";

                $toDB = new stdClass();
                $toDB->reportid = $templateReport->id;
                $toDB->type = 'coursesetcompletion';
                $toDB->value = 'pc' . $coursesetCourse->id;
                $toDB->heading = $columnName;
                $toDB->sortorder = ++$sort;
                $toDB->hidden = 0;
                $toDB->customheading = 1;

                $DB->insert_record('report_builder_columns', $toDB);
            }
        }

        $globalRestrictionSet = rb_global_restriction_set::create_from_page_parameters($templateReport);

        return reportbuilder::create_embedded($shortName, (new rb_config())
            ->set_embeddata(['programid' => $programId])
            ->set_nocache(true)
            ->set_sid(0)
            ->set_global_restriction_set($globalRestrictionSet)
        );
    }
}