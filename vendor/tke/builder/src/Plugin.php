<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace KineoBuilder;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Package\Link;
use Composer\Package\Version\VersionParser;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Symfony\Component\Process\Process;

class Plugin implements PluginInterface, EventSubscriberInterface, Capable
{

    /**
     * The default build level. This will simply install packages at the exact version listed.
     */
    protected const ENV_BUILD_FINAL = 'final';

    /**
     * This will force plugins to install as the latest release candidate of the given version.
     */
    protected const ENV_BUILD_RELEASE = 'rc';

    /**
     * This will force plugins to install as the latest alpha of the given version.
     */
    protected const ENV_BUILD_ALPHA = 'alpha';

    /**
     * This will force plugins to install as the latest alpha of the given version and do a development TUI build.
     */
    protected const ENV_BUILD_DEV = 'dev';

    /**
     * The Composer config key for the build stability flag. Can be overridden by the environment key.
     */
    protected const KINEO_BUILDER_CONFIG_KEY = 'kineo-builder-stability';

    /**
     * The environment key for the build stability flag. Takes precedence over the config key.
     */
    protected const KINEO_BUILDER_ENV_KEY = 'KINEO_BUILDER_STABILITY';

    /**
     * Extends the timeout for the sub-processes that are run in the build.
     * This is mostly useful for Windows, as it seems to really struggle with doing `npm` commands in a reasonable timescale.
     */
    protected const EXTEND_PROCESS_TIMEOUT = 600;

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     * * The method name to call (priority defaults to 0)
     * * An array composed of the method name to call and the priority
     * * An array of arrays composed of the method names to call and respective
     *   priorities, or 0 if unset
     *
     * For instance:
     *
     * * array('eventName' => 'methodName')
     * * array('eventName' => array('methodName', $priority))
     * * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            'pre-update-cmd' => 'onPreInstallEvent',
            'post-update-cmd' => 'onPostInstallEvent',
            'pre-install-cmd' => 'onPreInstallEvent',
            'post-install-cmd' => 'onPostInstallEvent',
        ];
    }

    /**
     * Apply plugin modifications to Composer
     *
     * @param Composer $composer
     * @param IOInterface $io
     */
    public function activate(Composer $composer, IOInterface $io)
    {

    }

    /**
     * Remove any hooks from Composer
     *
     * This will be called when a plugin is deactivated before being
     * uninstalled, but also before it gets upgraded to a new version
     * so the old one can be deactivated and the new one activated.
     *
     * @param Composer $composer
     * @param IOInterface $io
     */
    public function deactivate(Composer $composer, IOInterface $io)
    {

    }

    /**
     * Prepare the plugin to be uninstalled
     *
     * This will be called after deactivate.
     *
     * @param Composer $composer
     * @param IOInterface $io
     */
    public function uninstall(Composer $composer, IOInterface $io)
    {

    }

    /**
     * Method by which a Plugin announces its API implementations, through an array
     * with a special structure.
     *
     * The key must be a string, representing a fully qualified class/interface name
     * which Composer Plugin API exposes.
     * The value must be a string as well, representing the fully qualified class name
     * of the implementing class.
     *
     * @tutorial
     *
     * return array(
     *     'Composer\Plugin\Capability\CommandProvider' => 'My\CommandProvider',
     *     'Composer\Plugin\Capability\Validator'       => 'My\Validator',
     * );
     *
     * @return string[]
     */
    public function getCapabilities()
    {
        return [
            'Composer\Plugin\Capability\CommandProvider' => CommandProvider::class,
        ];
    }

    /**
     * Go through the composer.json file and update all plugins to be the exact version installed, then update the lock file to match.
     * @param Event $event
     */
    public static function onPreInstallEvent(Event $event)
    {
        $env = self::getBuildStability($event);

        $updateDependencies = function (string $level) use ($event)
        {
            $versionParser = new VersionParser();
            $newRequires = [];
            $package = $event->getComposer()->getPackage();
            foreach ($package->getRequires() as $name => $require) {
                // If this isn't a T:KE package, keep track of it, but do not change it.
                if (strpos($name, 'tke/') === false) {
                    $newRequires[$name] = $require;
                    continue;
                }

                // Make sure this isn't a variable constraint, as we can't modify those.
                if (preg_match('#~|@|as|>|>=|<|<=|\^#', $require->getConstraint()->getPrettyString())) {
                    $newRequires[$name] = $require;
                    $event->getIO()
                          ->warning(
                              sprintf(
                                  'Kineo Builder: Cannot modify package "%s" as it has a variable constraint "%s"',
                                  $name,
                                  $require->getConstraint()->getPrettyString()
                              )
                          );
                    continue;
                }

                $newVersion = sprintf(
                    "~%s@%s",
                    $require->getConstraint()->getPrettyString(),
                    $level
                );

                $newRequires[$name] = new Link(
                    $require->getSource(),
                    $require->getTarget(),
                    $versionParser->parseConstraints($newVersion),
                    $require->getDescription(),
                    $require->getPrettyConstraint()
                );
                $event->getIO()->info('Kineo Builder: Package ' . $name . ' has new constraint: ' . $newVersion);
            }

            $package->setRequires($newRequires);
            $package->setMinimumStability($level);
        };

        switch ($env) {
            case self::ENV_BUILD_DEV:
                $event->getIO()->writeError(
                    '<info>Kineo Builder: stability is set to DEV. Changing all plugins to alpha versions.</info>'
                );
                $updateDependencies('alpha');
                break;
            case self::ENV_BUILD_ALPHA:
                $event->getIO()->writeError(
                    '<info>Kineo Builder: stability is set to QA. Changing all plugins to alpha versions.</info>'
                );
                $updateDependencies('alpha');
                break;
            case self::ENV_BUILD_RELEASE:
                $event->getIO()->writeError(
                    '<info>Kineo Builder: stability is set to RC. Changing all plugins to release candidate versions.</info>'
                );
                $updateDependencies('RC');
                break;
            case self::ENV_BUILD_FINAL:
                $event->getIO()->writeError(
                    '<info>Kineo Builder: stability is set to FINAL. No changes will be made to plugins.</info>'
                );
                return;
            default:
                $event->getIO()->writeError(
                    '<warning>Kineo Builder: stability is not set correctly. No changes will be made to plugins.</warning>'
                );
        }
    }

    public static function onPostInstallEvent(Event $event)
    {
        $io = $event->getIO();

        // We are either installing this directly to T:KE, so can use the cwd, or this is a client bundle,
        // in which case cwd needs to be in the T:KE install directory.
        $cwd = getcwd();
        if ($event->getComposer()->getPackage()->getType() === 'tke-client') {
            $package = $event->getComposer()->getRepositoryManager()->findPackage('tke/tke', '*');
            if (!$package) {
                $io->writeError(
                    '<error>Kineo Builder: Failed to find the install location of T:KE.</error>Ensure tke/tke is required before continuing.'
                );
                return;
            }
            $cwd = rtrim($cwd, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $event->getComposer()->getInstallationManager()->getInstallPath($package);
        }

        $io->writeError('<info>Kineo Builder: Building TUI dependencies</info>');
        $io->writeError(
            "<info>Kineo Builder: This can take up to 15 minutes, depending on your computer's performance and if this is the first time you are using Kineo Builder.</info>"
        );

        // Little helper for wrapping a command up with correct messaging to the user.
        $run = function (string $command, ?string $cwd = null) use ($io) {
            if (method_exists(Process::class, 'fromShellCommandline')) {
                $process = Process::fromShellCommandline($command, $cwd);
            } else {
                $process = new Process($command, $cwd);
            }
            $process->setTimeout(self::EXTEND_PROCESS_TIMEOUT);

            $io->debug('Kineo Builder: Running command: ' . $command);
            if (($exitCode = $process->run()) !== 0) {
                $io->writeError(
                    "<error>Kineo Build: Failed to build TUI dependencies with error {$exitCode}: {$process->getErrorOutput()}</error>"
                );
            }
            return $exitCode;
        };

        // First up: make sure npm dependencies are up-to-date.
        if (($exitCode = $run('npm install', $cwd)) !== 0) {
            return;
        }

        // Now, build a production TUI build, unless we are building this for development, in which case do everything.
        $command = 'npm run tui-build';
        $command .= (self::getBuildStability($event) === self::ENV_BUILD_DEV) ? '' : '-prod';
        if (($exitCode = $run($command, $cwd)) !== 0) {
            return;
        }

        $io->writeError("<info>Kineo Builder: Successfully built TUI dependencies</info>");
    }

    protected static function getBuildStability(Event $event): string
    {
        $stability = getenv(self::KINEO_BUILDER_ENV_KEY);
        if (self::validateStability($stability)) {
            return $stability;
        }

        $stability = $event->getComposer()->getConfig()->get(self::KINEO_BUILDER_CONFIG_KEY);
        return self::validateStability($stability) ? $stability : self::ENV_BUILD_FINAL;
    }

    protected static function validateStability(?string $stability): bool
    {
        return is_string($stability)
               && in_array(
                   strtolower($stability),
                   [self::ENV_BUILD_FINAL, self::ENV_BUILD_RELEASE, self::ENV_BUILD_ALPHA, self::ENV_BUILD_DEV]
               );
    }
}
