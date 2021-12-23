<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

use mod_assessment\entity\assessment_version_assignment;
use mod_assessment\rb\source\version_assignment_trait;

global $CFG;
require_once($CFG->dirroot . '/totara/reportbuilder/classes/rb_base_source.php');

class rb_source_assessment_version_assignment extends rb_base_source
{

    use version_assignment_trait;

    public function __construct()
    {
        $this->base = $this->define_base();
        $this->joinlist = $this->define_joinlist();
        $this->columnoptions = $this->define_columnoptions();
        $this->filteroptions = $this->define_filteroptions();
        $this->contentoptions = $this->define_contentoptions();
        $this->paramoptions = $this->define_paramoptions();
        $this->sourcetitle = $this->get_report_string('sourcetitle');
        $this->sourcesummary = get_string('sourcesummary', 'rb_source_assessment_version_assignment');
        $this->sourcelabel = get_string('sourcelabel', 'rb_source_assessment_version_assignment');

        $this->usedcomponents[] = 'mod_assessment';

        parent::__construct();
    }

    public function global_restrictions_supported(): bool
    {
        return false;
    }

    protected function define_base(): string
    {
        return "{" . assessment_version_assignment::get_tablename() . "}";
    }

    protected function define_joinlist(): array
    {
        $joinlist = [];

        $this->add_core_user_tables($joinlist, 'base', 'userid', 'roleuser');
        $this->add_core_user_tables($joinlist, 'base', 'learnerid', 'learner');

        return $joinlist;
    }

    protected function define_columnoptions(): array
    {
        $columnoptions = parent::define_columnoptions();

        $this->add_version_assignment_columns($columnoptions, 'base');
        $this->add_core_user_columns($columnoptions, 'roleuser', 'roleuser', true);
        $this->add_core_user_columns($columnoptions, 'learner', 'learner', true);

        return $columnoptions;
    }

    protected function define_filteroptions(): array
    {
        $filteroptions = parent::define_filteroptions();

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


    protected function define_paramoptions(): array
    {
        $paramoptions = parent::define_paramoptions();
        $paramoptions[] = new rb_param_option('role', "base.role");
        $paramoptions[] = new rb_param_option('versionid', "base.versionid");

        return $paramoptions;
    }

    private function get_report_string(string $identifier): string
    {
        return get_string($identifier, 'rb_source_assessment_version_assignment');
    }

    public function phpunit_column_test_expected_count($columnoption): int
    {
        return 0;   // No test data loaded, so no results expected.
    }

}
