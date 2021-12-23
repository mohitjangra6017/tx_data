<?php

use mod_assessment\entity\assessment_version_assignment;
use mod_assessment\factory\assessment_question_factory;
use mod_assessment\factory\assessment_version_assignment_factory;
use mod_assessment\factory\assessment_version_factory;
use mod_assessment\model\question;
use mod_assessment\model\role;
use mod_assessment\model\stage;
use mod_assessment\model\version;
use mod_assessment\model\version_question;
use mod_assessment\model\version_stage;

/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2019 Kineo
 */

class mod_assessment_generator extends testing_module_generator {

    protected $questioncount = 0;
    protected $stagecount = 0;
    protected $usercount = 3;

    public function create_instance($record = null, array $options = null) {
        $record = (object)(array)$record;
        $record->attempts = random_int(0, 10);
        $record->extraattempts = false;
        $record->grademethod = mod_assessment\helper\grade::METHOD_HIGHEST;

        return parent::create_instance($record, $options);
    }

    public function create_version(int $assessmentid, array $options = null): version {
        $options['assessmentid'] = $assessmentid;

        if (!isset($options['singleevaluator'])) {
            $options['singleevaluator'] = true;
        }

        if (!isset($options['singlereviewer'])) {
            $options['singlereviewer'] = true;
        }

        $version = assessment_version_factory::create_from_data($options);
        $version->save();
        return $version;
    }

    public function create_version_assignment(int $versionid, array $options = null): assessment_version_assignment {
        $options['versionid'] = $versionid;

        if (!isset($options['userid'])) {
            $options['userid'] = $this->usercount++;
        }

        if (!isset($options['learnerid'])) {
            $options['learnerid'] = $this->usercount++;
        }

        if (!isset($options['role'])) $options['role'] = role::EVALUATOR;

        $assignment = assessment_version_assignment_factory::create_from_data($options);
        $assignment->save();
        return $assignment;
    }

    public function create_question(array $options = []): question {
        $this->questioncount++;

        if (!isset($options['type'])) {
            $options['type'] = 'text';
        }

        if (!isset($options['question'])) {
            $options['question'] = "Question #{$this->questioncount}";
        }

        if (!isset($options['learnerperms'])) {
            $options['learnerperms'] = 0;
        }

        if (!isset($options['evaluatorperms'])) {
            $options['evaluatorperms'] = 0;
        }

        if (!isset($options['reviewerperms'])) {
            $options['reviewerperms'] = 0;
        }

        $question = assessment_question_factory::create_from_data($options);
        $question->save();
        return $question;
    }

    public function create_stage(array $options = []): stage {
        $this->stagecount++;

        if (!isset($options['name'])) {
            $options['name'] = "Stage #{$this->stagecount}";
        }

        if (!isset($options['newpage'])) {
            $options['newpage'] = false;
        }

        $stage = stage::make($options);
        $stage->save();
        return $stage;
    }

    public function assign_stage_to_version(stage $stage, version $version, array $options = []) {
        $requiredoptions = ['stageid' => $stage->get_id(), 'versionid' => $version->get_id()];
        $options = array_merge($requiredoptions, $options);

        if (!isset($options['locked'])) {
            $options['locked'] = false;
        }

        if (!isset($options['sortorder'])) {
            $options['sortorder'] = version_stage::get_next_sortorder($version->get_id());
        }

        $versionstage = version_stage::make($options);
        $versionstage->save();
        return $versionstage;
    }

    public function assign_question_to_version_stage(question $question, version_stage $versionstage, array $options = []) {
        $requiredoptions = [
            'stageid' => $versionstage->get_stageid(),
            'questionid' => $question->get_id(),
            'parentid' => null,
            'versionid'=> $versionstage->get_versionid()
        ];
        $options = array_merge($requiredoptions, $options);

        $versionquestion = version_question::make($options);
        $versionquestion->calculate_sortorder();
        $versionquestion->save();
        return $versionquestion;
    }
}
