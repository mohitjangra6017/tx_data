<?php
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2010 onwards Totara Learning Solutions LTD
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Aaron Wells <aaronw@catalyst.net.nz>
 * @author Eugene Venter <eugene@catalyst.net.nz>
 * @package totara
 * @subpackage cohort
 */
if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.'); //  It must be included from a Moodle page
}

global $CFG;
require_once($CFG->dirroot.'/cohort/lib.php');

/**
 * A report builder source for cohorts
 */
class rb_source_cohort_members extends rb_base_source {
    use \totara_job\rb\source\report_trait;

    /**
     * Constructor
     * @global object $CFG
     * @param int $groupid (ignored)
     * @param rb_global_restriction_set $globalrestrictionset
     */
    public function  __construct($groupid, rb_global_restriction_set $globalrestrictionset = null) {
        if ($groupid instanceof rb_global_restriction_set) {
            throw new coding_exception('Wrong parameter orders detected during report source instantiation.');
        }

        // Remember the active global restriction set.
        $this->globalrestrictionset = $globalrestrictionset;

        $this->base = '{cohort_members}';
        $this->joinlist = $this->define_joinlist();
        $this->columnoptions = $this->define_columnoptions();
        $this->filteroptions = $this->define_filteroptions();
        $this->contentoptions = $this->define_contentoptions();
        $this->paramoptions = $this->define_paramoptions();
        $this->defaultcolumns = $this->define_defaultcolumns();
        $this->defaultfilters = $this->define_defaultfilters();
        $this->requiredcolumns = array();
        $this->sourcetitle = get_string('sourcetitle', 'rb_source_cohort_members');
        $this->sourcesummary = get_string('sourcesummary', 'rb_source_cohort_members');
        $this->sourcelabel = get_string('sourcelabel', 'rb_source_cohort_members');
        $this->usedcomponents[] = 'totara_cohort';

        // Apply global report restrictions.
        $this->add_global_report_restriction_join('base', 'userid');

        parent::__construct();
    }

    //
    //
    // Methods for defining contents of source
    //
    //

    /**
     * Are the global report restrictions implemented in the source?
     * @return null|bool
     */
    public function global_restrictions_supported() {
        return true;
    }

    /**
     * Creates the array of rb_join objects required for this->joinlist
     *
     * @global object $CFG
     * @return array
     */
    private function define_joinlist() {
        global $CFG;

        $joinlist = array(
            new rb_join(
                'cohort',
                'INNER',
                '{cohort}',
                'base.cohortid = cohort.id',
                REPORT_BUILDER_RELATION_MANY_TO_MANY
            ),
        );

        $this->add_core_user_tables($joinlist, 'base', 'userid');
        $this->add_totara_job_tables($joinlist, 'base', 'userid');

        return $joinlist;
    }

    /**
     * Creates the array of rb_column_option objects required for
     * $this->columnoptions
     *
     * @return array
     */
    protected function define_columnoptions() {
        $columnoptions = array();

        $columnoptions[] = new rb_column_option(
            'cohort', // Which table? Type.
            'name', // Alias for the field.
            get_string('name', 'totara_cohort'), // Name for the column.
            'cohort.name', // Table alias and field name.
            array('joins'=>array('cohort'),
                  'dbdatatype' => 'char',
                  'outputformat' => 'text',
                  'displayfunc' => 'format_string') // Options.
        );
        $columnoptions[] = new rb_column_option(
            'cohort',
            'namelink',
            get_string('namelink', 'totara_cohort'),
            'cohort.name',
            array(
                'displayfunc' => 'cohort_name_link',
                'extrafields' => array(
                    'cohort_id' => 'cohort.id'
                ),
                'joins' => array('cohort')
            )
        );
        $columnoptions[] = new rb_column_option(
            'cohort',
            'idnumber',
            get_string('idnumber', 'totara_cohort'),
            'cohort.idnumber',
            array('joins'=>array('cohort'),
                  'displayfunc' => 'plaintext',
                  'dbdatatype' => 'char',
                  'outputformat' => 'text')
        );
        $columnoptions[] = new rb_column_option(
            'cohort',
            'type',
            get_string('type', 'totara_cohort'),
            'cohort.cohorttype',
            array(
                'displayfunc' => 'cohort_type',
                'joins' => array('cohort')
            )
        );
        $columnoptions[] = new rb_column_option(
            'cohort',
            'startdate',
            get_string('startdate', 'totara_cohort'),
            'cohort.startdate',
            array(
                'displayfunc' => 'nice_date',
                'dbdatatype' => 'timestamp',
                'joins' => array('cohort')
            )
        );
        $columnoptions[] = new rb_column_option(
            'cohort',
            'enddate',
            get_string('enddate', 'totara_cohort'),
            'cohort.enddate',
            array(
                'displayfunc' => 'nice_date',
                'dbdatatype' => 'timestamp',
                'joins' => array('cohort')
            )
        );
        $columnoptions[] = new rb_column_option(
            'cohort',
            'status',
            get_string('status', 'totara_cohort'),
            'cohort.id',
            array(
                'displayfunc' => 'cohort_status',
                'extrafields' => array(
                    'startdate'=>'cohort.startdate',
                    'enddate'=>'cohort.enddate'
                ),
                'joins' => array('cohort')
            )
        );

        $this->add_core_user_columns($columnoptions);
        $this->add_totara_job_columns($columnoptions);

        return $columnoptions;
    }

    /**
     * Creates the array of rb_filter_option objects required for $this->filteroptions
     * @return array
     */
    protected function define_filteroptions() {
        global $CFG;
        $filteroptions = array();
        $filteroptions[] = new rb_filter_option(
            'cohort',
            'name',
            get_string('name', 'totara_cohort'),
            'text'
        );
        $filteroptions[] = new rb_filter_option(
            'cohort',
            'idnumber',
            get_string('idnumber', 'totara_cohort'),
            'text'
        );
        $filteroptions[] = new rb_filter_option(
            'cohort',
            'type',
            get_string('type', 'totara_cohort'),
            'select',
            array(
                'selectchoices' => array(
                    cohort::TYPE_DYNAMIC => get_string('dynamic', 'totara_cohort'),
                    cohort::TYPE_STATIC  => get_string('set', 'totara_cohort'),
                ),
                'simplemode' => true,
            )
        );

        $this->add_core_user_filters($filteroptions);
        $this->add_totara_job_filters($filteroptions, 'base', 'userid');

        return $filteroptions;
    }


    protected function define_defaultcolumns() {
        $defaultcolumns = array(
            array(
                'type' => 'cohort',
                'value' => 'name',
            ),
        array(
                'type' => 'user',
                'value' => 'fullname',
            )
        );
        return $defaultcolumns;
    }

    protected function define_defaultfilters() {
        $defaultfilters = array(
        array(
                'type' => 'user',
                'value' => 'fullname',
                'advanced' => 0,
            ),
        );

        return $defaultfilters;
    }
    /**
     * Creates the array of rb_content_option object required for $this->contentoptions
     * @return array
     */
    protected function define_contentoptions() {
        $contentoptions = array();

        // Add the manager/position/organisation content options.
        $this->add_basic_user_content_options($contentoptions);

        return $contentoptions;
    }

    protected function define_paramoptions() {
        $paramoptions = array(new rb_param_option('cohortid', 'base.cohortid'));

        return $paramoptions;
    }

    /**
     * RB helper function to show the name of the cohort with a link to the cohort's details page.
     *
     * @deprecated Since Totara 12.0
     * @param string $cohortname
     * @param object Report row $row
     * @return string html link
     */
    public function rb_display_cohort_name_link($cohortname, $row) {
        debugging('rb_source_cohort_members::rb_display_cohort_name_link has been deprecated since Totara 12.0', DEBUG_DEVELOPER);
        if (empty($cohortname)) {
            return '';
        }
        return html_writer::link(new moodle_url('/cohort/view.php',
            array('id' => $row->cohort_id)), format_string($cohortname));
    }

    /**
     * RB helper function to show whether a cohort is dynamic or static
     *
     * @deprecated Since Totara 12.0
     * @param int $cohorttype
     * @param object $row
     * @return string
     */
    public function rb_display_cohort_type($cohorttype, $row ) {
        debugging('rb_source_cohort_members::rb_display_cohort_type has been deprecated since Totara 12.0', DEBUG_DEVELOPER);
        global $CFG;
        require_once($CFG->dirroot.'/cohort/lib.php');

        switch( $cohorttype ) {
            case cohort::TYPE_DYNAMIC:
                $ret = get_string('dynamic', 'totara_cohort');
                break;
            case cohort::TYPE_STATIC:
                $ret = get_string('set', 'totara_cohort');
                break;
            default:
                $ret = get_string('typeunknown', 'totara_cohort', $cohorttype);
        }
        return $ret;
    }

    /**
     * RB helper function to show the "action" links for a cohort -- edit/clone/delete
     *
     * @deprecated Since Totara 12.0
     * @param int $cohortid
     * @param object $row
     * @return string|string
     */
    public function rb_display_cohort_actions($cohortid, $row ) {
        debugging('rb_source_cohort_members::rb_display_cohort_actions has been deprecated since Totara 12.0', DEBUG_DEVELOPER);
        global $OUTPUT;

        static $canedit = null;
        if ($canedit === null) {
            $canedit = has_capability('moodle/cohort:manage', context_system::instance());
        }

        if ($canedit) {
            $editurl = new moodle_url('/cohort/edit.php', array('id' => $cohortid));
            $str = html_writer::link($editurl, $OUTPUT->pix_icon('t/edit', get_string('edit')));
            $cloneurl = new moodle_url('/cohort/view.php', array('id' => $cohortid, 'clone' => 1, 'cancelurl' => qualified_me()));
            $str .= html_writer::link($cloneurl, $OUTPUT->pix_icon('t/copy', get_string('copy', 'totara_cohort')));
            $delurl = new moodle_url('/cohort/view.php', array('id'=>$cohortid, 'delete' => 1, 'cancelurl' => qualified_me()));
            $str .= html_writer::link($delurl, $OUTPUT->pix_icon('t/delete', get_string('delete')));

            return $str;
        }
        return '';
    }

    /**
     * Display cohort status
     *
     * @deprecated Since Totara 12.0
     * @param $cohortid
     * @param $row
     * @return string
     */
    public function rb_display_cohort_status($cohortid, $row) {
        debugging('rb_source_cohort_members::rb_display_cohort_status has been deprecated since Totara 12.0', DEBUG_DEVELOPER);
        $now = time();
        if (totara_cohort_is_active($row, $now)) {
            return get_string('cohortdateactive', 'totara_cohort');
        }

        if ($row->startdate && $row->startdate > $now) {
            return get_string('cohortdatenotyetstarted', 'totara_cohort');
        }

        if ($row->enddate && $row->enddate < $now) {
            return get_string('cohortdatealreadyended', 'totara_cohort');
        }

        return '';
    }
}

