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
 * @package totara_oauth2
 */

use core\orm\query\builder;
use core_phpunit\testcase;
use totara_oauth2\entity\client_provider;

class totara_oauth2_entity_client_provider_testcase extends testcase {
    /**
     * @return void
     */
    public function test_create_client_provider(): void {
        $db = builder::get_db();
        self::assertEquals(0, $db->count_records(client_provider::TABLE));

        $entity = new client_provider();
        $entity->client_id = "cc";
        $entity->client_secret = "data";
        $entity->name = "something else";
        $entity->id_number = uniqid();
        $entity->save();

        self::assertEquals(1, $db->count_records(client_provider::TABLE));

        $record = $db->get_record(client_provider::TABLE, ["id" => $entity->id]);
        self::assertNotNull($record->time_created);
        self::assertEquals($record->client_id, $entity->client_id);
        self::assertEquals($record->client_secret, $entity->client_secret);
        self::assertEquals($record->name, $entity->name);
        self::assertEquals($record->id_number, $entity->id_number);

        self::assertNull($record->description);
        self::assertNull($record->description_format);
        self::assertNull($record->component);
    }

    /**
     * @return void
     */
    public function test_delete_client_provider(): void {
        $db = builder::get_db();
        self::assertEquals(0, $db->count_records(client_provider::TABLE));

        $entity = new client_provider();
        $entity->client_id = "cc";
        $entity->client_secret = "data";
        $entity->name = "something else";
        $entity->id_number = uniqid();
        $entity->save();

        self::assertEquals(1, $db->count_records(client_provider::TABLE));
        self::assertTrue($entity->exists());

        $entity->delete();
        self::assertEquals(0, $db->count_records(client_provider::TABLE));
        self::assertFalse($entity->exists());
    }

    /**
     * @return void
     */
    public function test_upgrade_client_provider(): void {
        $entity = new client_provider();
        $entity->client_id = "cc";
        $entity->client_secret = "data";
        $entity->name = "something else";
        $entity->id_number = uniqid();
        $entity->save();

        $db = builder::get_db();

        self::assertTrue($db->record_exists(client_provider::TABLE, ["client_id" => "cc"]));
        self::assertFalse($db->record_exists(client_provider::TABLE, ["client_id" => "ddc"]));

        $entity->client_id = "ddc";
        $entity->save();

        self::assertFalse($db->record_exists(client_provider::TABLE, ["client_id" => "cc"]));
        self::assertTrue($db->record_exists(client_provider::TABLE, ["client_id" => "ddc"]));

        self::assertTrue($db->record_exists(client_provider::TABLE, ["client_secret" => "data"]));
        self::assertFalse($db->record_exists(client_provider::TABLE, ["client_secret" => "data_1"]));

        $entity->client_secret = "data_1";
        $entity->save();

        self::assertFalse($db->record_exists(client_provider::TABLE, ["client_secret" => "data"]));
        self::assertTrue($db->record_exists(client_provider::TABLE, ["client_secret" => "data_1"]));
    }
}