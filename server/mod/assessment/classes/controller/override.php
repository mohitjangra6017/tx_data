<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

namespace mod_assessment\controller;

defined('MOODLE_INTERNAL') || die();

use context_module;
use core\notification;
use Exception;
use mod_assessment\form;
use mod_assessment\helper;
use mod_assessment\model;
use mod_assessment_renderer;
use moodle_database;
use moodle_page;
use moodle_url;

class override
{

    /** @var model\assessment */
    protected $assessment;

    /** @var moodle_database */
    protected moodle_database $db;

    protected $learnerid;

    /** @var moodle_page */
    protected moodle_page $page;

    /** @var mod_assessment_renderer */
    protected $renderer;
    /**
     * @var form\override
     */
    private form\override $form;

    public function __construct(moodle_page $page, moodle_database $db)
    {
        $this->db = $db;
        $this->page = $page;

        $assessmentid = required_param('assessmentid', PARAM_INT);
        $this->assessment = model\assessment::instance(['id' => $assessmentid], MUST_EXIST);
        $this->learnerid = required_param('userid', PARAM_INT);

        $this->check_permissions();
        $this->setup_page();

        $this->renderer = $this->page->get_renderer('mod_assessment');
        $this->setup_form();
    }

    public function check_permissions()
    {
        global $USER;

        $context = context_module::instance($this->assessment->get_cmid());
        if ($context->is_user_access_prevented()) {
            throw new Exception(get_string('error:noaccess', 'mod_assessment'));
        }
        $role = helper\role::get_assessment_role($this->assessment, $USER->id, $this->learnerid);
        if ($role !== helper\role::EVALUATOR) {
            throw new Exception(get_string('error:notevaluator', 'mod_assessment'));
        }
    }

    public function get_heading()
    {
        return $this->assessment->name;
    }

    public function redirect_overview()
    {
        $overviewurl = new moodle_url(
            '/mod/assessment/view.php',
            ['id' => $this->assessment->get_cmid(), 'userid' => $this->learnerid]
        );
        redirect($overviewurl);
    }

    public function render()
    {
        echo $this->renderer->header();
        echo $this->renderer->heading($this->get_heading());
        echo $this->renderer->render_from_template('mod_assessment/override', $this->to_array());
        echo $this->renderer->footer();
    }

    public function setup_page()
    {
        $cm = get_coursemodule_from_id('assessment', $this->assessment->get_cmid());
        $this->page->set_cm($cm);
        $this->page->set_context(context_module::instance($cm->id));

        $this->page->set_url(new moodle_url(
            '/mod/assessment/override.php',
            ['id' => $this->assessment->get_cmid(), 'assessmentid' => $this->assessment->id, 'userid' => $this->learnerid]
        ));
    }

    public function setup_form()
    {
        $learner = $this->db->get_record('user', ['id' => $this->learnerid]);
        $form = new form\override($this->page->url, ['assessment' => $this->assessment, 'learner' => $learner]);

        if ($form->is_cancelled()) {
            $this->redirect_overview();
        } elseif ($data = $form->get_data()) {
            // Preprocess attempts data.
            $currentattempts = helper\attempt::count_user_attempts($this->assessment, $this->learnerid);
            $attempts = ($data->attempts == helper\attempt::UNLIMITED) ? $data->attempts : $currentattempts + $data->attempts;

            // Save data.
            $override = model\override::make(['assessmentid' => $this->assessment->id, 'userid' => $this->learnerid]);
            $override->set_attempts($attempts)->save();

            // Send success notification.
            $placeholders = (object)[
                'activityname' => $this->assessment->name,
                'attempts' => $override->attempts == helper\attempt::UNLIMITED ? get_string('unlimited') : $override->attempts,
                'fullname' => fullname($learner),
            ];
            notification::success(get_string('notification:extraattemptssaved', 'assessment', $placeholders));

            $this->redirect_overview();
        }

        $this->form = $form;
    }

    public function to_array(): array
    {
        return [
            'contenthead' => get_string('attemptsoverride', 'assessment'),
            'form' => $this->form->render()
        ];
    }

}
