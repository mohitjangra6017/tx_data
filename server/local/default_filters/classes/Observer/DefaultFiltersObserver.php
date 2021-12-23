<?php
/**
 * @copyright City & Guilds Kineo 2017
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_default_filters\Observer;


class DefaultFiltersObserver {

    /**
     * @param \core\event\user_loggedin $event
     * @return bool
     * @throws dml_exception
     */
    public static function set_defaults_on_login(\core\event\user_loggedin $event): bool
    {
        global $DB, $SESSION;

        $defaults = $DB->get_records('local_default_filters');
        if (!isset($SESSION->reportbuilder)) {
            $SESSION->reportbuilder = [];
        }
        foreach ($defaults as $default) {
            $SESSION->reportbuilder[$default->reportid] = unserialize($default->data);
        }

        return true;
    }

}