<?php
/**
 * @copyright   City & Guilds Kineo 2017
 * @author      Steven Hughes <steven.hughes@kineo.com>
 */

namespace mod_assessment\totara\menu;

defined('MOODLE_INTERNAL') || die();

use context_system;
use mod_assessment\helper;
use totara_core\totara\menu\item;

class assessment extends item
{

    public function get_default_sortorder(): int
    {
        return 90000;    // Hide this at the end.
    }

    /**
     * @return string
     */
    protected function get_default_title(): string
    {
        return get_string('navigation:dashboard', 'mod_assessment');
    }

    /**
     * @return string
     */
    protected function get_default_url(): string
    {
        return '/mod/assessment/dashboard.php';
    }

    /**
     * @return bool
     */
    public function get_default_visibility(): bool
    {
        return true;
    }

    /**
     * @return bool|null
     */
    protected function check_visibility(): ?bool
    {
        static $cache = null;

        if (isset($cache)) {
            return $cache;
        }

        $cache = has_capability('mod/assessment:viewdashboard', context_system::instance()) ||
            has_capability('mod/assessment:viewfaileddashboard', context_system::instance()) ||
            has_capability('mod/assessment:viewcompleteddashboard', context_system::instance()) ||
            has_capability('mod/assessment:viewcompletedrevieweddashboard', context_system::instance()) ||
            has_capability('mod/assessment:viewarchiveddashboard', context_system::instance()) ||
            helper\role::is_user_evaluator() ||
            helper\role::is_user_reviewer();

        return $cache;
    }
}