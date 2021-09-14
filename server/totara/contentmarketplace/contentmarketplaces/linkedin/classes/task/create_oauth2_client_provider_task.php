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
namespace contentmarketplace_linkedin\task;

use core\task\adhoc_task;
use core\task\manager;
use totara_oauth2\entity\client_provider;
use totara_oauth2\grant_type;

/**
 * An adhoc task which we are using to create a record of client provider of oauth2.
 * Note that this task will only be queued after installation or upgrade.
 */
class create_oauth2_client_provider_task extends adhoc_task {
    /**
     * @return void
     */
    public function execute(): void {
        $repository = client_provider::repository();

        if (!$repository->exists_for_id_number("linkedin_learning")) {
            $entity = new client_provider();
            $entity->client_id = random_string(16);
            $entity->client_secret = random_string(24);
            $entity->id_number = "linkedin_learning";
            $entity->name = get_string("pluginname", "contentmarketplace_linkedin");
            $entity->grant_types = grant_type::get_client_credentials();
            $entity->component = "contentmarketplace_linkedin";

            // Scope for linkedin learning, a string list concatenated by space.
            $entity->scope = "xapi:write";
            $entity->save();
        }
    }

    /**
     * @return create_oauth2_client_provider_task
     */
    public static function enqueue(): create_oauth2_client_provider_task {
        $task = new self();
        manager::queue_adhoc_task($task);

        return $task;
    }
}