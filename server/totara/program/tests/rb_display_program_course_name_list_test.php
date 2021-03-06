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
 * @author Sam Hemelryk <sam.hemelryk@totaralearning.com>
 * @package totara_program
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/totara/reportbuilder/lib.php');

/**
 * @group totara_program
 */
class totara_program_rb_display_program_course_name_list_testcase extends advanced_testcase {

    /**
     * Test resorting when there are no courses.
     */
    public function test_resort_no_courses() {

        $resort = new ReflectionMethod(\totara_program\rb\display\program_course_name_list::class, 'resort');
        $resort->setAccessible(true);
        $resort->invoke(null, -1, [], []);

    }

    /**
     * Test resorting a single course.
     */
    public function test_resort_one_courses() {

        $resort = new ReflectionMethod(\totara_program\rb\display\program_course_name_list::class, 'resort');
        $resort->setAccessible(true);
        $data = [-2 => 'Test'];
        self::assertSame($data, $resort->invoke(null, -1, $data, $data));

    }

    /**
     * Test resorting multiple courses
     */
    public function test_resort_many_courses() {

        $resort = new ReflectionMethod(\totara_program\rb\display\program_course_name_list::class, 'resort');
        $resort->setAccessible(true);

        $cache = \cache::make('totara_program', 'course_order');
        $cache->set(-1, [-2, -6, -9]);

        $expected = [
            'Apples',
            'Bananas',
            'Carrots',
        ];
        self::assertSame($expected, $resort->invoke(null, -1, ['Apples', 'Bananas', 'Carrots'], [-2, -6, -9]));

        $cache->set(-1, [-6, -2, -9]);
        $expected = [
            'Bananas',
            'Apples',
            'Carrots',
        ];
        self::assertSame($expected, $resort->invoke(null, -1, ['Apples', 'Bananas', 'Carrots'], [-2, -6, -9]));

        $cache->set(-1, [-6, -9, -2]);
        $expected = [
            'Bananas',
            'Carrots',
            'Apples',
        ];
        self::assertSame($expected, $resort->invoke(null, -1, ['Apples', 'Bananas', 'Carrots'], [-2, -6, -9]));

    }

    /**
     * Tests the outcome when the cache is aware of more courses then are actually present in the results.
     */
    public function test_resort_additional_courses() {
        $resort = new ReflectionMethod(\totara_program\rb\display\program_course_name_list::class, 'resort');
        $resort->setAccessible(true);

        $cache = \cache::make('totara_program', 'course_order');
        $cache->set(-1, [-2, -6, -9, -11]);

        $expected = [
            'Apples',
            'Carrots',
        ];
        self::assertSame($expected, $resort->invoke(null, -1, ['Apples', 'Carrots'], [-2, -9]));
    }

    /**
     * Tests the outcome when the cache is aware of less courses then are actually present in the results.
     */
    public function test_resort_missing_courses() {
        $resort = new ReflectionMethod(\totara_program\rb\display\program_course_name_list::class, 'resort');
        $resort->setAccessible(true);

        $cache = \cache::make('totara_program', 'course_order');
        $cache->set(-1, [-2, -9]);

        $expected = [
            'Apples',
            'Carrots',
            'Bananas',
            'Donuts',
        ];
        self::assertSame($expected, $resort->invoke(null, -1, ['Apples', 'Bananas', 'Carrots', 'Donuts'], [-2, -6, -9, -11]));
        self::assertDebuggingCalled('Expected program courses count does not match cached course count, try purging your caches.');
    }

    /**
     * Tests the outcome when the courses do not match any of the cached courses.
     */
    public function test_resort_mismatch_courses() {
        $resort = new ReflectionMethod(\totara_program\rb\display\program_course_name_list::class, 'resort');
        $resort->setAccessible(true);

        $cache = \cache::make('totara_program', 'course_order');
        $cache->set(-1, [-2, -9]);

        $expected = [
            'Carrots',
            'Donuts',
        ];
        self::assertSame($expected, $resort->invoke(null, -1, ['Carrots', 'Donuts'], [-9, -11]));
        self::assertDebuggingCalled('Expected program courses count does not match cached course count, try purging your caches.');
    }

    /**
     * Test the display of no courses.
     *
     * In this test we expect the order given to the display function to corrected to match the cached state.
     * Here we are saying we don't trust the database to get it right.
     */
    public function test_display_no_courses_forced_resort() {
        $this->setAdminUser();
        self::force_resort_required(true);

        /** @var \totara_reportbuilder\testing\generator $generator */
        $generator = $this->getDataGenerator()->get_plugin_generator('totara_reportbuilder');
        $rid = $generator->create_default_custom_report(['shortname' => 'PO', 'source' => 'program_overview']);

        $report = reportbuilder::create($rid);
        $column = new rb_column('course', 'shortname', 'shortname', 'shortname', []);
        $row = new stdClass();

        $result = \totara_program\rb\display\program_course_name_list::display('', 'html', $row, $column, $report);
        self::assertSame('', $result);
    }

    /**
     * Test the display of no courses.
     *
     * In this test we expect the order given to the display function to stay exactly as it is.
     * The cache data should not be referenced as we trust the database order.
     */
    public function test_display_no_courses_forced_no_resort() {
        $this->setAdminUser();
        self::force_resort_required(false);

        /** @var \totara_reportbuilder\testing\generator $generator */
        $generator = $this->getDataGenerator()->get_plugin_generator('totara_reportbuilder');
        $rid = $generator->create_default_custom_report(['shortname' => 'PO', 'source' => 'program_overview']);

        $report = reportbuilder::create($rid);
        $column = new rb_column('course', 'shortname', 'shortname', 'shortname', []);
        $row = new stdClass();

        $result = \totara_program\rb\display\program_course_name_list::display('', 'html', $row, $column, $report);
        self::assertSame('', $result);
    }

    /**
     * Test the display of one course.
     *
     * In this test we expect the order given to the display function to corrected to match the cached state.
     * Here we are saying we don't trust the database to get it right.
     */
    public function test_display_one_course_forced_resort() {
        $this->setAdminUser();
        self::force_resort_required(true);

        /** @var \totara_reportbuilder\testing\generator $generator */
        $generator = $this->getDataGenerator()->get_plugin_generator('totara_reportbuilder');
        $rid = $generator->create_default_custom_report(['shortname' => 'PO', 'source' => 'program_overview']);

        $report = reportbuilder::create($rid);
        $column = new rb_column('course', 'shortname', 'shortname', 'shortname', []);
        $row = new stdClass();

        $result = \totara_program\rb\display\program_course_name_list::display('-1|-2|Test', 'html', $row, $column, $report);
        self::assertSame('<a href="https://www.example.com/moodle/course/view.php?id=-2">Test</a>', $result);
    }

    /**
     * Test the display of one course.
     *
     * In this test we expect the order given to the display function to stay exactly as it is.
     * The cache data should not be referenced as we trust the database order.
     */
    public function test_display_one_course_forced_no_resort() {
        $this->setAdminUser();
        self::force_resort_required(false);

        /** @var \totara_reportbuilder\testing\generator $generator */
        $generator = $this->getDataGenerator()->get_plugin_generator('totara_reportbuilder');
        $rid = $generator->create_default_custom_report(['shortname' => 'PO', 'source' => 'program_overview']);

        $report = reportbuilder::create($rid);
        $column = new rb_column('course', 'shortname', 'shortname', 'shortname', []);
        $row = new stdClass();

        $result = \totara_program\rb\display\program_course_name_list::display('-1|-2|Test', 'html', $row, $column, $report);
        self::assertSame('<a href="https://www.example.com/moodle/course/view.php?id=-2">Test</a>', $result);
    }

    /**
     * Test the display function with resort forced on.
     *
     * In this test we expect the order given to the display function to corrected to match the cached state.
     * Here we are saying we don't trust the database to get it right.
     *
     * @throws coding_exception
     */
    public function test_display_many_courses_forced_resort() {
        $this->setAdminUser();
        self::force_resort_required(true);

        /** @var \totara_reportbuilder\testing\generator $generator */
        $generator = $this->getDataGenerator()->get_plugin_generator('totara_reportbuilder');
        $rid = $generator->create_default_custom_report(['shortname' => 'PO', 'source' => 'program_overview']);

        $report = reportbuilder::create($rid);
        $column = new rb_column('course', 'shortname', 'shortname', 'shortname', []);
        $row = new stdClass();

        $result = \totara_program\rb\display\program_course_name_list::display('-1|-2|Test', 'html', $row, $column, $report);
        self::assertSame('<a href="https://www.example.com/moodle/course/view.php?id=-2">Test</a>', $result);

        $cache = \cache::make('totara_program', 'course_order');
        $cache->set(-1, [-2, -6, -9]);

        $data = join($report->src->get_uniquedelimiter(), [
            '-1|-2|Apples',
            '-1|-6|Bananas',
            '-1|-9|Carrots',
        ]);
        $expected = join("\n", [
            '<a href="https://www.example.com/moodle/course/view.php?id=-2">Apples</a>',
            '<a href="https://www.example.com/moodle/course/view.php?id=-6">Bananas</a>',
            '<a href="https://www.example.com/moodle/course/view.php?id=-9">Carrots</a>',
        ]);

        self::assertSame($expected, \totara_program\rb\display\program_course_name_list::display($data, 'html', $row, $column, $report));

        $cache->set(-1, [-6, -2, -9]);
        $expected = join("\n", [
            '<a href="https://www.example.com/moodle/course/view.php?id=-6">Bananas</a>',
            '<a href="https://www.example.com/moodle/course/view.php?id=-2">Apples</a>',
            '<a href="https://www.example.com/moodle/course/view.php?id=-9">Carrots</a>',
        ]);
        self::assertSame($expected, \totara_program\rb\display\program_course_name_list::display($data, 'html', $row, $column, $report));

        $cache->set(-1, [-6, -9, -2]);
        $expected = join("\n", [
            '<a href="https://www.example.com/moodle/course/view.php?id=-6">Bananas</a>',
            '<a href="https://www.example.com/moodle/course/view.php?id=-9">Carrots</a>',
            '<a href="https://www.example.com/moodle/course/view.php?id=-2">Apples</a>',
        ]);
        self::assertSame($expected, \totara_program\rb\display\program_course_name_list::display($data, 'html', $row, $column, $report));
    }

    /**
     * Test the display function with resort forced off.
     *
     * In this test we expect the order given to the display function to stay exactly as it is.
     * The cache data should not be referenced as we trust the database order.
     *
     * @throws coding_exception
     */
    public function test_display_many_courses_forced_no_resort() {
        $this->setAdminUser();
        self::force_resort_required(false);

        /** @var \totara_reportbuilder\testing\generator $generator */
        $generator = $this->getDataGenerator()->get_plugin_generator('totara_reportbuilder');
        $rid = $generator->create_default_custom_report(['shortname' => 'PO', 'source' => 'program_overview']);

        $report = reportbuilder::create($rid);
        $column = new rb_column('course', 'shortname', 'shortname', 'shortname', []);
        $row = new stdClass();

        $result = \totara_program\rb\display\program_course_name_list::display('-1|-2|Test', 'html', $row, $column, $report);
        self::assertSame('<a href="https://www.example.com/moodle/course/view.php?id=-2">Test</a>', $result);

        $cache = \cache::make('totara_program', 'course_order');
        $cache->set(-1, [-2, -6, -9]);

        $data = join($report->src->get_uniquedelimiter(), [
            '-1|-2|Apples',
            '-1|-6|Bananas',
            '-1|-9|Carrots',
        ]);
        $expected = join("\n", [
            '<a href="https://www.example.com/moodle/course/view.php?id=-2">Apples</a>',
            '<a href="https://www.example.com/moodle/course/view.php?id=-6">Bananas</a>',
            '<a href="https://www.example.com/moodle/course/view.php?id=-9">Carrots</a>',
        ]);

        self::assertSame($expected, \totara_program\rb\display\program_course_name_list::display($data, 'html', $row, $column, $report));

        $cache->set(-1, [-6, -2, -9]);
        $expected = join("\n", [
            '<a href="https://www.example.com/moodle/course/view.php?id=-2">Apples</a>',
            '<a href="https://www.example.com/moodle/course/view.php?id=-6">Bananas</a>',
            '<a href="https://www.example.com/moodle/course/view.php?id=-9">Carrots</a>',
        ]);
        self::assertSame($expected, \totara_program\rb\display\program_course_name_list::display($data, 'html', $row, $column, $report));

        $cache->set(-1, [-6, -9, -2]);
        $expected = join("\n", [
            '<a href="https://www.example.com/moodle/course/view.php?id=-2">Apples</a>',
            '<a href="https://www.example.com/moodle/course/view.php?id=-6">Bananas</a>',
            '<a href="https://www.example.com/moodle/course/view.php?id=-9">Carrots</a>',
        ]);
        self::assertSame($expected, \totara_program\rb\display\program_course_name_list::display($data, 'html', $row, $column, $report));
    }

    /**
     * Force the display class to resort, or not.
     *
     * This is database dependent normally, but we can test it by manually forcing it and checking the outcome is what we expect
     * when it is on and when it is off.
     *
     * @param bool|null $value True or false, null to reset it so that it is calculated again.
     */
    private static function force_resort_required(?bool $value = null) {
        $property = new ReflectionProperty(\totara_program\rb\display\program_course_base::class, 'resort_required');
        $property->setAccessible(true);
        $property->setValue(null, $value);
    }

    /**
     * Make sure that we reset self::force_resort_required()
     */
    public function tearDown(): void {
        self::force_resort_required(null);
        parent::tearDown();
    }
}