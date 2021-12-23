<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace KineoInstaller\Installers;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;

/**
 * Class TotaraInstaller
 * Extends the MoodleInstaller, changing all locations to be in the server/ directory,
 * and adds the _component_ type to install into the client directory.
 *
 * @package KineoInstaller\Installers
 */
class TotaraInstaller extends MoodleInstaller
{
    protected $totaraLocations = [
        'component' => 'client/component/{$name}/',
    ];

    public function __construct(PackageInterface $package = null, Composer $composer = null, IOInterface $io = null)
    {
        parent::__construct($package, $composer, $io);
        foreach ($this->locations as &$location) {
            $location = 'server/' . $location;
        }
        $this->locations = array_merge($this->locations, $this->totaraLocations);
    }
}