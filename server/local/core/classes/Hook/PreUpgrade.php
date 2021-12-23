<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Ben Lobo <ben.lobo@kineo.com>
 */

namespace local_core\Hook;

use totara_core\hook\base;

/**
 * Class PreUpgrade
 * @package local_core\Hook
 *
 */
class PreUpgrade extends base
{
    private int $upgradeType;

    const UPGRADETYPE_CORE = 1;
    const UPGRADETYPE_NONCORE = 2;

    public function __construct(int $upgradeType)
    {
        $this->upgradeType = $upgradeType;
    }

    public function getUpgradeType(): int
    {
        return $this->upgradeType;
    }

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
