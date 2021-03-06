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
 * Database driver test case.
 *
 * @package    core_phpunit
 * @copyright  2012 Petr Skoda {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace core_phpunit;


/**
 * Special test case for testing of DML drivers and DDL layer.
 *
 * Note: Use only 'test_table*' names when creating new tables.
 *
 * For DML/DDL developers: you can add following settings to config.php if you want to test different driver than the main one,
 *                         the reason is to allow testing of incomplete drivers that do not allow full PHPUnit environment
 *                         initialisation (the database can be empty).
 * $CFG->phpunit_extra_drivers = array(
 *      1=>array('dbtype'=>'mysqli', 'dbhost'=>'localhost', 'dbname'=>'moodle', 'dbuser'=>'root', 'dbpass'=>'', 'prefix'=>'phpu2_'),
 *      2=>array('dbtype'=>'pgsql', 'dbhost'=>'localhost', 'dbname'=>'moodle', 'dbuser'=>'postgres', 'dbpass'=>'', 'prefix'=>'phpu2_'),
 *      3=>array('dbtype'=>'sqlsrv', 'dbhost'=>'127.0.0.1', 'dbname'=>'moodle', 'dbuser'=>'sa', 'dbpass'=>'', 'prefix'=>'phpu2_'),
 *      4=>array('dbtype'=>'oci', 'dbhost'=>'127.0.0.1', 'dbname'=>'XE', 'dbuser'=>'sa', 'dbpass'=>'', 'prefix'=>'t_'),
 * );
 * define('PHPUNIT_TEST_DRIVER')=1; //number is index in the previous array
 *
 * @package    core_phpunit
 * @copyright  2012 Petr Skoda {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class database_driver_testcase extends testcase {
    /** @var \moodle_database connection to extra database */
    private static $extradb = null;

    /** @var \moodle_database used in these tests*/
    protected $tdb;

    public static function setUpBeforeClass(): void {
        global $CFG;
        parent::setUpBeforeClass();

        if (!defined('PHPUNIT_TEST_DRIVER')) {
            // use normal $DB
            return;
        }

        if (!isset($CFG->phpunit_extra_drivers[PHPUNIT_TEST_DRIVER])) {
            throw new \Exception('Can not find driver configuration options with index: '.PHPUNIT_TEST_DRIVER);
        }

        $dblibrary = empty($CFG->phpunit_extra_drivers[PHPUNIT_TEST_DRIVER]['dblibrary']) ? 'native' : $CFG->phpunit_extra_drivers[PHPUNIT_TEST_DRIVER]['dblibrary'];
        $dbtype = $CFG->phpunit_extra_drivers[PHPUNIT_TEST_DRIVER]['dbtype'];
        $dbhost = $CFG->phpunit_extra_drivers[PHPUNIT_TEST_DRIVER]['dbhost'];
        $dbname = $CFG->phpunit_extra_drivers[PHPUNIT_TEST_DRIVER]['dbname'];
        $dbuser = $CFG->phpunit_extra_drivers[PHPUNIT_TEST_DRIVER]['dbuser'];
        $dbpass = $CFG->phpunit_extra_drivers[PHPUNIT_TEST_DRIVER]['dbpass'];
        $prefix = $CFG->phpunit_extra_drivers[PHPUNIT_TEST_DRIVER]['prefix'];
        $dboptions = empty($CFG->phpunit_extra_drivers[PHPUNIT_TEST_DRIVER]['dboptions']) ? array() : $CFG->phpunit_extra_drivers[PHPUNIT_TEST_DRIVER]['dboptions'];

        $classname = "{$dbtype}_{$dblibrary}_moodle_database";
        require_once("$CFG->libdir/dml/$classname.php");
        $d = new $classname();
        if (!$d->driver_installed()) {
            throw new \Exception('Database driver for '.$classname.' is not installed');
        }

        $d->connect($dbhost, $dbuser, $dbpass, $dbname, $prefix, $dboptions);

        self::$extradb = $d;
    }

    protected function setUp(): void {
        global $DB;
        parent::setUp();

        if (self::$extradb) {
            $this->tdb = self::$extradb;
        } else {
            $this->tdb = $DB;
        }
    }

    protected function tearDown(): void {
        // Totara: undo any transaction leftovers.
        if ($this->tdb->is_transaction_started()) {
            $this->tdb->force_transaction_rollback();
        }
        // delete all test tables
        $dbman = $this->tdb->get_manager();
        $tables = $this->tdb->get_tables(false);
        foreach($tables as $tablename) {
            if (strpos($tablename, 'test_table') === 0) {
                $table = new \xmldb_table($tablename);
                $dbman->drop_table($table);
            }
        }
        parent::tearDown();
    }

    public static function tearDownAfterClass(): void {
        if (self::$extradb) {
            self::$extradb->dispose();
            self::$extradb = null;
        }
        internal_util::reset_all_data(null);
        parent::tearDownAfterClass();
    }
}
