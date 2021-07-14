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
 * @author Qingyang Liu <qingyang.liu@totaralearning.com>
 * @package contentmarketplace_linkedin
 */

namespace contentmarketplace_linkedin\task;

use contentmarketplace_linkedin\config;
use contentmarketplace_linkedin\interactor\catalog_import_interactor;
use core\entity\user;
use core\task\adhoc_task;
use core\task\manager as task_manager;
use null_progress_trace;
use progress_trace;
use text_progress_trace;
use totara_contentmarketplace\course\course_builder;
use totara_contentmarketplace\exception\cannot_resolve_default_course_category;

final class create_course_delay_task extends adhoc_task {

    /**
     * @var string
     */
    protected const COURSE_INPUT_ARRAY_KEY = 'course_input_array';

    /**
     * @var progress_trace
     */
    protected $trace;

    /**
     * create_course_delay_task constructor.
     * @param progress_trace|null $trace
     */
    public function __construct(?progress_trace $trace = null) {
        $this->set_component('contentmarketplace_linkedin');

        if (is_null($trace)) {
            if (!defined('PHPUNIT_TEST') || !PHPUNIT_TEST) {
                $this->trace = new text_progress_trace();
            } else {
                $this->trace = new null_progress_trace();
            }
        }
    }

    /**
     * @param array $input_params
     * $input_params = [
     *      [
     *          'learning_object_id' => 1,
     *          'category_id'        => 1
     *      ]
     * ]
     * @param progress_trace|null $trace
     *
     * @return static
     */
    public static function enqueue(array $input_params, ?progress_trace $trace = null): self {
        $task = new self($trace);

        $task->set_userid(user::logged_in()->id);
        $task->set_custom_data([
            self::COURSE_INPUT_ARRAY_KEY => $input_params,
        ]);
        task_manager::queue_adhoc_task($task);
        return $task;
    }

    /**
     * @return void
     */
    public function execute() {
        $this->valid_custom_data_key();
        $course_input_array = $this->get_course_input_array();

        if (count($course_input_array) == 0) {
            $this->trace->output('No course are is queue up');
            debugging("No course is queue up for the task", DEBUG_DEVELOPER);
            return;
        }

        $result = $this->create_bulk_courses($course_input_array);

        if ($result) {
            $this->trace->output('Course creation completed');
            return;
        }

        $this->trace->output('Some courses could not be created');
    }

    /**
     * @return array
     */
    private function get_course_input_array(): array {
        $data = $this->get_custom_data();
        $course_input_array = $data->course_input_array;

        $course_input_array = array_map(function ($course_input) {
            if (is_array($course_input)) {
                return $course_input;
            }
            return (array) $course_input;
        }, $course_input_array);

        return $course_input_array;
    }

    /**
     * @param array $course_input_array
     * @return bool
     */
    private function create_bulk_courses(array $course_input_array): bool {
        $result = false;
        while (count($course_input_array) > 0) {
            $batch_array = array_splice($course_input_array, 0, config::get_max_selected_items_number());
            $result = $this->do_create_bulk_courses($batch_array);
        }

        return $result;
    }

    /**
     * @param array $patch_array
     * @return bool
     */
    private function do_create_bulk_courses(array $patch_array): bool {
        $interactor = new catalog_import_interactor($this->get_userid());
        $successful_run = true;

        foreach ($patch_array as $course_input) {
            $learning_object_id = $course_input['learning_object_id'];
            $category_id = $course_input['category_id'] ?? null;

            try {
                $course = course_builder::create_with_learning_object(
                    $this->get_component(),
                    $learning_object_id,
                    $interactor,
                    $category_id
                );

                $result = $course->create_course_in_transaction();
                if (!$result->is_success()) {
                    $successful_run = false;
                }

            } catch (cannot_resolve_default_course_category $e) {
                $this->trace->output('There are some courses that can be added with given category id.');
                $successful_run = false;
            }
        }

        return $successful_run;
    }

    /**
     * @return void
     */
    private function valid_custom_data_key(): void {
        $data = $this->get_custom_data();
        $keys = [self::COURSE_INPUT_ARRAY_KEY];

        foreach ($keys as $key) {
            if (!property_exists($data, $key)) {
                debugging("The custom data for the task does not have key '{$key}'");
                return;
            }
        }
    }
}