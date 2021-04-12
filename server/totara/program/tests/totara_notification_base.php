<?php
/**
 * This file is part of Totara Learn
 *
 * Copyright (C) 2021 onwards Totara Learning Solutions LTD
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
 * @author Matthias Bonk <matthias.bonk@totaralearning.com>
 * @package totara_program
 */

use totara_core\advanced_feature;
use totara_job\job_assignment;
use totara_program\testing\generator as program_generator;

defined('MOODLE_INTERNAL') || die();

class totara_program_totara_notification_base extends advanced_testcase {

    protected function setup_programs(): stdClass {
        self::setAdminUser();

        $test_data = new stdClass();

        // Make sure it works with certifications turned off.
        set_config('enablecertifications', advanced_feature::DISABLED);

        $generator = self::getDataGenerator();
        $program_generator = program_generator::instance();

        // Create a user with two managers.
        $test_data->user1 = $generator->create_user(['lastname' => 'My user1 last name']);
        $manager1 = $generator->create_user(['lastname' => 'Manager1 last name']);
        $manager2 = $generator->create_user(['lastname' => 'Manager2 last name']);
        /** @var job_assignment $manager1job */
        $manager1job = job_assignment::create(['userid' => $manager1->id, 'idnumber' => 'job1']);
        /** @var job_assignment $manager2job */
        $manager2job = job_assignment::create(['userid' => $manager2->id, 'idnumber' => 'job2']);
        job_assignment::create([
            'userid' => $test_data->user1->id,
            'idnumber' => 'userjob1',
            'managerjaid' => $manager1job->id
        ]);
        job_assignment::create([
            'userid' => $test_data->user1->id,
            'idnumber' => 'userjob2',
            'managerjaid' => $manager2job->id
        ]);

        // Create two programs.
        $test_data->program1 = $program_generator->create_program(['fullname' => 'My program1 full name']);
        $test_data->program2 = $program_generator->create_program(['fullname' => 'My program2 full name']);

        // Create two courses.
        $course1 = $generator->create_course();
        $course2 = $generator->create_course();

        // Assign courses to program.
        $coursesetdata = [
            [
                'type' => CONTENTTYPE_MULTICOURSE,
                'nextsetoperator' => NEXTSETOPERATOR_THEN,
                'completiontype' => COMPLETIONTYPE_ALL,
                'certifpath' => CERTIFPATH_CERT,
                'courses' => [$course1]
            ],
            [
                'type' => CONTENTTYPE_MULTICOURSE,
                'nextsetoperator' => NEXTSETOPERATOR_THEN,
                'completiontype' => COMPLETIONTYPE_ALL,
                'certifpath' => CERTIFPATH_CERT,
                'courses' => [$course2]
            ],
        ];
        $program_generator->legacy_add_coursesets_to_program($test_data->program1, $coursesetdata);

        // Assign users to courses.
        $generator->enrol_user($test_data->user1->id, $course1->id);
        $generator->enrol_user($test_data->user1->id, $course2->id);

        // Assign user to program.
        $program_generator->assign_program($test_data->program1->id, [$test_data->user1->id]);

        $test_data->due_date = new DateTime('2020-10-25', new DateTimeZone('Pacific/Auckland'));
        $prog_compl1 = prog_load_completion($test_data->program1->id, $test_data->user1->id);
        $prog_compl1->timedue = $test_data->due_date->getTimestamp();
        self::assertTrue(prog_write_completion($prog_compl1));

        return $test_data;
    }
}
