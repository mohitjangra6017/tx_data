<?php
/*
 * This file is part of Totara LMS
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
 * @author David Curry <david.curry@totaralearning.com>
 * @package mobile_findlearning
 */

defined('MOODLE_INTERNAL') || die();

use core\format;
use totara_webapi\phpunit\webapi_phpunit_helper;
use totara_engage\access\access;
use totara_catalog\task\refresh_catalog_data;
use mobile_findlearning\catalog as mobile_catalog;

/**
 * Note: This also tests the catalog_item resolver since that's contained within the page.
 */
class mobile_findlearning_webapi_resolver_type_catalog_item_testcase extends advanced_testcase {

    use webapi_phpunit_helper;

    private function resolve($field, $item, array $args = []) {
        return $this->resolve_graphql_type('mobile_findlearning_catalog_item', $field, $item, $args);
    }

    /**
     * Create some users and various learning items to be fetched in the catalog.
     * @return []
     */
    private function create_faux_catalog_items($format = 'html') {
        $prog_gen = $this->getDataGenerator()->get_plugin_generator('totara_program');

        $user1 = $this->getDataGenerator()->create_user();
        $user2 = $this->getDataGenerator()->create_user();

        // Create some courses.
        $this->getDataGenerator()->create_course(['shortname' => 'alpha', 'fullname' => 'Alpha course', 'summary' => 'Alphabetical courses 1']);
        $this->getDataGenerator()->create_course(['shortname' => 'beta', 'fullname' => 'Beta course', 'summary' => 'Alphabetical courses 1']);
        $this->getDataGenerator()->create_course(['shortname' => 'charlie', 'fullname' => 'Charlie course', 'summary' => 'Alphabetical courses 1']);

        // Add some extra courses as prog/cert content.
        $c1 = $this->getDataGenerator()->create_course(['fullname' => 'Prog content 1']);
        $c2 = $this->getDataGenerator()->create_course(['fullname' => 'Prog content 2']);
        $c3 = $this->getDataGenerator()->create_course(['fullname' => 'Prog content 3']);

        // Create a single program expected at the top of sort.
        $program = $prog_gen->create_program(['shortname' => 'prg', 'fullname' => 'Alpha program', 'summary' => 'first program']);

        // Create a single certification expected at the top of sort.
        $certification = $prog_gen->create_certification(['shortname' => 'crt', 'fullname' => 'Alpha certification', 'summary' => 'first certification']);

        // Create a playlist to test.
        $playlistgen = $this->getDataGenerator()->get_plugin_generator('totara_playlist');

        $params = [
            'name' => 'Alpha playlist 1',
            'userid' => $user1->id,
            'contextid' => \context_user::instance($user1->id)->id,
            'access' => access::PRIVATE,
            'summary' => 'Playlist 1 description'
        ];
        $playlistgen->create_playlist($params);

        // Create an article to test.
        $articlegen = $this->getDataGenerator()->get_plugin_generator('engage_article');
        $params = [
            'name' => 'Alpha article 1',
            'content' => 'this article is about the first alpha',
            'userid' => $user1->id,
            'access' => access::PRIVATE
        ];
        $articlegen->create_article($params);

        $task = new refresh_catalog_data();
        $task->execute();

        return ['u1' => $user1, 'u2' => $user2];
    }

    // Test mobile_item::create failure
    public function test_resolve_invalid_object() {
        $user1 = $this->getDataGenerator()->create_user();
        $this->setUser($user1->id);
    }

    // Test resolving prog/cert failure
    public function test_resolve_invalid_items() {
        $users = $this->create_faux_catalog_items();
        $this->setUser($users['u1']->id);
        $page = mobile_catalog::load_catalog_page_objects();
        $item = array_shift($page->objects); // Get a valid item to mess up.

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage('Only mobile_item catalog objects are accepted: object');

        $item = new \stdClass();
        $this->resolve('id', $item);

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage('Only mobile_item catalog objects are accepted: object');
        $item->objecttype = 'totara_program';
        $this->resolve('id', $item);

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage('Only mobile_item catalog objects are accepted: object');
        $item->objecttype = 'totara_certification';
        $this->resolve('id', $item);

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage('Only mobile_item catalog objects are accepted: object');
        $item->objecttype = 'crazystuff';
        $this->resolve('id', $item);
    }

    public function test_resolve_id() {
        $users = $this->create_faux_catalog_items();

        // get the catalog objects for user 1 since it will have a range of objects.
        $this->setUser($users['u1']->id);
        $page = mobile_catalog::load_catalog_page_objects();
        $objects = $page->objects;

        foreach ($objects as $object) {
            $this->assertSame($object->id, $this->resolve('id', $object));
        }
    }

    public function test_resolve_itemid() {
        $users = $this->create_faux_catalog_items();

        // get the catalog objects for user 1 since it will have a range of objects.
        $this->setUser($users['u1']->id);
        $page = mobile_catalog::load_catalog_page_objects();
        $objects = $page->objects;

        foreach ($objects as $object) {
            $this->assertSame($object->objectid, $this->resolve('itemid', $object));
        }
    }

    public function test_resolve_item_type() {
        $users = $this->create_faux_catalog_items();

        // get the catalog objects for user 1 since it will have a range of objects.
        $this->setUser($users['u1']->id);
        $page = mobile_catalog::load_catalog_page_objects();
        $objects = $page->objects;

        foreach ($objects as $object) {
            $this->assertSame($object->objecttype, $this->resolve('item_type', $object));
        }
    }

    public function test_resolve_title() {
        $users = $this->create_faux_catalog_items();

        // get the catalog objects for user 1 since it will have a range of objects.
        $this->setUser($users['u1']->id);
        $page = mobile_catalog::load_catalog_page_objects();
        $objects = $page->objects;

        foreach ($objects as $object) {
            $expected = null;
            foreach ($object->data as $data) {
                // We have to take into account the several fields that different itemtypes use.
                if (!is_array($data)) {
                    continue;
                } else if (array_key_exists('name', $data)) {
                    $expected = $data['name'];
                    break;
                } else if (array_key_exists('fullname', $data)) {
                    $expected = $data['fullname'];
                    break;
                }
            }

            if (empty($expected)) {
                $this->fail('Data object missing required field: name');
            } else {
                $this->assertSame($expected, $this->resolve('title', $object, ['format' => format::FORMAT_PLAIN]));
            }
        }
    }

    public function test_resolve_image_enabled() {
        $users = $this->create_faux_catalog_items();

        // get the catalog objects for user 1 since it will have a range of objects.
        $this->setUser($users['u1']->id);
        $page = mobile_catalog::load_catalog_page_objects();
        $objects = $page->objects;

        foreach ($objects as $object) {
            $this->assertTrue($this->resolve('image_enabled', $object)); // Hardcoded atm.
        }
    }

    public function test_resolve_image_url() {
        $users = $this->create_faux_catalog_items();

        // get the catalog objects for user 1 since it will have a range of objects.
        $this->setUser($users['u1']->id);
        $page = mobile_catalog::load_catalog_page_objects();
        $objects = $page->objects;

        foreach ($objects as $object) {
            $expected = null;
            foreach ($object->data as $data) {
                if (is_array($data) && array_key_exists('image', $data)) {
                    $expected = $data['image']->url;
                }
            }

            if (empty($expected)) {
                $this->fail('Data object missing required field: name');
            } else {
                $this->assertSame($expected, $this->resolve('image_url', $object));
            }
        }
    }

    public function test_resolve_image_alt() {
        $users = $this->create_faux_catalog_items();

        // get the catalog objects for user 1 since it will have a range of objects.
        $this->setUser($users['u1']->id);
        $page = mobile_catalog::load_catalog_page_objects();
        $objects = $page->objects;

        foreach ($objects as $object) {
            $expected = null;
            foreach ($object->data as $data) {
                if (is_array($data) && array_key_exists('image', $data)) {
                    $expected = $data['image']->alt;
                }
            }

            if (empty($expected)) {
                $this->fail('Data object missing required field: name');
            } else {
                $this->assertSame($expected, $this->resolve('image_alt', $object, ['format' => format::FORMAT_PLAIN]));
            }
        }
    }

    public function test_resolve_description_enabled() {
        $users = $this->create_faux_catalog_items();

        // get the catalog objects for user 1 since it will have a range of objects.
        $this->setUser($users['u1']->id);
        $page = mobile_catalog::load_catalog_page_objects();
        $objects = $page->objects;

        foreach ($objects as $object) {
            $this->assertTrue($this->resolve('description_enabled', $object)); // Hardcoded atm.
        }
    }

    public function test_resolve_description() {
        $users = $this->create_faux_catalog_items();

        // get the catalog objects for user 1 since it will have a range of objects.
        $this->setUser($users['u1']->id);
        $page = mobile_catalog::load_catalog_page_objects();
        $objects = $page->objects;

        foreach ($objects as $object) {
            $this->assertTrue($this->resolve('description_enabled', $object)); // Hardcoded atm.
        }
    }
}
