<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * PHPUnit related utilities.
 *
 * Exit codes: {@see phpunit_bootstrap_error()}
 *
 * @package    tool_phpunit
 * @copyright  2012 Petr Skoda {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if (isset($_SERVER['REMOTE_ADDR'])) {
    die; // no access from web!
}

define('IGNORE_COMPONENT_CACHE', true);

define('TOOL_PHPUNIT_DIR_ROOT', realpath(__DIR__ . '/../../../../..'));
define('TOOL_PHPUNIT_DIR_SERVER', realpath(TOOL_PHPUNIT_DIR_ROOT . '/server'));
define('TOOL_PHPUNIT_DIR_VENDOR', realpath(TOOL_PHPUNIT_DIR_ROOT . '/test/phpunit/vendor'));

require_once(TOOL_PHPUNIT_DIR_SERVER . '/lib/clilib.php');
require_once(TOOL_PHPUNIT_DIR_SERVER . '/lib/phpunit/bootstraplib.php');
require_once(TOOL_PHPUNIT_DIR_SERVER . '/lib/testing/lib.php');

// now get cli options
list($options, $unrecognized) = cli_get_params(
    array(
        'drop'                  => false,
        'install'               => false,
        'instance'              => 0,
        'buildconfig'           => false,
        'buildcomponentconfigs' => false,
        'diag'                  => false,
        'run'                   => false,
        'help'                  => false,
    ),
    array(
        'h' => 'help'
    )
);

if (file_exists(TOOL_PHPUNIT_DIR_VENDOR . '/phpunit/phpunit/composer.json')) {
    // Composer packages present.
    require_once(TOOL_PHPUNIT_DIR_VENDOR. '/autoload.php');

} else {
    // Note: installation via PEAR is not supported any more.
    phpunit_bootstrap_error(PHPUNIT_EXITCODE_PHPUNITMISSING);
}

$instance = empty($options['instance']) ? 0 : intval($options['instance']);
if ($instance < 0 or $instance > 99) {
    cli_error('Instance number must be a positive number smaller than 100');
}
define('PHPUNIT_INSTANCE', str_pad((string)$instance, 2, '0', STR_PAD_LEFT));

if ($options['run']) {
    unset($options);
    unset($unrecognized);

    foreach ($_SERVER['argv'] as $k=>$v) {
        if (strpos($v, '--run') === 0 or strpos($v, '--instance') === 0) {
            unset($_SERVER['argv'][$k]);
            $_SERVER['argc'] = $_SERVER['argc'] - 1;
        }
    }
    $_SERVER['argv'] = array_values($_SERVER['argv']);
    \PHPUnit\TextUI\Command::main(true);
    exit(0); // Not reached.
}

define('CACHE_DISABLE_ALL', true); // Totara: do not cache anything!
define('PHPUNIT_UTIL', true);

require(TOOL_PHPUNIT_DIR_SERVER . '/lib/phpunit/bootstrap.php');

// from now on this is a regular moodle CLI_SCRIPT

require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->libdir.'/upgradelib.php');
require_once($CFG->libdir.'/clilib.php');
require_once($CFG->libdir.'/installlib.php');

if ($unrecognized) {
    $unrecognized = implode("\n  ", $unrecognized);
    cli_error(get_string('cliunknowoption', 'admin', $unrecognized));
}

$diag = $options['diag'];
$drop = $options['drop'];
$install = $options['install'];
$buildconfig = $options['buildconfig'];
$buildcomponentconfigs = $options['buildcomponentconfigs'];

if ($options['help'] or (!$drop and !$install and !$buildconfig and !$buildcomponentconfigs and !$diag)) {
    $help = "Various PHPUnit utility functions

Options:
--drop         Drop database and dataroot
--install      Install database
--diag         Diagnose installation and return error code only
--run          Execute PHPUnit tests (alternative for standard phpunit binary)
--buildconfig  Build /phpunit.xml from /phpunit.xml.dist that runs all tests
--buildcomponentconfigs
               Build distributed phpunit.xml files for each component
--instance=n   Test environment instance number, defaults to 0

-h, --help     Print out this help

Example:
\$ php ".testing_cli_argument_path('/admin/tool/phpunit/cli/util.php')." --install
";
    echo $help;
    exit(0);
}

if ($diag) {
    list($errorcode, $message) = \core_phpunit\internal_util::testing_ready_problem();
    if ($errorcode) {
        phpunit_bootstrap_error($errorcode, $message);
    }
    exit(0);

} else if ($buildconfig) {
    if (\core_phpunit\internal_util::build_config_file()) {
        exit(0);
    } else {
        phpunit_bootstrap_error(PHPUNIT_EXITCODE_CONFIGWARNING, 'Can not create main /phpunit.xml configuration file, verify srcroot permissions');
    }

} else if ($buildcomponentconfigs) {
    \core_phpunit\internal_util::build_component_config_files();
    exit(0);

} else if ($drop) {
    // make sure tests do not run in parallel
    \core_phpunit\internal_util::acquire_lock();
    \core_phpunit\internal_util::drop_site(true);
    // note: we must stop here because $CFG is messed up and we can not reinstall, sorry
    exit(0);

} else if ($install) {
    \core_phpunit\internal_util::install_site();
    exit(0);
}
