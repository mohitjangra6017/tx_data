<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Ben Lobo <ben.lobo@kineo.com>
 */

namespace local_core\Hook;

use totara_core\hook\base;

/**
 * Class PostUpgrade
 * @package local_core\Hook
 *
 */
class PostUpgrade extends base
{
    public function isCliRequest(): bool
    {
        return defined('CLI_SCRIPT') && CLI_SCRIPT;
    }

    public function isDryRun(): bool
    {
        global $CFG;
        return $CFG->upgradeDryRun ?? false;
    }
}
