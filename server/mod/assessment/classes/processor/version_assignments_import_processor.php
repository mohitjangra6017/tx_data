<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

namespace mod_assessment\processor;

use context_course;
use context_user;
use core\orm\query\builder;
use core\tenant_orm_helper;
use dml_exception;
use Dompdf\Exception;
use mod_assessment\entity\assessment_version_assignment;
use mod_assessment\entity\assessment_version_assignment_log;
use mod_assessment\factory\assessment_version_assignment_factory;
use mod_assessment\factory\assessment_version_assignment_log_factory;
use mod_assessment\factory\assessment_version_factory;
use mod_assessment\model\import_error;
use mod_assessment\model\role;
use mod_assessment\model\user_identifier;
use mod_assessment\model\version;
use stdClass;

class version_assignments_import_processor
{

    protected int $importid;
    protected user_identifier $learnerfield;
    protected user_identifier $userfield;
    protected bool $replace;
    protected bool $autoenrol;

    /** @var bool */
    protected bool $confirmed;

    /** @var stdClass */
    protected stdClass $course;

    public function __construct(int $importid, user_identifier $learnerfield, user_identifier $userfield, bool $replace, bool $autoenrol)
    {
        $this->importid = $importid;
        $this->learnerfield = $learnerfield;
        $this->userfield = $userfield;
        $this->replace = $replace;
        $this->autoenrol = $autoenrol;
    }

    public function execute()
    {
        if ($this->is_confirmed()) {
            return;
        }

        if ($this->replace) {
            /** @var assessment_version_assignment[] $assignments */
            $assignments = assessment_version_assignment_factory::fetch_all(['role' => $this->get_role()->value()]);
            foreach ($assignments as $assignment) {
                $assignment->delete();
                // TODO: Trigger delete event?
            }
        }

        /** @var assessment_version_assignment_log[] $logs */
        $logs = assessment_version_assignment_log_factory::fetch_all(['importid' => $this->importid]);
        foreach ($logs as $log) {
            $assignment = assessment_version_assignment_factory::create_from_log($log);
            if ($assignment) {
                $assignment->save();
                // TODO: Assignment event?

                // Enrol user if not enrolled.
                if (!is_enrolled(context_course::instance($this->get_course()->id), $assignment->get_learnerid())) {
                    global $CFG, $DB;
                    $defaultlearnerrole = $DB->get_record('role', array('id' => $CFG->learnerroleid));
                    $success = enrol_try_internal_enrol($this->get_course()->id, $assignment->get_learnerid(), $defaultlearnerrole->id, time());
                    if (!$success) {
                        throw new Exception("Could not enrol user: ({$assignment->get_learnerid()})");
                    }
                }
            }

            $log->mark_confirmed();
            $log->save();
        }
    }

    public function process_row_error(import_error $error, assessment_version_assignment_log $log)
    {
        $log->set_errorcode($error);
        $log->set_skipped(true);
        $log->save();
    }

    public function preprocess()
    {
        if ($this->is_confirmed()) {
            return;
        }

        /** @var assessment_version_assignment_log[] $logs */
        $logs = assessment_version_assignment_log_factory::fetch_all(['importid' => $this->importid]);
        foreach ($logs as $log) {
            $learner = builder::table('user', 'auser')
                              ->where($this->learnerfield->value(), '=', $log->get_learneridraw())
                              ->when(
                                  true,
                                  function (builder $builder) {
                                      tenant_orm_helper::restrict_users(
                                          $builder,
                                          'auser.id',
                                          context_course::instance($this->get_course()->id)
                                      );
                                  }
                              )
                              ->one();
            $log->set_learnerid($learner->id ?? null);

            $user = builder::table('user', 'auser')
                           ->select('auser.id')
                           ->where($this->userfield->value(), '=', $log->get_useridraw())
                           ->when(
                               true,
                               function (builder $builder) {
                                   tenant_orm_helper::restrict_users(
                                       $builder,
                                       'auser.id',
                                       context_course::instance($this->get_course()->id)
                                   );
                               }
                           )
                           ->one();
            $log->set_userid($user->id ?? null);

            // Validation.
            if (!$log->get_learnerid()) {
                $this->process_row_error(new import_error(import_error::LEARNER_NOT_FOUND), $log);
                continue;
            }

            if (!$log->get_userid()) {
                $this->process_row_error(new import_error(import_error::USER_NOT_FOUND), $log);
                continue;
            }

            if (!$this->autoenrol && !is_enrolled(context_course::instance($this->get_course()->id), $log->get_learnerid())) {
                $this->process_row_error(new import_error(import_error::LEARNER_NOT_ENROLLED), $log);
                continue;
            }

            $dupeparams = ['userid' => $log->get_userid(), 'learnerid' => $log->get_learnerid(), 'role' => $log->get_role()->value(), 'versionid' => $log->get_versionid()];
            if (!$this->replace && assessment_version_assignment_factory::fetch($dupeparams, IGNORE_MISSING)) {
                $this->process_row_error(new import_error(import_error::ASSIGNMENT_EXISTS), $log);
                continue;
            }

            // Validated!
            $log->set_errorcode(null)
                ->set_skipped(false)
                ->save();
        }
    }

    public function cancel_import()
    {
        global $DB;
        if ($this->is_confirmed()) {
            return;
        }
        $DB->delete_records(assessment_version_assignment_log::get_tablename(), ['importid' => $this->importid]);
    }

    public function is_confirmed(): bool
    {
        if (isset($this->confirmed)) {
            return $this->confirmed;
        }

        $this->confirmed = $this->get_single_log()->is_confirmed();
        return $this->confirmed;
    }

    protected function get_course(): stdClass
    {
        if (isset($this->course)) {
            return $this->course;
        }

        /** @var version $version */
        $version = assessment_version_factory::fetch(['id' => $this->get_single_log()->get_versionid()]);

        [$this->course, $cm] = get_course_and_cm_from_instance($version->get_assessmentid(), 'assessment');
        return $this->course;
    }

    protected function get_role(): role
    {
        if (isset($this->role)) {
            return $this->role;
        }

        $this->role = $this->get_single_log()->get_role();
        return $this->role;
    }

    private function get_single_log(): assessment_version_assignment_log
    {
        $log = assessment_version_assignment_log_factory::fetch(['importid' => $this->importid], IGNORE_MULTIPLE);
        if (!$log) {
            throw new \coding_exception("No logs found with importid: {$this->importid}");
        }
        return $log;
    }
}
