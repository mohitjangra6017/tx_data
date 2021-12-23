<?php
/**
 * @copyright City & Guilds Kineo 2018
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace KineoInstaller;

use Composer\Factory;
use Composer\Installer\LibraryInstaller;
use Composer\Installers\Installer;
use Composer\IO\IOInterface;
use Composer\Package\CompletePackageInterface;
use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;
use Composer\Util\Filesystem;
use KineoInstaller\Installers\MoodleInstaller;
use KineoInstaller\Installers\TotaraInstaller;
use React\Promise\PromiseInterface;

class KineoInstallerHelper extends Installer
{
    public const KINEO_CLIENT_TYPE = 'tke-client';

    public const KINEO_TKE_TYPE = 'tke';

    /**
     * The Composer config key for the installer location flag. Can be overridden by the environment key.
     */
    protected const KINEO_INSTALLER_TKE_CONFIG_KEY = 'kineo-installer-default-tke-location';

    /**
     * The environment key for the installer location flag. Takes precedence over the config key.
     */
    protected const KINEO_INSTALLER_TKE_ENV_KEY = 'KINEO_INSTALLER_DEFAULT_TKE_LOCATION';

    /** @var string */
    private $cacheDir;

    private $installers = [
        'moodle' => MoodleInstaller::class,
        'kineo' => MoodleInstaller::class,
        'totara' => TotaraInstaller::class,
    ];

    public function getInstallPath(PackageInterface $package)
    {
        $basicPath = LibraryInstaller::getInstallPath($package);

        $pathPrefix = $this->getDefaultPackageInstallLocation();

        // If we are installing Totara: Kineo Edition itself, then make sure it installs into the root directory.
        if ($package->getType() === self::KINEO_TKE_TYPE) {
            return $pathPrefix;
        }

        $packageType = $package->getType();
        if (strpos($packageType, '-') === false) {
            return $pathPrefix . $basicPath;
        }

        $frameworkType = explode('-', $packageType)[0];
        // This should not happen, as self::supports should filter this out.
        if (!isset($this->installers[$frameworkType])) {
            return $pathPrefix . $basicPath;
        }

        /** @var MoodleInstaller|TotaraInstaller $installer */
        $installer = new $this->installers[$frameworkType]($package, $this->composer, $this->io);
        $path = $installer->getInstallPath($package, $frameworkType);
        if ($path) {
            return $pathPrefix . $path;
        }

        return $pathPrefix . $basicPath;
    }

    public function supports($packageType)
    {
        return strpos($packageType, 'kineo') !== false
               || strpos($packageType, 'moodle') !== false
               || strpos($packageType, 'totara') !== false
               || strpos($packageType, self::KINEO_TKE_TYPE) !== false;
    }

    /**
     * {@inheritDoc}
     */
    public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        $this->parseKineoAttributes($package);
        $promise = parent::install($repo, $package);
        if ($promise instanceof PromiseInterface) {
            // This is Composer 2 compatible.
            return $promise;
        }
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function update(InstalledRepositoryInterface $repo, PackageInterface $initial, PackageInterface $target)
    {
        $this->parseKineoAttributes($target);
        $this->backupIfNeeded($target);
        $promise = parent::update($repo, $initial, $target);
        $after = function () use ($target) {
            $this->restoreIfNeeded($target);
        };
        if ($promise instanceof PromiseInterface) {
            // This is Composer 2 compatible.
            return $promise->then($after);
        }
        // For Composer 1, just trigger the function synchronously.
        $after();
        return null;
    }

    private function getDefaultPackageInstallLocation(): string
    {
        if ($this->composer->getPackage()->getType() !== self::KINEO_CLIENT_TYPE) {
            return '';
        }

        $default = 'httpdocs' . DIRECTORY_SEPARATOR;

        // getenv might return false if the env variable is not found, so force it to null just in case.
        $envPrefix = getenv(self::KINEO_INSTALLER_TKE_ENV_KEY) ?: null;

        $prefix = $envPrefix
                  ?? $this->composer->getConfig()->get(self::KINEO_INSTALLER_TKE_CONFIG_KEY)
                     ?? $default;

        return rtrim($prefix, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    private function parseKineoAttributes(PackageInterface $package)
    {
        if (!isset($package->getExtra()['kineo'])) {
            return;
        }

        $wiki = false;
        if ($package instanceof CompletePackageInterface && isset($package->getSupport()['wiki'])) {
            $wiki = $package->getSupport()['wiki'];
        }

        $extra = $package->getExtra()['kineo'];

        $pluginRequirements = [
            'has-core-modifications' => 'has core modifications',
            'has-htaccess' => 'has modifications to the htaccess or vhost file',
            'has-config-init' => 'must be initialised by the config.php file',
        ];

        $warnings = [];
        foreach ($pluginRequirements as $requirement => $text) {
            if (isset($extra[$requirement]) && $extra[$requirement]) {
                $warnings[] = $text;
            }
        }

        if (count($warnings) == 0) {
            return;
        }

        $warningString = implode(', ', $warnings);
        if (count($warnings) > 1) {
            $last = array_pop($warnings);
            $warningString = implode(', ', $warnings);
            $warningString .= ', and ' . $last . '.';
        }
        $warningString = '<error>{name} ' . $warningString . '</error>';
        $wikiString = ($wiki && is_string($wiki))
            ? "<warning>Visit {$wiki} to find out what needs changing.</warning>"
            : "<warning>Visit the plugin wiki to find out what needs changing.</warning>";

        $warningString = str_replace('{name}', $package->getPrettyName(), $warningString);
        $this->io->write($warningString);
        $this->io->write($wikiString);
    }

    private function backupIfNeeded(PackageInterface $package)
    {
        $this->initCacheDir();
        $path = $this->getBackupPathFromPackage($package);
        $cachePath = $this->getCachePathFromPackage($package);
        if (!$path) {
            return;
        }
        if ((new Filesystem())->copy($path, $cachePath)) {
            $this->io->write(sprintf('<info>Backed up subplugin path "%s"</info>', $path), true, IOInterface::VERY_VERBOSE);
        }
    }

    private function restoreIfNeeded(PackageInterface $package)
    {
        $this->initCacheDir();
        $path = $this->getBackupPathFromPackage($package);
        $cachePath = $this->getCachePathFromPackage($package);
        if (!$path) {
            return;
        }
        if ((new Filesystem())->copy($cachePath, $path)) {
            $this->io->write(sprintf('<info>Restored subplugin path "%s"</info>', $path), true, IOInterface::VERY_VERBOSE);
        }
        (new Filesystem())->removeDirectory($cachePath);
    }

    private function initCacheDir()
    {
        if ($this->cacheDir) {
            return;
        }

        $config = Factory::createConfig();
        $this->cacheDir = $config->get('cache-dir') . '/kineo';
    }

    /**
     * @param PackageInterface $package
     * @return bool|string
     */
    private function getBackupPathFromPackage(PackageInterface $package)
    {
        if (!isset($package->getExtra()['kineo']['subplugin-path'])) {
            return false;
        }

        $path = rtrim($package->getExtra()['kineo']['subplugin-path'], DIRECTORY_SEPARATOR);
        if ($path[0] === '/') {
            $this->io->write('Absolute paths are not supported in kineo.subplugin-path', true, IOInterface::DEBUG);
            return false;
        }
        if (strpos($path, '..') !== false) {
            $this->io->write('Relative paths that traverse the parent directory are not supported in kineo.subplugin-path', true, IOInterface::DEBUG);
            return false;
        }

        $path = rtrim($this->getInstallPath($package), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $path;
        if (!is_dir($path) && !is_file($path)) {
            $this->io->write("kineo.subplugin-path '{$path}' not found", true, IOInterface::DEBUG);
            return false;
        }

        return $path;
    }

    /**
     * @param PackageInterface $package
     * @return string
     */
    private function getCachePathFromPackage(PackageInterface $package)
    {
        return $this->cacheDir . DIRECTORY_SEPARATOR . str_replace(DIRECTORY_SEPARATOR, '_', $package->getName());
    }
}
