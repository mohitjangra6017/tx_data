<?php
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2016 onwards Totara Learning Solutions LTD
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package mod_glossary
 * @category phpunit
 * @author Sam Hemelryk <sam.hemelryk@totaralearning.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/backup/util/includes/restore_includes.php');
require_once($CFG->dirroot . '/backup/moodle2/restore_stepslib.php');
require_once($CFG->dirroot . '/backup/moodle2/restore_activity_task.class.php');
require_once($CFG->dirroot . '/mod/glossary/backup/moodle2/restore_glossary_activity_task.class.php');


/**
 * Test the glossary backup activity task methods.
 */
class mod_glossary_restore_activity_task_testcase extends advanced_testcase {

    public function test_restore_decode_rules() {
        global $CFG;


        $restoreid = 89765;
        $www = $CFG->wwwroot;

        /** @var restore_decode_rule[] $rules */
        $rules = \restore_glossary_activity_task::define_decode_rules();

        \restore_controller_dbops::create_restore_temp_tables($restoreid);

        $original  = "This is some test content.\n\n";
        $original .= "$@GLOSSARYINDEX*7@$\n";
        $original .= "<a href='$@GLOSSARYINDEX*9@$'>$@GLOSSARYINDEX*9@$</a>\n\n";
        $original .= "$@GLOSSARYVIEWBYID*6@$\n";
        $original .= "<a href='$@GLOSSARYVIEWBYID*8@$'>$@GLOSSARYVIEWBYID*8@$</a>\n";
        $original .= "$@GLOSSARYVIEWBYID*5@$&name=value\n";
        $original .= "$@GLOSSARYSHOWENTRY*63*45@$\n";
        $original .= "$@GLOSSARYVIEWBYG*6@$\n";

        $expected  = "This is some test content.\n\n";
        $expected .= "{$www}/mod/glossary/index.php?id=7\n";
        $expected .= "<a href='{$www}/mod/glossary/index.php?id=9'>{$www}/mod/glossary/index.php?id=9</a>\n\n";
        $expected .= "{$www}/mod/glossary/view.php?id=6\n";
        $expected .= "<a href='{$www}/mod/glossary/view.php?id=8'>{$www}/mod/glossary/view.php?id=8</a>\n";
        $expected .= "{$www}/mod/glossary/view.php?id=5&name=value\n";
        $expected .= "{$www}/mod/glossary/showentry.php?courseid=63&eid=45\n";
        $expected .= "{$www}/mod/glossary/view.php?g=6\n";

        $actual = $original;
        foreach ($rules as $rule) {
            $rule->set_restoreid($restoreid);
            $rule->set_wwwroots($www, $www);
            $actual = $rule->decode($actual);
        }

        \restore_controller_dbops::drop_restore_temp_tables($restoreid);

        $this->assertSame($expected, $actual);
    }
}
