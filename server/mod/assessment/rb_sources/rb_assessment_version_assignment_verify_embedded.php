<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

defined('MOODLE_INTERNAL') || die();

class rb_assessment_version_assignment_verify_embedded extends rb_base_embedded
{
    public function __construct()
    {
        $this->url = '/mod/assessment/admin/assignments/version/confirm.php';
        $this->source = 'assessment_version_assignment_log';
        $this->shortname = 'assessment_version_assignment_verify';
        $this->fullname = $this->get_report_string('roleassignmentpreview');

        $this->columns = [
            ['type' => 'assessment_version_assignment', 'value' => 'importid', 'heading' => $this->get_report_string('importid')],
            ['type' => 'assessment_version_assignment', 'value' => 'csvrow', 'heading' => $this->get_report_string('csvrow')],
            ['type' => 'assessment_version_assignment', 'value' => 'learneridraw', 'heading' => $this->get_report_string('learneridraw')],
            ['type' => 'learner', 'value' => 'fullname', 'heading' => $this->get_report_string('learnerfullname')],
            ['type' => 'assessment_version_assignment', 'value' => 'useridraw', 'heading' => $this->get_report_string('useridraw')],
            ['type' => 'roleuser', 'value' => 'fullname', 'heading' => $this->get_report_string('roleuserfullname')],
            ['type' => 'assessment_version_assignment', 'value' => 'role', 'heading' => get_string('role', 'rb_source_assessment_version_assignment')],
            ['type' => 'assessment_version_assignment', 'value' => 'skipped', 'heading' => $this->get_report_string('skipped')],
            ['type' => 'assessment_version_assignment', 'value' => 'errorcode', 'heading' => $this->get_report_string('errorcode')],
            ['type' => 'assessment_version_assignment', 'value' => 'timecreated', 'heading' => $this->get_report_string('timecreated')],
        ];

        $this->filters = [
            ['type' => 'assessment_version_assignment', 'value' => 'skipped'],
            ['type' => 'assessment_version_assignment', 'value' => 'errorcode'],
        ];

        parent::__construct();
    }

    public function is_capable(int $reportfor, reportbuilder $report): bool
    {
        global $PAGE;
        return has_capability('mod/assessment:editinstance', $PAGE->context);
    }

    private function get_report_string(string $identifier)
    {
        return get_string($identifier, 'rb_source_assessment_version_assignment_log');
    }

    /**
     * @return false
     */
    public function embedded_global_restrictions_supported(): bool
    {
        return false;
    }
}
