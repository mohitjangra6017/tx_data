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
 * @package mod_folder
 * @category phpunit
 * @author Sam Hemelryk <sam.hemelryk@totaralearning.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/backup/util/includes/backup_includes.php');
require_once($CFG->dirroot . '/backup/moodle2/backup_stepslib.php');
require_once($CFG->dirroot . '/backup/moodle2/backup_activity_task.class.php');
require_once($CFG->dirroot . '/mod/folder/backup/moodle2/backup_folder_activity_task.class.php');


/**
 * Test the folder backup activity task methods.
 */
class mod_folder_backup_activity_task_testcase extends advanced_testcase {

    /**
     * Tests calling encode_content_links without content that should have no matches.
     */
    public function test_encode_content_links_with_no_matches() {
        global $CFG;
        // First up test things that should not lead to replacement.
        $this->assertSame('', backup_folder_activity_task::encode_content_links(''));
        $this->assertSame('Test', backup_folder_activity_task::encode_content_links('Test'));
        $this->assertSame($CFG->wwwroot, backup_folder_activity_task::encode_content_links($CFG->wwwroot));
        $this->assertSame(
            "<a href='{$CFG->wwwroot}'>wwwroot</a>",
            backup_folder_activity_task::encode_content_links("<a href='{$CFG->wwwroot}'>wwwroot</a>")
        );
    }

    /**
     * Test encode_content_links can convert index links when called without a task.
     */
    public function test_encode_content_links_index_without_a_task() {
        global $CFG;

        // Test index.php links.
        $this->assertSame(
            '$@FOLDERINDEX*3@$',
            backup_folder_activity_task::encode_content_links($CFG->wwwroot.'/mod/folder/index.php?id=3')
        );
        $this->assertSame(
            '$@FOLDERINDEX*987654321@$',
            backup_folder_activity_task::encode_content_links($CFG->wwwroot.'/mod/folder/index.php?id=987654321')
        );
        $this->assertSame(
            '<a href="$@FOLDERINDEX*987654321@$">$@FOLDERINDEX*987654321@$</a>',
            backup_folder_activity_task::encode_content_links(
                '<a href="'.$CFG->wwwroot.'/mod/folder/index.php?id=987654321">'.$CFG->wwwroot.'/mod/folder/index.php?id=987654321</a>'
            )
        );
        $this->assertSame(
            '<a href="/mod/folder/index.php?id=64">/mod/folder/index.php?id=64</a>',
            backup_folder_activity_task::encode_content_links(
                '<a href="/mod/folder/index.php?id=64">/mod/folder/index.php?id=64</a>'
            )
        );
        $this->assertSame(
            '<a href="$@FOLDERINDEX*987654321@$#anchor">$@FOLDERINDEX*987654321@$#anchor</a>',
            backup_folder_activity_task::encode_content_links(
                '<a href="'.$CFG->wwwroot.'/mod/folder/index.php?id=987654321#anchor">'.$CFG->wwwroot.'/mod/folder/index.php?id=987654321#anchor</a>'
            )
        );
        $this->assertSame(
            '<a href="$@FOLDERINDEX*987654321@$&arg=value">$@FOLDERINDEX*987654321@$&arg=value</a>',
            backup_folder_activity_task::encode_content_links(
                '<a href="'.$CFG->wwwroot.'/mod/folder/index.php?id=987654321&arg=value">'.$CFG->wwwroot.'/mod/folder/index.php?id=987654321&arg=value</a>'
            )
        );
    }

    /**
     * Test encode_content_links can convert index links when called with a valid backup task.
     */
    public function test_encode_content_links_index_with_a_task() {
        global $CFG, $USER;

        $this->setAdminUser();

        $generator = $this->getDataGenerator();

        $course1 = $generator->create_course();
        $course2 = $generator->create_course();

        $module1 = $generator->create_module('folder', array('course' => $course1));
        $module2 = $generator->create_module('folder', array('course' => $course2));

        // Turn off file logging, otherwise it can't delete the file (Windows).
        $CFG->backup_file_logger_level = backup::LOG_NONE;

        // Do backup with default settings. MODE_IMPORT means it will just create the directory and not zip it.
        $bc = new backup_controller(backup::TYPE_1COURSE, $course1->id, backup::FORMAT_MOODLE, backup::INTERACTIVE_NO,
                                    backup::MODE_IMPORT, $USER->id);
        $tasks = $bc->get_plan()->get_tasks();

        // We need a task to test with, it doesn't matter which, but we'll use the root task.
        $roottask = null;
        foreach ($tasks as $task) {
            if ($task instanceof backup_root_task) {
                $roottask = $task;
                break;
            }
        }
        $this->assertNotEmpty($roottask, 'Unable to find the root backup task');

        // We expect the module in course 1 to be encoded, but not the module in course 2.
        $this->assertSame(
            '$@FOLDERINDEX*'.$course1->id.'@$',
            backup_folder_activity_task::encode_content_links($CFG->wwwroot.'/mod/folder/index.php?id='.$course1->id, $roottask)
        );
        $this->assertSame(
            $CFG->wwwroot.'/mod/folder/index.php?id='.$course2->id,
            backup_folder_activity_task::encode_content_links($CFG->wwwroot.'/mod/folder/index.php?id='.$course2->id, $roottask)
        );
        $this->assertSame(
            $CFG->wwwroot.'/mod/folder/index.php?id=987654321',
            backup_folder_activity_task::encode_content_links($CFG->wwwroot.'/mod/folder/index.php?id=987654321', $roottask)
        );

        // Test no relative URL's get encoded.
        $this->assertSame(
            '/mod/folder/index.php?id='.$course1->id,
            backup_folder_activity_task::encode_content_links('/mod/folder/index.php?id='.$course1->id, $roottask)
        );
        $this->assertSame(
            '/mod/folder/index.php?id='.$course2->id,
            backup_folder_activity_task::encode_content_links('/mod/folder/index.php?id='.$course2->id, $roottask)
        );
        $this->assertSame(
            '/mod/folder/index.php?id=987654321',
            backup_folder_activity_task::encode_content_links('/mod/folder/index.php?id=987654321', $roottask)
        );

        // Now test the correct URL's with additional anchors and arguments.
        $url = $CFG->wwwroot.'/mod/folder/index.php?id='.$course1->id.'#anchor';
        $this->assertSame(
            '<a href="$@FOLDERINDEX*'.$course1->id.'@$#anchor">$@FOLDERINDEX*'.$course1->id.'@$#anchor</a>',
            backup_folder_activity_task::encode_content_links('<a href="'.$url.'">'.$url.'</a>', $roottask)
        );
        $url = $CFG->wwwroot.'/mod/folder/index.php?id='.$course2->id.'#anchor';
        $this->assertSame(
            '<a href="'.$url.'">'.$url.'</a>',
            backup_folder_activity_task::encode_content_links('<a href="'.$url.'">'.$url.'</a>', $roottask)
        );
        $url = $CFG->wwwroot.'/mod/folder/index.php?id='.$course1->id.'&arg=value';
        $this->assertSame(
            '<a href="$@FOLDERINDEX*'.$course1->id.'@$&arg=value">$@FOLDERINDEX*'.$course1->id.'@$&arg=value</a>',
            backup_folder_activity_task::encode_content_links('<a href="'.$url.'">'.$url.'</a>', $roottask)
        );
        $url = $CFG->wwwroot.'/mod/folder/index.php?id='.$course2->id.'&arg=value';
        $this->assertSame(
            '<a href="'.$url.'">'.$url.'</a>',
            backup_folder_activity_task::encode_content_links('<a href="'.$url.'">'.$url.'</a>', $roottask)
        );
    }

    /**
     * Test encode_content_links can convert view links when called without a task.
     */
    public function test_encode_content_links_view_without_a_task() {
        global $CFG;

        // Test view.php links.
        $this->assertSame(
            '$@FOLDERVIEWBYID*3@$',
            backup_folder_activity_task::encode_content_links($CFG->wwwroot.'/mod/folder/view.php?id=3')
        );
        $this->assertSame(
            '$@FOLDERVIEWBYID*987654321@$',
            backup_folder_activity_task::encode_content_links($CFG->wwwroot.'/mod/folder/view.php?id=987654321')
        );
        $this->assertSame(
            '<a href="$@FOLDERVIEWBYID*987654321@$">$@FOLDERVIEWBYID*987654321@$</a>',
            backup_folder_activity_task::encode_content_links(
                '<a href="'.$CFG->wwwroot.'/mod/folder/view.php?id=987654321">'.$CFG->wwwroot.'/mod/folder/view.php?id=987654321</a>'
            )
        );
        $this->assertSame(
            '<a href="/mod/folder/view.php?id=64">/mod/folder/view.php?id=64</a>',
            backup_folder_activity_task::encode_content_links(
                '<a href="/mod/folder/view.php?id=64">/mod/folder/view.php?id=64</a>'
            )
        );
        $this->assertSame(
            '<a href="$@FOLDERVIEWBYID*987654321@$#anchor">$@FOLDERVIEWBYID*987654321@$#anchor</a>',
            backup_folder_activity_task::encode_content_links(
                '<a href="'.$CFG->wwwroot.'/mod/folder/view.php?id=987654321#anchor">'.$CFG->wwwroot.'/mod/folder/view.php?id=987654321#anchor</a>'
            )
        );
        $this->assertSame(
            '<a href="$@FOLDERVIEWBYID*987654321@$&arg=value">$@FOLDERVIEWBYID*987654321@$&arg=value</a>',
            backup_folder_activity_task::encode_content_links(
                '<a href="'.$CFG->wwwroot.'/mod/folder/view.php?id=987654321&arg=value">'.$CFG->wwwroot.'/mod/folder/view.php?id=987654321&arg=value</a>'
            )
        );
    }

    /**
     * Test encode_content_links can convert view links when called with a valid task.
     */
    public function test_encode_content_links_view_with_a_task() {
        global $CFG, $USER;

        $this->setAdminUser();

        $generator = $this->getDataGenerator();

        $course1 = $generator->create_course();
        $course2 = $generator->create_course();

        $module1 = $generator->create_module('folder', array('course' => $course1));
        $module2 = $generator->create_module('folder', array('course' => $course2));

        // Turn off file logging, otherwise it can't delete the file (Windows).
        $CFG->backup_file_logger_level = backup::LOG_NONE;

        // Do backup with default settings. MODE_IMPORT means it will just create the directory and not zip it.
        $bc = new backup_controller(backup::TYPE_1COURSE, $course1->id, backup::FORMAT_MOODLE, backup::INTERACTIVE_NO,
            backup::MODE_IMPORT, $USER->id);
        $tasks = $bc->get_plan()->get_tasks();

        // We need a task to test with, it doesn't matter which, but we'll use the root task.
        $roottask = null;
        foreach ($tasks as $task) {
            if ($task instanceof backup_root_task) {
                $roottask = $task;
                break;
            }
        }
        $this->assertNotEmpty($roottask, 'Unable to find the root backup task');

        // We expect the module in course 1 to be encoded, but not the module in course 2.
        $this->assertSame(
            '$@FOLDERVIEWBYID*'.$module1->cmid.'@$',
            backup_folder_activity_task::encode_content_links($CFG->wwwroot.'/mod/folder/view.php?id='.$module1->cmid, $roottask)
        );
        $this->assertSame(
            $CFG->wwwroot.'/mod/folder/view.php?id='.$module2->cmid,
            backup_folder_activity_task::encode_content_links($CFG->wwwroot.'/mod/folder/view.php?id='.$module2->cmid, $roottask)
        );
        $this->assertSame(
            $CFG->wwwroot.'/mod/folder/view.php?id=987654321',
            backup_folder_activity_task::encode_content_links($CFG->wwwroot.'/mod/folder/view.php?id=987654321', $roottask)
        );

        // Test no relative URL's get encoded.
        $this->assertSame(
            '/mod/folder/view.php?id='.$module1->cmid,
            backup_folder_activity_task::encode_content_links('/mod/folder/view.php?id='.$module1->cmid, $roottask)
        );
        $this->assertSame(
            '/mod/folder/view.php?id='.$module2->cmid,
            backup_folder_activity_task::encode_content_links('/mod/folder/view.php?id='.$module2->cmid, $roottask)
        );
        $this->assertSame(
            '/mod/folder/view.php?id=987654321',
            backup_folder_activity_task::encode_content_links('/mod/folder/view.php?id=987654321', $roottask)
        );

        // Now test the correct URL's with additional anchors and arguments.
        $url = $CFG->wwwroot.'/mod/folder/view.php?id='.$module1->cmid.'#anchor';
        $this->assertSame(
            '<a href="$@FOLDERVIEWBYID*'.$module1->cmid.'@$#anchor">$@FOLDERVIEWBYID*'.$module1->cmid.'@$#anchor</a>',
            backup_folder_activity_task::encode_content_links('<a href="'.$url.'">'.$url.'</a>', $roottask)
        );
        $url = $CFG->wwwroot.'/mod/folder/view.php?id='.$module2->cmid.'#anchor';
        $this->assertSame(
            '<a href="'.$url.'">'.$url.'</a>',
            backup_folder_activity_task::encode_content_links('<a href="'.$url.'">'.$url.'</a>', $roottask)
        );
        $url = $CFG->wwwroot.'/mod/folder/view.php?id=546#anchor';
        $this->assertSame(
            '<a href="'.$url.'">'.$url.'</a>',
            backup_folder_activity_task::encode_content_links('<a href="'.$url.'">'.$url.'</a>', $roottask)
        );
        $url = $CFG->wwwroot.'/mod/folder/view.php?id='.$module1->cmid.'&arg=value';
        $this->assertSame(
            '<a href="$@FOLDERVIEWBYID*'.$module1->cmid.'@$&arg=value">$@FOLDERVIEWBYID*'.$module1->cmid.'@$&arg=value</a>',
            backup_folder_activity_task::encode_content_links('<a href="'.$url.'">'.$url.'</a>', $roottask)
        );
        $url = $CFG->wwwroot.'/mod/folder/view.php?id='.$module2->cmid.'&arg=value';
        $this->assertSame(
            '<a href="'.$url.'">'.$url.'</a>',
            backup_folder_activity_task::encode_content_links('<a href="'.$url.'">'.$url.'</a>', $roottask)
        );
        $url = $CFG->wwwroot.'/mod/folder/view.php?id=546&arg=value';
        $this->assertSame(
            '<a href="'.$url.'">'.$url.'</a>',
            backup_folder_activity_task::encode_content_links('<a href="'.$url.'">'.$url.'</a>', $roottask)
        );

        // Now check that the correct activity id doesn't get converted (it should only convert the cmid).
        // $url = $CFG->wwwroot.'/mod/folder/view.php?id='.$module1->id;
        // $this->assertSame(
        //     '<a href="'.$url.'">'.$url.'</a>',
        //     backup_folder_activity_task::encode_content_links('<a href="'.$url.'">'.$url.'</a>', $roottask)
        // );
    }
}
