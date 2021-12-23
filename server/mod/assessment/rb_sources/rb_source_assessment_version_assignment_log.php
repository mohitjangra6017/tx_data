<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

use mod_assessment\entity\assessment_version_assignment_log;
use mod_assessment\model\import_error;
use mod_assessment\rb\source\version_assignment_trait;


global $CFG;
require_once($CFG->dirroot . '/totara/reportbuilder/classes/rb_base_source.php');

class rb_source_assessment_version_assignment_log extends rb_base_source
{

    use version_assignment_trait;

    public function __construct()
    {

        $this->base = $this->define_base();
        $this->joinlist = $this->define_joinlist();
        $this->columnoptions = $this->define_columnoptions();
        $this->filteroptions = $this->define_filteroptions();
        $this->paramoptions = $this->define_paramoptions();
        $this->contentoptions = $this->define_contentoptions();

        $this->sourcetitle = $this->get_report_string('sourcetitle');
        $this->sourcesummary = get_string('sourcesummary', 'rb_source_assessment_version_assignment_log');
        $this->sourcelabel = get_string('sourcelabel', 'rb_source_assessment_version_assignment_log');
        $this->usedcomponents[] = 'mod_assessment';

        parent::__construct();
    }

    public function global_restrictions_supported(): bool
    {
        return false;
    }

    protected function define_base(): string
    {
        return "{" . assessment_version_assignment_log::get_tablename() . "}";
    }

    protected function define_columnoptions(): array
    {
        $columnoptions = parent::define_columnoptions();

        $columnoptions[] = new rb_column_option(
            'assessment_version_assignment',
            'importid',
            $this->get_report_string('importid'),
            "base.importid",
            ['displayfunc' => 'integer']
        );

        $columnoptions[] = new rb_column_option(
            'assessment_version_assignment',
            'csvrow',
            $this->get_report_string('csvrow'),
            "base.csvrow",
            ['displayfunc' => 'integer']
        );

        $columnoptions[] = new rb_column_option(
            'assessment_version_assignment',
            'useridraw',
            $this->get_report_string('useridraw'),
            "base.useridraw",
            ['displayfunc' => 'format_text']
        );

        $columnoptions[] = new rb_column_option(
            'assessment_version_assignment',
            'learneridraw',
            $this->get_report_string('learneridraw'),
            "base.learneridraw",
            ['displayfunc' => 'format_text']
        );

        $columnoptions[] = new rb_column_option(
            'assessment_version_assignment',
            'confirmed',
            $this->get_report_string('confirmed'),
            "CASE WHEN base.timeconfirmed IS NULL THEN 0 ELSE 1 END",
            ['displayfunc' => 'yes_or_no']
        );

        $columnoptions[] = new rb_column_option(
            'assessment_version_assignment',
            'skipped',
            $this->get_report_string('skipped'),
            "base.skipped",
            ['displayfunc' => 'yes_or_no']
        );

        $columnoptions[] = new rb_column_option(
            'assessment_version_assignment',
            'timecreated',
            $this->get_report_string('timecreated'),
            "base.timecreated",
            ['displayfunc' => 'nice_datetime']
        );

        $columnoptions[] = new rb_column_option(
            'assessment_version_assignment',
            'timeconfirmed',
            $this->get_report_string('timeconfirmed'),
            "base.timeconfirmed",
            ['displayfunc' => 'nice_datetime']
        );

        $columnoptions[] = new rb_column_option(
            'assessment_version_assignment',
            'errorcode',
            $this->get_report_string('errorcode'),
            "base.errorcode",
            ['displayfunc' => 'error_code']
        );

        $this->add_version_assignment_columns($columnoptions, 'base');

        $this->add_core_user_columns($columnoptions, 'roleuser', 'roleuser', true);
        $this->add_core_user_columns($columnoptions, 'learner', 'learner', true);

        return $columnoptions;
    }

    protected function define_filteroptions(): array
    {
        $filteroptions = parent::define_filteroptions();

        $filteroptions[] = new rb_filter_option(
            'assessment_version_assignment',
            'importid',
            $this->get_report_string('importid'),
            'number'
        );

        $filteroptions[] = new rb_filter_option(
            'assessment_version_assignment',
            'csvrow',
            $this->get_report_string('csvrow'),
            'number'
        );

        $filteroptions[] = new rb_filter_option(
            'assessment_version_assignment',
            'useridraw',
            $this->get_report_string('useridraw'),
            'text'
        );

        $filteroptions[] = new rb_filter_option(
            'assessment_version_assignment',
            'learneridraw',
            $this->get_report_string('learneridraw'),
            'text'
        );

        $filteroptions[] = new rb_filter_option(
            'assessment_version_assignment',
            'confirmed',
            $this->get_report_string('confirmed'),
            'select',
            [
                'selectchoices' => $this->rb_filter_yesno_list(),
                'simplemode' => true,
            ]
        );

        $filteroptions[] = new rb_filter_option(
            'assessment_version_assignment',
            'skipped',
            $this->get_report_string('skipped'),
            'select',
            [
                'selectchoices' => $this->rb_filter_yesno_list(),
                'simplemode' => true,
            ]
        );

        $filteroptions[] = new rb_filter_option(
            'assessment_version_assignment',
            'errorcode',
            $this->get_report_string('errorcode'),
            'select',
            [
                'selectchoices' => import_error::get_errors()
            ]
        );

        $filteroptions[] = new rb_filter_option(
            'assessment_version_assignment',
            'timeconfirmed',
            $this->get_report_string('timeconfirmed'),
            'date'
        );

        $filteroptions[] = new rb_filter_option(
            'assessment_version_assignment',
            'timecreated',
            $this->get_report_string('timecreated'),
            'date'
        );

        $this->add_version_assignment_filters($filteroptions);

        $this->add_core_user_filters($filteroptions, 'roleuser', true);
        $this->add_core_user_filters($filteroptions, 'learner', true);

        return $filteroptions;
    }

    protected function define_contentoptions()
    {
        $contentoptions = [];
        $this->add_basic_user_content_options($contentoptions, 'learner');

        return $contentoptions;
    }


    protected function define_joinlist(): array
    {
        $joinlist = [];

        $this->add_core_user_tables($joinlist, 'base', 'userid', 'roleuser');
        $this->add_core_user_tables($joinlist, 'base', 'learnerid', 'learner');

        return $joinlist;
    }

    protected function define_paramoptions(): array
    {
        $paramoptions = parent::define_paramoptions();
        $paramoptions[] = new rb_param_option('importid', 'base.importid');

        return $paramoptions;
    }

    private function get_report_string(string $identifier): string
    {
        return get_string($identifier, 'rb_source_assessment_version_assignment_log');
    }

    public function phpunit_column_test_expected_count($columnoption): int
    {
        return 0;   // No test data loaded, so no results expected.
    }
}
