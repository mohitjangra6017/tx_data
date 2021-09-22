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
namespace contentmarketplace_linkedin\totara_xapi\handler;

use cm_info;
use completion_info;
use contentmarketplace_linkedin\entity\learning_object;
use contentmarketplace_linkedin\entity\user_completion;
use contentmarketplace_linkedin\totara_xapi\statement;
use context_course;
use totara_contentmarketplace\entity\course_module_source;
use totara_oauth2\io\request;
use totara_oauth2\server;
use totara_xapi\entity\xapi_statement;
use totara_xapi\handler\base_handler;
use totara_xapi\response\facade\result;
use totara_xapi\response\json_result;

class handler extends base_handler {
    /**
     * @return result|null
     */
    public function authenticate(): ?result {
        $oauth2_request = request::create_from_global(
            $this->request->get_get_parameters(),
            $this->request->get_post_parameters(),
            $this->request->get_header_parameters(),
            $this->request->get_server_parameters()
        );

        $server = server::create($this->time_now);

        if ($server->is_request_verified($oauth2_request)) {
            // The server is verified - hence, we should not return any result.
            return null;
        }

        return new json_result([
            "error" => "access_denied",
            "error_description" => get_string("access_denied", "contentmarketplace_linkedin")
        ]);
    }

    /**
     * @inheritDoc
     *
     * @param xapi_statement $xapi_statement
     * @return result
     */
    public function process(xapi_statement $xapi_statement): result {
        $statement = statement::create_with_validation($xapi_statement);

        // Store the user completion for later usage, if we are going to have to update any new course
        // that is created with the linkedin learning content.
        $this->persist_user_completion($statement);

        // update user completion
        $this->update_user_completion($statement);
        return new json_result(["success" => true]);
    }

    /**
     * A function to update the course's completion or activity completion.
     *
     * @param statement $statement
     *
     * @return void
     */
    private function update_user_completion(statement $statement): void {
        global $CFG;
        $urn = $statement->get_learning_object_urn();

        $learning_object_repository = learning_object::repository();
        $learning_object = $learning_object_repository->find_by_urn($urn);

        if (null === $learning_object) {
            // Weird, the learning object is not found. We should probably skip the rest.
            // This can potentially happen if our sync is not yet happening.
            // Might as well debugging so that we can identify the problem easier.
            debugging(
                "Cannot find the learning object with urn {$urn}",
                DEBUG_DEVELOPER
            );

            return;
        }

        // Get all the courses that are linked with this very learning object.
        /** @var course_module_source[] $course_module_sources */
        $course_module_sources = course_module_source::repository()
            ->with('module.course_entity')
            ->filter_by_id_and_component($learning_object->id, "contentmarketplace_linkedin")
            ->get();

        $target_user_id = $statement->get_user_id();

        require_once("{$CFG->dirroot}/lib/completionlib.php");

        foreach ($course_module_sources as $course_module_source) {
            $course = $course_module_source->module->course_entity;
            $course_record = $course->to_record(true);

            // Check if the user is enrolled into this course or not.
            $context_course = context_course::instance($course->id);
            if (!is_enrolled($context_course, $target_user_id)) {
                continue;
            }

            $completion_info = new completion_info($course_record);
            if (!$completion_info->is_enabled()) {
                // Course's completion is not marked as enabled.
                continue;
            }

            // Get all the cm from the courses that are mod contentmarketplace
            $cm_info = cm_info::create($course_module_source->module->to_record(true), $target_user_id);

            if (!$completion_info->is_enabled($cm_info)) {
                // Completion is not enabled for this course module contentmarketplace.
                continue;
            }

            $completion_info->update_state($cm_info, COMPLETION_UNKNOWN, $target_user_id);
        }
    }

    /**
     * @param statement $statement
     * @return void
     */
    private function persist_user_completion(statement $statement): void {
        $user_id = $statement->get_user_id();
        $urn = $statement->get_learning_object_urn();

        $repository = user_completion::repository();
        $entity = $repository->find_for_user_with_urn($user_id, $urn);

        if (null === $entity) {
            // No user completion record existing yet.
            $entity = new user_completion();
            $entity->user_id = $user_id;
            $entity->learning_object_urn = $urn;
        }

        // Set the entity completion
        $progress = $statement->get_progress();
        $entity->progress = $progress->get_progress();
        $entity->completion = $progress->is_completed();

        $entity->save();
    }
}