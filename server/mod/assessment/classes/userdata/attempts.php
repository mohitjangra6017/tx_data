<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon Adams <simon.adams@kineo.com>
 */

namespace mod_assessment\userdata;


use context;
use context_module;
use core\files\file_helper;
use core\orm\query\builder;
use mod_assessment\helper\attempt;
use mod_assessment\model\assessment;
use mod_assessment\model\version;
use totara_userdata\userdata\export;
use totara_userdata\userdata\item;
use totara_userdata\userdata\target_user;

class attempts extends item
{
    /**
     * @return array|string[]
     */
    public static function get_fullname_string()
    {
        return ['userdataitem-user-attempts', 'mod_assessment'];
    }

    /**
     * @inheritDoc
     */
    public static function is_purgeable(int $userstatus): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public static function is_exportable(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public static function is_countable(): bool
    {
        return true;
    }

    /**
     * @param target_user $user
     * @param context $context
     * @return int
     */
    protected static function count(target_user $user, context $context): int
    {
        return builder::table('assessment_attempt')
                      ->where('userid', '=', $user->id)
                      ->count();
    }

    /**
     * @param target_user $user
     * @param context $context
     * @return export
     */
    protected static function export(target_user $user, context $context): export
    {
        $export = new export();

        $attempts = builder::table('assessment_attempt')
                           ->where('userid', '=', $user->id)
                           ->get()
                           ->to_array();

        $attempts_export = [];

        foreach ($attempts as $attempt) {
            $attempt['status'] = attempt::status($attempt['status']);
            $version = version::instance(['id' => $attempt['versionid']]);
            $assessment = assessment::instance(['id' => $version->get_assessmentid()]);
            $attempt['assessmentid'] = $assessment->get_id();
            $attempt['assessmentname'] = $assessment->name;

            $answers = builder::table('assessment_attempt', 'attempt')
                              ->select(['question.type', 'question.question', 'answer.id', 'answer.value as answer'])
                              ->join(['assessment_answer', 'answer'], 'attempt.id', '=', 'answer.attemptid')
                              ->join(['assessment_question', 'question'], 'answer.questionid', '=', 'question.id')
                              ->where('attempt.id', '=', $attempt['id'])
                              ->get()
                              ->to_array();

            $files_to_export = [];
            foreach ($answers as $answer) {
                if ($answer['type'] == 'file') {
                    $fh = new file_helper(
                        'mod_assessment',
                        'answer',
                        context_module::instance($assessment->get_cmid())
                    );
                    $fh->set_item_id($answer['id']);
                    $stored_files = $fh->get_stored_files();
                    foreach ($stored_files as $stored_file) {
                        $files_to_export[] = $export->add_file($stored_file);
                    }
                }
            }

            $attempt['answers'] = $answers;
            $attempt['files'] = $files_to_export;
            $attempts_export[] = $attempt;
        }

        $export->data = $attempts_export;

        return $export;
    }

    /**
     * @param target_user $user
     * @param context $context
     * @return int
     */
    protected static function purge(target_user $user, context $context): int
    {
        global $DB;

        $attempts = builder::table('assessment_attempt')
                           ->where('userid', '=', $user->id)
                           ->get()
                           ->to_array();

        $trans = $DB->start_delegated_transaction();

        try {
            foreach ($attempts as $attempt) {
                $answers = builder::table('assessment_attempt', 'attempt')
                                  ->select(['question.id', 'question.type', 'answer.id'])
                                  ->join(['assessment_answer', 'answer'], 'attempt.id', '=', 'answer.attemptid')
                                  ->join(['assessment_question', 'question'], 'answer.questionid', '=', 'question.id')
                                  ->where('attempt.id', '=', $attempt['id'])
                                  ->get()
                                  ->to_array();

                $version = version::instance(['id' => $attempt['versionid']]);
                $assessment = assessment::instance(['id' => $version->get_assessmentid()]);

                foreach ($answers as $answer) {
                    if ($answer['type'] == 'file') {
                        $fh = new file_helper(
                            'mod_assessment',
                            'answer',
                            context_module::instance($assessment->get_cmid())
                        );
                        $fh->set_item_id($answer['id']);
                        $stored_files = $fh->get_stored_files();
                        foreach ($stored_files as $stored_file) {
                            $stored_file->delete();
                        }
                    }
                }
                builder::table('assessment_answer')
                       ->where('attemptid', '=', $attempt['id'])
                       ->delete();

                builder::table('assessment_attempt_completion')
                       ->where('attemptid', '=', $attempt['id'])
                       ->delete();

                builder::table('assessment_stage_completion')
                       ->where('attemptid', '=', $attempt['id'])
                       ->delete();

                builder::table('assessment_attempt_assignments')
                       ->where('attemptid', '=', $attempt['id'])
                       ->delete();
            }

            builder::table('assessment_attempt')
                   ->where('userid', '=', $user->id)
                   ->delete();

            builder::table('assessment_due')
                   ->where('userid', '=', $user->id)
                   ->delete();

        } catch (\Exception $e) {
            $trans->rollback();
            return item::RESULT_STATUS_ERROR;
        }

        $trans->allow_commit();

        return item::RESULT_STATUS_SUCCESS;
    }
}