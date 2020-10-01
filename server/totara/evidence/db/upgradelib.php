<?php
/**
 * This file is part of Totara Learn
 *
 * Copyright (C) 2019 onwards Totara Learning Solutions LTD
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
 * @author Mark Metcalfe <mark.metcalfe@totaralearning.com>
 * @package totara_evidence
 */

/**
 * Create the system evidence types needed for the completion history import tool
 */
function totara_evidence_create_completion_types() {
    global $DB;

    $now = time();
    $admin = get_admin()->id;

    // Templates
    $type = [
        'descriptionformat' => FORMAT_HTML,
        'created_at' => $now,
        'modified_at' => $now,
        'created_by' => $admin,
        'modified_by' => $admin,
        'location' => 1, // Equal to \totara_evidence\models\evidence_type::LOCATION_RECORD_OF_LEARNING
        'status' => 0, // Equal to \totara_evidence\models\evidence_type::STATUS_HIDDEN
    ];
    $text_field = [
        'datatype' => 'text',
        'hidden' => 0,
        'locked' => 0,
        'required' => 0,
        'forceunique' => 0,
        'defaultdata' => '',
        'param1' => 30,
        'param2' => 2048,
    ];
    $date_field = [
        'datatype' => 'datetime',
        'hidden' => 0,
        'locked' => 0,
        'required' => 0,
        'forceunique' => 0,
        'defaultdata' => 0,
        'param1' => 1919,
        'param2' => 2038,
    ];

    $transaction = $DB->start_delegated_transaction();

    // Course type and its fields
    // Note: The shortnames must match get_columnnames() in totara/completionimport/lib.php
    if (!$DB->record_exists('totara_evidence_type', ['idnumber' => 'coursecompletionimport'])) {
        $course_type = $DB->insert_record('totara_evidence_type', array_merge($type, [
            'name' => 'multilang:completion_course',
            'idnumber' => 'coursecompletionimport',
            'description' => 'multilang:completion_course',
        ]));
        $DB->insert_record('totara_evidence_type_info_field', array_merge($text_field, [
            'typeid' => $course_type,
            'fullname' => 'multilang:course_shortname',
            'shortname' => 'courseshortname',
            'sortorder' => 1,
            'param1' => 20,
        ]));
        $DB->insert_record('totara_evidence_type_info_field', array_merge($text_field, [
            'typeid' => $course_type,
            'fullname' => 'multilang:course_idnumber',
            'shortname' => 'courseidnumber',
            'sortorder' => 2,
            'param1' => 10,
            'param2' => 100,
        ]));
        $DB->insert_record('totara_evidence_type_info_field', array_merge($date_field, [
            'typeid' => $course_type,
            'fullname' => 'multilang:completion_date',
            'shortname' => 'completiondate',
            'sortorder' => 3,
        ]));
        $DB->insert_record('totara_evidence_type_info_field', array_merge($text_field, [
            'typeid' => $course_type,
            'fullname' => 'multilang:grade',
            'shortname' => 'grade',
            'sortorder' => 4,
            'param1' => 5,
        ]));
        $DB->insert_record('totara_evidence_type_info_field', array_merge($text_field, [
            'typeid' => $course_type,
            'fullname' => 'multilang:import_id',
            'shortname' => 'importid',
            'sortorder' => 5,
            'param1' => 10,
        ]));
    }

    // Certification type and its fields
    // Note: The shortnames must match get_columnnames() in totara/completionimport/lib.php
    if (!$DB->record_exists('totara_evidence_type', ['idnumber' => 'certificationcompletionimport'])) {
        $certification_type = $DB->insert_record('totara_evidence_type', array_merge($type, [
            'name' => 'multilang:completion_certification',
            'idnumber' => 'certificationcompletionimport',
            'description' => 'multilang:completion_certification',
        ]));
        $DB->insert_record('totara_evidence_type_info_field', array_merge($text_field, [
            'typeid' => $certification_type,
            'fullname' => 'multilang:certification_shortname',
            'shortname' => 'certificationshortname',
            'sortorder' => 1,
            'param1' => 20,
        ]));
        $DB->insert_record('totara_evidence_type_info_field', array_merge($text_field, [
            'typeid' => $certification_type,
            'fullname' => 'multilang:certification_idnumber',
            'shortname' => 'certificationidnumber',
            'sortorder' => 2,
            'param1' => 10,
            'param2' => 100,
        ]));
        $DB->insert_record('totara_evidence_type_info_field', array_merge($date_field, [
            'typeid' => $certification_type,
            'fullname' => 'multilang:completion_date',
            'shortname' => 'completiondate',
            'sortorder' => 3,
        ]));
        $DB->insert_record('totara_evidence_type_info_field', array_merge($date_field, [
            'typeid' => $certification_type,
            'fullname' => 'multilang:due_date',
            'shortname' => 'duedate',
            'sortorder' => 4,
        ]));
        $DB->insert_record('totara_evidence_type_info_field', array_merge($text_field, [
            'typeid' => $certification_type,
            'fullname' => 'multilang:import_id',
            'shortname' => 'importid',
            'sortorder' => 5,
            'param1' => 10,
        ]));
    }

    $transaction->allow_commit();
    return true;
}
