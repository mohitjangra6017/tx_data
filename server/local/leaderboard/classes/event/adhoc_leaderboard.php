<?php

namespace local_leaderboard\event;

use coding_exception;
use lang_string;
use moodle_exception;
use moodle_url;

defined('MOODLE_INTERNAL') || die();

class adhoc_leaderboard extends \core\event\base
{

    /**
     * Initialise the event data.
     */
    protected function init()
    {
        $this->context = \context_system::instance();
        $this->data['crud'] = 'c';
        $this->data['edulevel'] = self::LEVEL_OTHER;
    }

    /**
     * Returns localised general event name.
     * @return lang_string|string
     * @throws coding_exception
     */
    public static function get_name()
    {
        return get_string('event:name:adhoc_score', 'local_leaderboard');
    }

    /**
     * Returns non-localised description of what happened.
     *
     * @return string
     */
    public function get_description()
    {
        $score = $this->data['other']['score'];
        $relatedUserId = $this->data['other']['relateduserid'];

        return "Ad hoc score of {$score} awarded to User id {$this->userid} by User id {$relatedUserId}.";
    }

    /**
     * Returns relevant URL, override in subclasses.
     * @return moodle_url|null
     * @throws moodle_exception
     */
    public function get_url()
    {
        return new moodle_url('/user/profile.php', ['id' => $this->userid]);
    }
}