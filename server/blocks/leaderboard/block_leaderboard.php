<?php
/**
 * Score block
 *
 * @package   block_score
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2016 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

defined('MOODLE_INTERNAL') || die;

use core\orm\query\exceptions\record_not_found_exception;
use local_leaderboard\Utils;

global $CFG;

class block_leaderboard extends block_base
{

    const COMPONENT = __CLASS__;

    /**
     * Initialise block
     * @throws coding_exception
     */
    public function init(): void
    {
        $this->title = get_string('pluginname', self::COMPONENT);
    }

    /**
     * @return bool
     */
    function has_config(): bool
    {
        return true;
    }

    /**
     * Render the Leaderboard block.
     *
     * @return object
     * @throws record_not_found_exception
     * @throws coding_exception
     * @throws dml_exception
     */
    public function get_content(): object
    {
        global $USER, $OUTPUT;

        if ($this->content !== null) {
            return $this->content;
        }

        if (!empty(get_config(self::COMPONENT, 'displaytotal'))) {
            $score = new \stdClass();
            $score->rank = Utils::getUserRank($USER->id);
            $score->lowestrank = Utils::getLowestRank();
            $rank = get_string('currentranktotal', self::COMPONENT, $score);

        } else {
            $rank = get_string('currentrank', self::COMPONENT, Utils::getUserRank($USER->id));
        }
        $leaderboardUrl = get_config(self::COMPONENT, 'leaderboardurl');

        $this->content = new \stdClass();
        $this->content->footer = '';
        $this->content->text = $OUTPUT->render_from_template(
            'block_leaderboard/leaderboard',
            [
                'score' => Utils::getUserScore($USER->id),
                'rank' => $rank,
                'leaderboardurl' => $leaderboardUrl,
            ]
        );

        return $this->content;
    }
}