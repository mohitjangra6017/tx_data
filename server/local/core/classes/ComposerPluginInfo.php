<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_core;

use moodle_exception;
use Throwable;

/**
 * Loads the Composer Plugin Version information.
 * Will do its absolute utmost to not throw an error, as doing so would block pretty much everything internal to Moodle's plugin loading.
 * @package local_core
 */
class ComposerPluginInfo
{
    private static $instance;

    private function __construct()
    {

    }

    public static function getInstance(): self
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function getInstalledPluginVersion(string $pluginName): ?string
    {
        if (!class_exists('\Composer\InstalledVersions')) {
            return null;
        }

        try {
            $version = \Composer\InstalledVersions::getPrettyVersion('tke/' . $pluginName);
        } catch (\OutOfBoundsException $exception) {
            try {
                $version = \Composer\InstalledVersions::getPrettyVersion('kineo/' . $pluginName);
            } catch (\OutOfBoundsException $e) {
                debugging('No Composer version found for plugin ' . $pluginName);
            }
        } catch (moodle_exception $exception) {
            $message = 'Moodle error occurred in ComposerPluginInfo: ' .
                       $exception->getMessage() .
                       PHP_EOL .
                       $exception->debuginfo;
            debugging($message, DEBUG_DEVELOPER);
        } catch (Throwable $exception) {
            debugging(
                'Unknown error occurred in ComposerPluginInfo: ' . $exception->getMessage(),
                DEBUG_DEVELOPER
            );
        } finally {
            // This gets called every time, so make sure to coalesce this to null in case we caught an exception.
            return $version ?? null;
        }
    }
}
