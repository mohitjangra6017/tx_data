<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

namespace mod_assessment\processor;

use mod_assessment\entity\assessment_version_assignment;
use mod_assessment\factory\assessment_question_factory;
use mod_assessment\factory\assessment_version_assignment_factory;
use mod_assessment\model\question;
use mod_assessment\model\rule;
use mod_assessment\model\ruleset;
use mod_assessment\model\stage;
use mod_assessment\model\version;
use mod_assessment\model\version_question;
use mod_assessment\model\version_stage;

defined('MOODLE_INTERNAL') || die();

class version_clone_processor
{

    protected version $version;

    public function __construct(version $version)
    {
        $this->version = $version;
    }

    public static function clone_from_version(version $version): version
    {
        $processor = new self($version);
        return $processor->execute();
    }

    public function execute(): version
    {
        global $DB;
        $transaction = $DB->start_delegated_transaction();

        $instance = clone $this->version;
        unset($instance->id);
        unset($instance->timeopened);
        unset($instance->timeclosed);
        unset($instance->timecreated);
        unset($instance->timemodified);
        $instance->save();

        // Add stage maps.
        $stages = stage::instances_from_version($this->version);
        foreach ($stages as $stage) {
            $oldvstage = version_stage::instance(['stageid' => $stage->id, 'versionid' => $this->version->get_id()]);

            $versionstage = new version_stage();
            $versionstage->set_locked($oldvstage->get_locked());
            $versionstage->set_stageid($stage->id);
            $versionstage->set_versionid($instance->id);
            $versionstage->calculate_sortorder();
            $versionstage->save();

            // Add question maps.
            $oldstage = version_stage::instance(['stageid' => $stage->id, 'versionid' => $this->version->get_id()]);
            $questions = question::instances_from_versionstage($oldstage);
            $this->save_questions($questions, $stage->get_id(), $instance->get_id());
        }

        // Clone rules.
        $rulesets = ruleset::instances(['versionid' => $this->version->get_id()]);
        foreach ($rulesets as $ruleset) {
            $newruleset = clone($ruleset);
            unset($newruleset->id);
            $newruleset->set_versionid($instance->id)->save();

            $rules = rule::instances(['rulesetid' => $ruleset->id]);
            foreach ($rules as $rule) {
                unset($rule->id);
                $rule->set_rulesetid($newruleset->id)->save();
            }
        }

        // Clone direct user assignments.
        /** @var assessment_version_assignment[] $assignments */
        $assignments = assessment_version_assignment_factory::fetch_all(['versionid' => $this->version->get_id()]);
        foreach ($assignments as $assignment) {
            assessment_version_assignment_factory::create_clone($assignment, $instance->get_id())->save();
        }

        $transaction->allow_commit();

        return $instance;
    }

    protected function save_questions($questions, $stageid, $versionid, $parentid = null)
    {
        foreach ($questions as $question) {
            $versionquestion = new version_question();
            $versionquestion->set_questionid($question->id);
            $versionquestion->set_parentid($parentid);
            $versionquestion->set_stageid($stageid);
            $versionquestion->set_versionid($versionid);
            $versionquestion->calculate_sortorder();
            $versionquestion->save();

            $children = assessment_question_factory::fetch_from_parentid($question->get_id(), $this->version->get_id());
            if ($children) {
                $this->save_questions($children, $stageid, $versionid, $question->get_id());
            }
        }
    }
}
