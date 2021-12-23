<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace KineoBuilder;

use Composer\Command\BaseCommand;
use Composer\Plugin\Capability\CommandProvider as CommandProviderCapability;

class CommandProvider implements CommandProviderCapability
{
    /**
     * Retrieves an array of commands
     *
     * @return BaseCommand[]
     */
    public function getCommands()
    {
        return [
            new BuilderCommand(),
        ];
    }

}