<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

defined('MOODLE_INTERNAL') || die();

class rb_assessment_version_assignment_direct_embedded extends rb_base_embedded
{
    public function __construct()
    {
        $this->url = '/mod/assessment/admin/assignments/version/directview.php';
        $this->source = 'assessment_version_assignment';
        $this->shortname = 'assessment_version_assignment_direct';
        $this->fullname = $this->get_report_string('directassignments');

        $this->columns = [
            ['type' => 'learner', 'value' => 'fullname', 'heading' => $this->get_report_string('learnerfullname')],
            ['type' => 'roleuser', 'value' => 'fullname', 'heading' => $this->get_report_string('roleuserfullname')],
            ['type' => 'assessment_version_assignment', 'value' => 'role', 'heading' => get_string('role', 'rb_source_assessment_version_assignment')],
        ];

        $this->filters = [
            ['type' => 'learner', 'value' => 'fullname'],
            ['type' => 'roleuser', 'value' => 'fullname'],
        ];

        $this->contentmode = REPORT_BUILDER_CONTENT_MODE_ALL;
        $this->contentsettings = [
            'user_visibility' => [
                'enable' => 1,
            ]
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
        return get_string($identifier, 'rb_source_assessment_version_assignment');
    }

    /**
     * @return bool
     */
    public function embedded_global_restrictions_supported(): bool
    {
        return false;
    }
}
