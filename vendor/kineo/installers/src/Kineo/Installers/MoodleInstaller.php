<?php
/**
 * @copyright City & Guilds Kineo 2018
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace KineoInstaller\Installers;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;

/**
 * Class MoodleInstaller
 * Extends the core MoodleInstaller to include any sub-plugin types we define.
 *
 * @package KineoInstaller\Installers
 */
class MoodleInstaller extends \Composer\Installers\MoodleInstaller
{
    private $kineoLocations = [
        'isotope-provider' => 'local/isotope/providers/{$name}/',
        'mootivated-addon' => 'local/mootivated/addons/{$name}/',
        'migration' => 'local/core/migrations/{$name}/',
    ];

    public function __construct(PackageInterface $package = null, Composer $composer = null, IOInterface $io = null)
    {
        parent::__construct($package, $composer, $io);
        $this->locations = array_merge($this->locations, $this->kineoLocations);
    }
}
