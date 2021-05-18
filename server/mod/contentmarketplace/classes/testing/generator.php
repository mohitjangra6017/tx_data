<?php
/**
 * This file is part of Totara Core
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
 * @author  Kian Nguyen <kian.nguyen@totaralearning.com>
 * @package mod_contentmarketplace
 */
namespace mod_contentmarketplace\testing;

use coding_exception;
use core\orm\query\builder;
use core\testing\mod_generator;
use core_container\entity\module;
use core_container\factory;
use stdClass;
use totara_contentmarketplace\testing\generator as totara_contentmarketplace_generator;

class generator extends mod_generator {
    /**
     * @param stdClass|array $record
     * @param array|null     $options
     *
     * @return stdClass
     */
    public function create_instance($record = null, array $options = null): stdClass {
        if (is_array($record)) {
            $record = (object) $record;
        }

        // Clear the pass by references.
        $record = clone $record;
        $options = $options ?? [];

        if (!property_exists($record, 'modulename')) {
            $record->modulename = 'contentmarketplace';
        }

        if (!property_exists($record, 'section')) {
            $record->section = 0;
        }

        if (!property_exists($record, 'learning_object_marketplace_component')) {
            // Default to linkedin learning, for test environment, if it is not provided.
            $record->learning_object_marketplace_component = 'contentmarketplace_linkedin';
        }

        if (!property_exists($record, 'learning_object_id')) {
            $marketplace_generator = totara_contentmarketplace_generator::instance();
            $marketplace_component = $record->learning_object_marketplace_component;

            $learning_object = $marketplace_generator->create_learning_object($marketplace_component);
            $record->learning_object_id = $learning_object->get_id();
        }

        return parent::create_instance($record, $options);
    }

    /**
     * Functions to generate a content marketplace instance from the behat system.
     * What it does is to generate a learning object record and then map with the
     * generated content marketplace activity record.
     *
     * @param array $data
     * @return stdClass
     */
    public function create_content_marketplace_instance(array $data = []): stdClass {
        if (!isset($data['course']) || !isset($data['name']) || !isset($data['marketplace_component'])) {
            throw new coding_exception(
                "Missing either of the required fields: ['course', 'name', 'marketplace_component']"
            );
        }

        $marketplace_generator = totara_contentmarketplace_generator::instance();
        $learning_object = $marketplace_generator->create_learning_object(
            $data['marketplace_component'],
            $data['name']
        );

        $db = builder::get_db();

        $course_record = $db->get_record('course', ['shortname' => $data['course']], '*', MUST_EXIST);
        $course = factory::from_record($course_record);

        $module = $this->create_instance([
            'course' => $course->id,
            'section' => 0,
            'learning_object_id' => $learning_object->get_id(),
            'learning_object_marketplace_component' => $learning_object::get_marketplace_component()
        ]);

        if ('singleactivity' === $course->format) {
            $course_format = course_get_format($course_record);
            $course_format->update_course_format_options(['activitytype' => 'contentmarketplace']);

            $course->rebuild_cache();
        }

        return $module;
    }
}