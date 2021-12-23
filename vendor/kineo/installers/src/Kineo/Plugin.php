<?php
/**
 * @copyright City & Guilds Kineo 2018
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace KineoInstaller;

use Composer\Composer;
use Composer\DependencyResolver\Operation\InstallOperation;
use Composer\DependencyResolver\Operation\UninstallOperation;
use Composer\DependencyResolver\Operation\UpdateOperation;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvent;
use Composer\IO\IOInterface;
use Composer\Package\Package;
use Composer\Package\PackageInterface;
use Composer\Plugin\PluginInterface;
use Composer\Util\Filesystem;

class Plugin implements PluginInterface, EventSubscriberInterface
{
    protected const HAS_TOTARA_COMPONENT = 'has-totara-component';
    protected const TOTARA_COMPONENT_DIR = '.component';

    protected const CONFIG_KEY_INSTALL_MODE = 'kineo-plugin-install-mode';

    protected const CONFIG_KEY_ENV = 'KINEO_PLUGIN_INSTALL_MODE';

    protected const INSTALL_MODE_MOVE = 'move';
    protected const INSTALL_MODE_SYMLINK = 'symlink';

    /**
     * Apply plugin modifications to Composer
     *
     * @param Composer $composer
     * @param IOInterface $io
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $installer = new KineoInstallerHelper($io, $composer);
        $composer->getInstallationManager()->addInstaller($installer);
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
        // Nothing to do here.
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
        // Nothing to do here.
    }


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
            'post-package-install' => 'onPostPackageInstallEvent',
            'post-package-update' => 'onPostPackageUpdateEvent',
            'post-package-uninstall' => 'onPostPackageUninstallEvent',
        ];
    }

    public static function onPostPackageInstallEvent(PackageEvent $event)
    {
        /** @var InstallOperation $operation */
        $operation = $event->getOperation();
        $package = $operation->getPackage();
        if (!static::packageHasComponent($package)) {
            return;
        }

        static::installComponent($event, $package);
    }

    public static function onPostPackageUpdateEvent(PackageEvent $event)
    {
        /** @var UpdateOperation $operation */
        $operation = $event->getOperation();
        $package = $operation->getTargetPackage();
        if (!static::packageHasComponent($package)) {
            return;
        }
        static::installComponent($event, $package);
    }

    public static function onPostPackageUninstallEvent(PackageEvent $event)
    {
        /** @var UninstallOperation $operation */
        $operation = $event->getOperation();
        $package = $operation->getPackage();
        if (!static::packageHasComponent($package)) {
            return;
        }
        static::uninstallComponent($event, $package);
    }

    /**
     * @param PackageInterface $package
     * @return bool
     */
    private static function packageHasComponent(PackageInterface $package): bool
    {
        return $package->getExtra()['kineo'][static::HAS_TOTARA_COMPONENT] ?? false;
    }

    /**
     * @param PackageEvent $event
     * @param PackageInterface $package
     */
    private static function installComponent(PackageEvent $event, PackageInterface $package): void
    {
        $sourceDir = rtrim($event->getComposer()->getInstallationManager()->getInstallPath($package), DIRECTORY_SEPARATOR)
            . DIRECTORY_SEPARATOR
            . rtrim(static::TOTARA_COMPONENT_DIR, DIRECTORY_SEPARATOR);
        $destDir = self::getComponentDir($package, $event);

        $installMode = getenv(static::CONFIG_KEY_ENV)
           ?? $event->getComposer()->getConfig()->get(static::CONFIG_KEY_INSTALL_MODE)
           ?? static::INSTALL_MODE_MOVE;

        self::copyPackage(
            $sourceDir,
            $destDir,
            $installMode
        );
    }

    /**
     * @param PackageEvent $event
     * @param PackageInterface $package
     */
    private static function uninstallComponent(PackageEvent $event, PackageInterface $package): void
    {
        $destDir = self::getComponentDir($package, $event);
        (new Filesystem())->remove($destDir);
    }

    /**
     * @param PackageInterface $package
     * @param PackageEvent $event
     * @return string
     */
    private static function getComponentDir(PackageInterface $package, PackageEvent $event): string
    {
        // Create a fake Package so we can get Composer to tell us where the destination should be.
        $component = new Package($package->getName(), $package->getVersion(), $package->getPrettyVersion());
        $component->setType('totara-component');

        // The current package has a type like "moodle-{type}" or "totara-{type}", and the name is in the installer-name.
        // Take these 2 to build the fake installer-name.
        $component->setExtra(
            [
                'installer-name' => explode('-', $package->getType())[1] . '_' . $package->getExtra()['installer-name']
            ]
        );
        return rtrim(
            $event->getComposer()->getInstallationManager()->getInstallPath($component),
            DIRECTORY_SEPARATOR
        );
    }

    /**
     * @param string $sourceDir
     * @param string $destDir
     * @param string $copyMode
     */
    private static function copyPackage(string $sourceDir, string $destDir, string $copyMode = self::INSTALL_MODE_MOVE): void
    {
        $cwd = getcwd();

        $fs = new Filesystem();
        $fs->remove($destDir);

        switch ($copyMode) {
            case static::INSTALL_MODE_SYMLINK:
                $fs->relativeSymlink($cwd . DIRECTORY_SEPARATOR . $sourceDir, $cwd . DIRECTORY_SEPARATOR . $destDir);
                break;

            case static::INSTALL_MODE_MOVE:
            default:
                $fs->rename($sourceDir, $destDir);
                break;
        }
    }
}