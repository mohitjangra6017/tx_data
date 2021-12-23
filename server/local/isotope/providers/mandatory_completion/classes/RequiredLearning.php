<?php
/**
 * @package isotopeprovider_mandatory_completion
 * @author Ben Lobo <ben.lobo@kineo.com>
 * @copyright City & Guilds Kineo 2019
 * @license http://www.kineo.com
 */

namespace isotopeprovider_mandatory_completion;

use moodle_url;
use flexible_table;
use context_program;
use html_writer;

defined('MOODLE_INTERNAL') || die;

global $CFG;

class RequiredLearning
{
    const COMPONENT = 'isotopeprovider_mandatory_completion';
    
    private $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Return markup for displaying a table of a specified user's required programs
     * (i.e. programs that have been automatically assigned to the user)
     *
     * This includes hidden programs but excludes unavailable programs
     *
     * @access  public
     * @param   int     $userId     Program assignee
     * @return  string
     */
    public function progDisplayRequiredPrograms($userId = false)
    {
        if (!$userId) {
            $userId = $this->userId;
        }

        $count = prog_get_required_programs($userId, '', '', '', true, true);

        // Set up table
        $tableName = 'progs-list-programs';
        $tableHeaders = array(get_string('programname', 'totara_program'));
        $tableCols = array('progname');

        // Due date
        $tableHeaders[] = get_string('duedate', 'totara_program');
        $tableCols[] = 'duedate';

        // Progress
        $tableHeaders[] = get_string('progress', 'totara_program');
        $tableCols[] = 'progress';

        $baseUrl = new moodle_url('/local/isotope/providers/mandatory_completion/required.php', array('userid' => $userId));

        $table = new flexible_table($tableName);
        $table->define_headers($tableHeaders);
        $table->define_columns($tableCols);
        $table->define_baseurl($baseUrl);
        $table->set_attribute('class', 'fullwidth generalbox');
        $table->set_control_variables(array(
            TABLE_VAR_SORT    => 'tsort',
        ));
        $table->sortable(true);
        $table->no_sorting('progress');

        $table->setup();
        $table->pagesize(15, $count);
        $sort = $table->get_sql_sort();
        $sort = empty($sort) ? '' : ' ORDER BY '.$sort;

        // Add table data
        $programs = prog_get_required_programs($userId, $sort, $table->get_page_start(), $table->get_page_size(), false, true);

        if (!$programs) {
            return '';
        }
        $rowCount = 0;
        foreach ($programs as $p) {
            if (!prog_is_accessible($p)) {
                continue;
            }
            $row = array();
            $row[] = $this->progDisplaySummaryWidget($p);
            $row[] = prog_display_duedate($p->duedate, $p->id, $userId);
            $row[] = prog_display_progress($p->id, $userId);
            $table->add_data($row);
            $rowCount++;
        }

        unset($programs);

        if ($rowCount > 0) {
            //2.2 flexible_table class no longer supports $table->data and echos directly on each call to add_data
            ob_start();
            $table->finish_html();
            $out = ob_get_contents();
            ob_end_clean();
            return $out;
        } else {
            return '';
        }
    }

    /**
     * Return markup for displaying a table of a specified user's certification programs
     * This includes hidden programs but excludes unavailable programs
     *
     * @param   int $userId     Program assignee
     * @return  string
     */
    public function progDisplayCertificationPrograms($userId = false)
    {
        if (!$userId) {
            $userId = $this->userId;
        }

        $count = prog_get_certification_programs($userId, '', '', '', true, true, true);

        // Set up table
        $tableName = 'progs-list-cert';
        $tableHeaders = array(get_string('certificationname', 'totara_program'));
        $tableCols = array('progname');

        // Due date
        $tableHeaders[] = get_string('duedate', 'totara_program');
        $tableCols[] = 'duedate';

        // Progress
        $tableHeaders[] = get_string('progress', 'totara_program');
        $tableCols[] = 'progress';

        $baseUrl = new moodle_url('/local/isotope/providers/mandatory_completion/required.php', array('userid' => $userId));

        $table = new flexible_table($tableName);
        $table->define_headers($tableHeaders);
        $table->define_columns($tableCols);
        $table->define_baseurl($baseUrl);
        $table->set_attribute('class', 'fullwidth generalbox');
        $table->set_control_variables(array(
            TABLE_VAR_SORT    => 'tsort',
        ));
        $table->sortable(true);
        $table->no_sorting('progress');

        $table->setup();
        $table->pagesize(15, $count);
        $sort = $table->get_sql_sort();
        $sort = empty($sort) ? '' : ' ORDER BY '.$sort;

        // Add table data
        $cPrograms = prog_get_certification_programs($userId, $sort, $table->get_page_start(), $table->get_page_size(),
                false, true, true);

        if (!$cPrograms) {
            return '';
        }

        $rowCount = 0;
        foreach ($cPrograms as $cp) {
            if (!prog_is_accessible($cp)) {
                continue;
            }
            $row = array();
            $row[] = $this->progDisplaySummaryWidget($cp);
            if (!empty($cp->timeexpires)) {
                $row[] = prog_display_duedate($cp->timeexpires, $cp->id, $userId, $cp->certifpath, $cp->status);
            } else {
                $row[] = prog_display_duedate($cp->duedate, $cp->id, $userId, $cp->certifpath, $cp->status);
            }
            $row[] = prog_display_progress($cp->id, $userId, $cp->certifpath);
            $table->add_data($row);
            $rowCount++;
        }

        unset($cPrograms);

        if ($rowCount > 0) {
            //2.2 flexible_table class no longer supports $table->data and echos directly on each call to add_data
            ob_start();
            $table->finish_html();
            $out = ob_get_contents();
            ob_end_clean();
            return $out;
        } else {
            return '';
        }
    }

    /**
     * Display widget containing a program name
     *
     * @param \stdClass  $program    A program database record.
     * @return string $out
     */
    public function progDisplaySummaryWidget($program)
    {
        $summary = file_rewrite_pluginfile_urls($program->summary, 'pluginfile.php',
                context_program::instance($program->id)->id, 'totara_program', 'summary', 0);

        $out = '';
        $out .= html_writer::start_tag('div', array('class' => 'cell'));
        $out .= html_writer::tag('h5', $program->fullname);
        $out .= html_writer::end_tag('div');
        $out .= html_writer::start_tag('div', array('class' => 'dp-summary-widget-description'));
        $out .= $summary . html_writer::end_tag('div');

        return $out;
    }

    /**
     * Add lowest levels of breadcrumbs to the page
     *
     * @param integer $userId ID of the required learning's owner
     *
     */
    public function progAddRequiredLearningBaseNavlinks($userId = false) {
        global $PAGE, $DB;

        if (!$userId) {
            $userId = $this->userId;
        }

        $user = $DB->get_record('user', array('id' => $userId));
        if ($user) {
            $PAGE->navbar->add(get_string('xsrequiredlearning', 'totara_program', fullname($user)), new moodle_url('/local/isotope/providers/mandatory_completion/required.php', array('userid' => $userId)));
        } else {
            $PAGE->navbar->add(get_string('unknownusersrequiredlearning', 'totara_program'), new moodle_url('/local/isotope/providers/mandatory_completion/required.php', array('userid' => $userId)));
        }

    }

}