<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

namespace mod_assessment\processor;

use mod_assessment\entity\attempt_completion;
use mod_assessment\factory\assessment_version_factory;
use mod_assessment\factory\attempt_completion_factory;
use mod_assessment\model\attempt;
use mod_assessment\model\attempt_completion_status;
use mod_assessment\model\role;
use mod_assessment\model\stage;
use mod_assessment\model\stage_completion;
use mod_assessment\model\version;
use mod_assessment\model\version_stage;

class attempt_completion_processor
{

    protected attempt $attempt;

    /** @var stage[] */
    protected array $stages;

    public function __construct(attempt $attempt)
    {
        $this->attempt = $attempt;
        $this->stages = stage::instances_from_version(assessment_version_factory::fetch(['id' => $attempt->get_versionid()]));
    }

    public function generate_completions()
    {
        foreach (role::get_roles() as $roleid => $rolename) {
            $completion = new attempt_completion(
                $this->attempt->get_id(),
                new role($roleid),
                $this->get_role_status(new role($roleid))
            );
            $completion->save();
        }
    }

    public function update_statuses()
    {
        if (!attempt_completion_factory::fetch(['attemptid' => $this->attempt->get_id()], IGNORE_MULTIPLE)) {
            $this->generate_completions();
        }

        foreach (role::get_roles() as $roleid => $rolename) {
            $completion = attempt_completion_factory::fetch(['attemptid' => $this->attempt->get_id(), 'role' => $roleid]);
            $completion->set_status($this->get_role_status(new role($roleid)));
            $completion->save();
        }
    }

    protected function get_role_status(role $role): attempt_completion_status
    {
        $previousvstage = null;
        foreach ($this->stages as $stage) {
            $vstage = version_stage::instance(['stageid' => $stage->get_id(), 'versionid' => $this->attempt->get_versionid()], MUST_EXIST);
            if ($vstage->get_locked()) {
                $processor = new completion();
                if (!$processor->is_stage_complete($this->attempt, $previousvstage)) {
                    return new attempt_completion_status(attempt_completion_status::WAITING);
                }
            }

            // Only get stage completion for non-archived attempts.
            $completions = stage_completion::instances(['stageid' => $stage->get_id(), 'role' => $role->value()]);
            foreach ($completions as $completion) {
                $attempt = $completion->get_attempt();
                if ($attempt->is_archived()) {
                    continue;
                }

                if (!$completion || !$completion->is_complete()) {
                    return new attempt_completion_status(attempt_completion_status::READY);
                }
            }

            $previousvstage = $vstage;
        }

        return new attempt_completion_status(attempt_completion_status::COMPLETE);
    }
}
