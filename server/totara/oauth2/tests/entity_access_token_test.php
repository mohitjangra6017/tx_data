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
use totara_oauth2\entity\access_token;

class totara_oauth2_entity_access_token_testcase extends testcase {
    /**
     * @return void
     */
    public function test_create_access_token(): void {
        $entity = new access_token();
        $entity->client_id = "abcde";
        $entity->access_token = "dd";
        $entity->expires = time() + DAYSECS;
        $entity->save();

        $db = builder::get_db();
        $record = $db->get_record(access_token::TABLE, ["id" => $entity->id]);

        self::assertEquals($entity->client_id, $record->client_id);
        self::assertEquals($entity->access_token, $record->access_token);
        self::assertEquals($entity->expires, $record->expires);
        self::assertNull($record->scope);
    }

    /**
     * @return void
     */
    public function test_delete_access_token(): void {
        $entity = new access_token();
        $entity->client_id = "abcde";
        $entity->access_token = "dd";
        $entity->expires = time() + DAYSECS;
        $entity->save();

        $db = builder::get_db();

        self::assertTrue($entity->exists());
        self::assertTrue($db->record_exists(access_token::TABLE, ["id" => $entity->id]));

        $id = $entity->id;
        $entity->delete();

        self::assertFalse($entity->exists());
        self::assertFalse($db->record_exists(access_token::TABLE, ["id" => $id]));
    }

    /**
     * @return void
     */
    public function test_update_access_token(): void {
        $entity = new access_token();
        $entity->client_id = "abcde";
        $entity->access_token = "dd";
        $entity->expires = time() + DAYSECS;
        $entity->save();

        $db = builder::get_db();
        self::assertTrue($db->record_exists(access_token::TABLE, ["access_token" => "dd"]));
        self::assertFalse($db->record_exists(access_token::TABLE, ["access_token" => "dca"]));

        $entity->access_token = "dca";
        $entity->save();

        self::assertFalse($db->record_exists(access_token::TABLE, ["access_token" => "dd"]));
        self::assertTrue($db->record_exists(access_token::TABLE, ["access_token" => "dca"]));

        self::assertTrue($db->record_exists(access_token::TABLE, ["client_id" => "abcde"]));
        self::assertFalse($db->record_exists(access_token::TABLE, ["client_id" => "ddca"]));

        $entity->client_id = "ddca";
        $entity->save();

        self::assertFalse($db->record_exists(access_token::TABLE, ["client_id" => "abcde"]));
        self::assertTrue($db->record_exists(access_token::TABLE, ["client_id" => "ddca"]));
    }
}