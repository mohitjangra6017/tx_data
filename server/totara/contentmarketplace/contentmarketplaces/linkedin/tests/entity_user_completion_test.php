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
 * @package contentmarketplace_linkedin
 */

use contentmarketplace_linkedin\dto\xapi\progress;
use contentmarketplace_linkedin\entity\user_completion;
use core\entity\user;
use core\orm\query\builder;
use core_phpunit\testcase;

class contentmarketplace_linkedin_entity_user_completion_testcase extends testcase {
    /**
     * @var user|null
     */
    private $user;

    /**
     * @return void
     */
    protected function setUp(): void {
        $generator = self::getDataGenerator();
        $user = $generator->create_user();

        $this->user = new user($user);
    }

    /**
     * @return void
     */
    protected function tearDown(): void {
        $this->user = null;
    }

    /**
     * @return void
     */
    public function test_create(): void {
        $entity = new user_completion();
        $entity->user_id = $this->user->id;
        $entity->learning_object_urn = "urn:lyndaCourse:252";
        $entity->progress = 39;
        $entity->completion = false;

        self::assertFalse($entity->exists());
        $entity->save();

        self::assertNotEmpty($entity->time_created);
        self::assertNotNull($entity->id);
        self::assertTrue($entity->exists());

        $db = builder::get_db();
        self::assertTrue($db->record_exists(user_completion::TABLE, ["id" => $entity->id]));
    }

    /**
     * @return void
     */
    public function test_update(): void {
        $db = builder::get_db();

        $entity = new user_completion();
        $entity->user_id = $this->user->id;
        $entity->learning_object_urn = "urn:lyndaCourse:522";
        $entity->progress = 42;
        $entity->completion = false;

        self::assertFalse(
            $db->record_exists(user_completion::TABLE, ["learning_object_urn" => "urn:lyndaCourse:522"])
        );

        $entity->save();

        self::assertTrue($db->record_exists(user_completion::TABLE, ["learning_object_urn" => "urn:lyndaCourse:522"]));
        self::assertFalse($db->record_exists(user_completion::TABLE, ["learning_object_urn" => "urn:lyndaCourse:252"]));

        $entity->learning_object_urn = "urn:lyndaCourse:252";
        $entity->save();

        self::assertFalse($db->record_exists(user_completion::TABLE, ["learning_object_urn" => "urn:lyndaCourse:522"]));
        self::assertTrue($db->record_exists(user_completion::TABLE, ["learning_object_urn" => "urn:lyndaCourse:252"]));
    }

    /**
     * @return void
     */
    public function test_create_with_violation_of_data_integrity_1(): void {
        $entity = new user_completion();
        $entity->user_id = $this->user->id;
        $entity->learning_object_urn = "urn:lyndaCourse:252";
        $entity->progress = 55;
        $entity->completion = true;

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage("Cannot save the user completion that identify as completed but also in progress");

        $entity->save();
    }

    /**
     * @return void
     */
    public function test_create_with_violation_of_data_integrity_2(): void {
        $entity = new user_completion();
        $entity->user_id = $this->user->id;
        $entity->learning_object_urn = "urn:lyndaCourse:252";
        $entity->progress = 100;
        $entity->completion = false;

        $this->expectException(coding_exception::class);
        $this->expectExceptionMessage(
            "Cannot save the user completion that identify as not completed but also not in progressed"
        );

        $entity->save();
    }
}