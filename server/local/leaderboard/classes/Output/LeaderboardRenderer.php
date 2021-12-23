<?php

namespace local_leaderboard\Output;

use coding_exception;
use moodle_exception;
use moodle_url;
use plugin_renderer_base;

defined('MOODLE_INTERNAL') || die();

class LeaderboardRenderer extends plugin_renderer_base
{
    /**
     * @param $scoreId
     * @param $context
     * @return string
     * @throws coding_exception
     * @throws moodle_exception
     */
    public function printScoreTableActions($scoreId, $context)
    {
        $actions = "";

        if (has_capability('local/leaderboard:config', $context)) {

            $return = new moodle_url(qualified_me());

            $editUrl = new moodle_url('/local/leaderboard/edit.php', ['id' => $scoreId]);
            $actions .= $this->output->action_icon($editUrl, new \pix_icon('t/edit', get_string('edit'))) . " ";

            $deleteUrl = new moodle_url('/local/leaderboard/action.php', ['id' => $scoreId]);
            $deleteUrl->param('delete', 1);
            $deleteUrl->param('return', $return->out_as_local_url(false));
            $deleteUrl->param('sesskey', sesskey());
            $actions .= $this->output->action_icon($deleteUrl, new \pix_icon('t/delete', get_string('delete'))) . " ";
        }

        return $actions;
    }
}