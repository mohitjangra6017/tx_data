<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_core\Mustache;

/**
 * Defines a Mustache Override that can modify the behaviour of any core mustache template.
 * Defining a class with this interface requires you to have also defined a 'templates/overrides' directory in that same plugin.
 * @package local_core\Mustache
 */
interface OverrideInterface
{
    /**
     * Defines the loading priority of this override. The higher the number, the earlier this loads.
     * Once a template has been found, it does not continue searching, so plugins with lower priorities will be skipped.
     * 2 plugins that define the same priority can - and will - cause race conditions if they both have the same template(s).
     * @return int
     */
    public function priority(): int;
}