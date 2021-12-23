<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_core\Mustache;

use core_collator;
use core_component;
use Generator;
use Throwable;

class OverrideSystem
{
    /**
     * @return string[]
     */
    public static function getOverrideDirectories(string $component): array
    {
        static $directories = [];
        if (empty($directories)) {
            $directories = self::load();
        }

        // Every requested component needs its own set of paths, so generate and store them the first time they are requested.
        static $components = [];
        if (empty($components[$component])) {
            $components[$component] = array_map(
                function ($path) use ($component) {
                    return $path . DIRECTORY_SEPARATOR . $component . DIRECTORY_SEPARATOR;
                },
                $directories
            );
        }

        return $components[$component];
    }

    private static function load(): array
    {
        global $CFG;

        $classes = core_component::get_namespace_classes('Mustache', OverrideInterface::class);
        $directories = [];

        foreach ($classes as $class) {

            // Get the actual component from the class name so we can get it's directory.
            $classComponent = substr($class, 0, strpos($class, '\\'));
            if ($classComponent === false) {
                continue;
            }

            // Just give up with this class if an exception occurs.
            try {
                /** @var OverrideInterface $override */
                $override = new $class();
            } catch (Throwable $e) {
                continue;
            }

            $componentDir = core_component::get_component_directory($classComponent);
            if ($componentDir === null) {
                continue;
            }

            $overrideDir = rtrim($componentDir, "/\\") . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'overrides';
            if (realpath($overrideDir) !== $overrideDir || !is_dir($overrideDir)) {
                continue;
            }

            $directories[$overrideDir] = $override->priority();
        }

        core_collator::asort(
            $directories,
            core_collator::SORT_NUMERIC | core_collator::REVERSED
        );

        return array_keys($directories);
    }
}