<?php
/**
 * @copyright   City & Guilds Kineo 2017
 * @author      Steven Hughes <steven.hughes@kineo.com>
 */

namespace mod_assessment\controller;

use coding_exception;
use core\notification;
use mod_assessment\form\extra_attempt;
use mod_assessment\helper\attempt;
use mod_assessment\interfaces\to_array;
use mod_assessment\model\assessment;
use mod_assessment\model\embedded_report;
use mod_assessment\model\form;
use mod_assessment\model\version;
use moodle_database;
use moodle_exception;
use moodle_page;
use moodle_url;
use moodleform;
use stdClass;

class faileddashboard_controller extends dashboard_controller
{

    public const DASHBOARD = 1;
    public const EXTRA_ATTEMPT = 2;

    /**
     * @var moodleform
     */
    private $form;

    /**
     * @var string
     */
    private $template = '';

    private $model;

    private $attemptid = 0;

    private int $mode = self::DASHBOARD;

    public static function get_permission(): string
    {
        return 'mod/assessment:viewfaileddashboard';
    }

    /**
     * faileddashboard_controller constructor.
     *
     * @param moodle_page $page
     * @param moodle_database $db
     * @throws coding_exception
     * @throws moodle_exception
     */
    public function __construct(moodle_page $page, moodle_database $db)
    {

        $this->reportshortname = 'assessment_dashboardfailed';
        $this->title = get_string('navigation:faileddashboard', $this->plugin);

        $this->set_mode();
        parent::__construct($page, $db);

        $this->page->set_url(new moodle_url('/mod/assessment/dashboard.php', ['type' => 'failed']));

        $this->handle_extra_attempts_submission();
    }

    public function render_dashboard()
    {
        echo $this->output->header();

        $context = $this->model->to_array();
        $context['navtabs'] = $this->output->dashboard_navtabs($this->page->url->get_param('type'));
        echo $this->output->render_from_template(
            $this->template,
            $context
        );

        echo $this->output->footer();
    }

    protected function setup()
    {
        parent::setup();

        switch ($this->mode) {
            case static::EXTRA_ATTEMPT:
                list($assessment, $learner) = $this->get_module_and_learner_information();

                $this->form = new extra_attempt(
                    new moodle_url('/mod/assessment/dashboard.php', [
                        'type' => 'failed',
                        'attemptid' => $this->attemptid,
                    ]),
                    [
                        'db' => $this->db,
                        'learner' => $learner,
                        'assessment' => $assessment,
                    ]
                );

                $this->title = get_string('title:allowextraattempt', $this->plugin);
                $this->model = new form($this->title, $this->form);
                $this->template = 'mod_assessment/extraattempt';
                break;
            default:
                $this->title = get_string('navigation:faileddashboard', $this->plugin);
                $this->model = new embedded_report($this->title, $this->report);
                $this->template = 'mod_assessment/dashboard';
                break;
        }
    }

    /**
     * @description Determines whether to show the dashboard, or the extra attempts screen.
     */
    private function set_mode()
    {
        $this->attemptid = optional_param('attemptid', 0, PARAM_INT);

        if ($this->attemptid) {
            $this->mode = static::EXTRA_ATTEMPT;
        } else {
            $this->mode = static::DASHBOARD;
        }
    }

    private function get_module_and_learner_information(): array
    {
        $attempt = \mod_assessment\model\attempt::instance(['id' => $this->attemptid], MUST_EXIST);
        $version = version::instance(['id' => $attempt->versionid], MUST_EXIST);
        $assessment = assessment::instance(['id' => $version->assessmentid], MUST_EXIST);

        if (!$learner = $this->db->get_record('user', ['id' => $attempt->userid])) {
            print_error('error:nolearnerattempts', $this->plugin);
        }

        return [
            $assessment,
            $learner,
        ];
    }

    private function handle_extra_attempts_submission()
    {
        if ($this->mode != static::EXTRA_ATTEMPT) {
            return;
        }

        $faileddashurl = new moodle_url('/mod/assessment/dashboard.php', ['type' => 'failed']);

        if ($this->form->is_submitted() && $data = $this->form->get_data()) {
            list($assessment, $learner) = $this->getModuleAndLearnerInformation();

            $model = \mod_assessment\model\override::make(['assessmentid' => $assessment->id, 'userid' => $learner->id]);

            $todb = new stdClass();
            $todb->userid = $learner->id;
            $todb->assessmentid = $assessment->id;
            if (!isset($data->attempts)) {
                $todb->attempts = attempt::get_max_attempts($assessment, $learner->id);
            } elseif ($data->attempts == attempt::UNLIMITED) {
                $todb->attempts = attempt::UNLIMITED;
            } else {
                $todb->attempts = attempt::count_user_attempts($assessment, $learner->id) + $data->attempts;
            }

            $model->set_data($todb);
            $model->save();

            if ($model->id) {
                $a = new stdClass();
                $a->activityname = $assessment->name;
                $a->fullname = fullname($learner);
                $a->attempts = $todb->attempts == -1 ? get_string('value:unlimited', $this->plugin) : $todb->attempts;

                // Do not set notification if nothing changed.
                if (!empty($data->attempts)) {
                    redirect(
                        $faileddashurl,
                        get_string('notification:extraattemptssaved', $this->plugin, $a),
                        null,
                        \core\output\notification::NOTIFY_SUCCESS
                    );
                } else {
                    redirect($faileddashurl);
                }
            }
        } elseif ($this->form->is_cancelled()) {
            redirect($faileddashurl);
        }
    }
}
