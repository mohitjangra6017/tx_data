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
 * @package totara_contentmarketplace
 */
namespace totara_contentmarketplace\course;

use coding_exception;
use container_course\course;
use container_course\course_helper;
use context_coursecat;
use core\orm\query\builder;
use core_text;
use stdClass;
use Throwable as throwable;
use totara_contentmarketplace\exception\cannot_resolve_default_course_category;
use totara_contentmarketplace\interactor\abstraction\create_course_interactor;
use totara_contentmarketplace\learning_object\abstraction\metadata\detailed_model;
use totara_contentmarketplace\learning_object\abstraction\metadata\model;
use totara_contentmarketplace\learning_object\factory;

/**
 * The course_builder class is designed to create a course for one learning object.
 * If you would want to create a new course out of different object, please instantiate
 * a new instance of this builder to do such thing.
 */
class course_builder {
    /**
     * The threshold trial that we allow system to look up.
     * @var int
     */
    public const COURSE_SHORT_NAME_THRESHOLD = 10;

    /**
     * @var model
     */
    private $learning_object;

    /**
     * @var int|null
     */
    private $category_id;

    /**
     * The interactor class that help to provide the permission check
     * when the creation course is invoked. Note that it is also a source
     * of the user actor.
     *
     * @var create_course_interactor
     */
    private $course_interactor;

    /**
     * Defaults to 'singleactivity'
     * @var string
     */
    private $course_format;

    /**
     * The list of which enrol methods to enabled, by default we are going
     * to enable the self enrolment, but it is up to the caller to provide
     * more enrolment methods to be enabled.
     *
     * @var string[]
     */
    private $enrol_names_enable;

    /**
     * The default section number that we would want to add the course module into.
     * Default to section zero.
     *
     * @var int
     */
    private $default_section_number;

    /**
     * course_helper constructor.
     */
    public function __construct(model $learning_object, int $category_id, create_course_interactor $interactor) {
        $this->learning_object = $learning_object;
        $this->category_id = $category_id;
        $this->course_interactor = $interactor;
        $this->course_format = 'singleactivity';
        $this->enrol_names_enable = ['self'];
        $this->default_section_number = 0;
    }

    /**
     * @param string                   $marketplace_component
     * @param int                      $learning_object_id
     * @param create_course_interactor $interactor
     * @param int|null                 $category_id
     *
     * @return course_builder
     */
    public static function create_with_learning_object(
        string $marketplace_component,
        int $learning_object_id,
        create_course_interactor $interactor,
        ?int $category_id = null
    ): course_builder {
        global $CFG;

        $resolver = factory::get_resolver($marketplace_component);
        $learning_object = $resolver->find($learning_object_id, true);

        if (empty($category_id)) {
            require_once("{$CFG->dirroot}/totara/core/lib.php");

            $actor_id = $interactor->get_actor_id();
            $category_id = totara_get_categoryid_with_capability('totara/contentmarketplace:add', $actor_id);

            if (!$category_id) {
                throw new cannot_resolve_default_course_category($actor_id);
            }
        }

        return new static($learning_object, $category_id, $interactor);
    }

    /**
     * @param string $enrol_name
     * @return void
     */
    public function disable_enrol(string $enrol_name): void {
        $this->enrol_names_enable = array_filter(
            $this->enrol_names_enable,
            function (string $internal_enrol_name) use ($enrol_name): bool {
                return $internal_enrol_name !== $enrol_name;
            }
        );
    }

    /**
     * @param string $enrol_name
     * @return void
     */
    public function enable_enrol(string $enrol_name): void {
        $enrol_name = strtolower($enrol_name);
        if (!in_array($enrol_name, $this->enrol_names_enable)) {
            $this->enrol_names_enable[] = $enrol_name;
        }
    }

    /**
     * @param string[] $enrol_names
     * @return void
     */
    public function enable_enrols(string ...$enrol_names): void {
        foreach ($enrol_names as $enrol_name) {
            $this->enable_enrol($enrol_name);
        }
    }

    /**
     * Set default section number
     *
     * @param int $section_number
     * @return void
     */
    public function set_default_section_number(int $section_number): void {
        $this->default_section_number = $section_number;
    }

    /**
     * @param string $course_format
     * @return void
     */
    public function set_course_format(string $course_format): void {
        $this->course_format = $course_format;
    }

    /**
     * Please note that this function is about the process of creation only, and the database transaction should be
     * instantiated at prior to this function call.
     *
     * @return course
     */
    private function do_create_course(): course {
        $name = $this->learning_object->get_name();
        $record = new stdClass();
        $record->shortname = self::get_short_name($name, $this->learning_object->get_id());
        $record->fullname = $name;
        $record->category = $this->category_id;
        $record->visible = 1;
        $record->visibleold = 1;
        $record->format = $this->course_format;
        $record->containertype = course::get_type();
        $record->enablecompletion = 1;
        $record->lang = $this->learning_object->get_language();

        if ('singleactivity' === $this->course_format) {
            $record->activitytype = 'contentmarketplace';
        }

        if ($this->learning_object instanceof detailed_model) {
            $summary = $this->learning_object->get_description();
            $record->summary = $summary->get_raw_value();
            $record->summaryformat = $summary->get_format();
        }

        $course = course_helper::create_course($record);

        // Download image and store it.
        (new course_image_downloader($course->id, $this->learning_object->get_image_url()))->download_image_for_course();
        $manager = new enrol_manager($course);

        // Enable any enrol method.
        foreach ($this->enrol_names_enable as $enrol_name) {
            $manager->enable_enrol($enrol_name);
        }

        $actor_id = $this->course_interactor->get_actor_id();
        $manager->enrol_course_creator($actor_id);

        return $course;
    }

    /**
     * @param course $course
     * @return void
     */
    private function do_add_module(course $course): void {
        $module_info = new stdClass();
        $module_info->modulename = 'contentmarketplace';
        $module_info->visible = 1;
        $module_info->learning_object_marketplace_component = $this->learning_object::get_marketplace_component();
        $module_info->learning_object_id = $this->learning_object->get_id();
        $module_info->section = $this->default_section_number;

        $actor_id = $this->course_interactor->get_actor_id();
        if (!course_helper::is_module_addable('contentmarketplace', $course, $actor_id)) {
            throw new coding_exception(
                "Cannot add module 'contentmarketplace' to course '{$course->fullname}'"
            );
        }

        $course->add_module($module_info);
    }

    /**
     * Note that this function does not put the creation in any transaction.
     * Use {@see course_builder::create_course_in_transaction()} if you would want
     * to have rollback the whole process when something went wrong.
     *
     * @return result
     */
    public function create_course(): result {
        $context_category = context_coursecat::instance($this->category_id);
        if (!$this->course_interactor->can_add_course_to_category($context_category)) {
            return result::create(
                null,
                result::ERROR_ON_COURSE_CREATION,
                get_string(
                    'error:cannot_add_course_to_category',
                    'totara_contentmarketplace',
                    $context_category->get_context_name(false)
                )
            );
        }

        try {
            $course = $this->do_create_course();
        } catch (throwable $e) {
            // Encapsulate whatever the exception is in the result, and return this result,
            // instead of yielding the error.
            $class_name = get_class($e);
            debugging(
                "Caught exception '{$class_name}': {$e->getMessage()}",
                DEBUG_ALL
            );

            return result::create(
                null,
                result::ERROR_ON_COURSE_CREATION,
                get_string('error:cannot_create_course', 'totara_contentmarketplace'),
                $e
            );
        }

        try {
            $this->do_add_module($course);
        } catch (throwable $e) {
            $class_name = get_class($e);
            debugging(
                "Caught exception '{$class_name}': {$e->getMessage()}",
                DEBUG_ALL
            );

            return result::create(
                null,
                result::ERROR_ON_MODULE_CREATION,
                get_string('error:cannot_add_module_to_course', 'totara_contentmarketplace', $course->fullname),
                $e
            );
        }

        // Everything runs successfully.
        return result::create($course->get_id());
    }

    /**
     * Create a course within database transaction, if the whole process
     * is error, then the transaction will be rollback.
     *
     * @return result
     */
    public function create_course_in_transaction(): result {
        $db = builder::get_db();
        $transaction = $db->start_delegated_transaction();

        $result = $this->create_course();
        if (!$result->is_error()) {
            $transaction->allow_commit();
            return $result;
        }

        if (!$transaction->is_disposed()) {
            // Note: please do not pass exception to the rollback function,
            // as we do not want to yield any error yet, not until
            // the result is returned.
            $transaction->rollback();
        }

        return $result;
    }

    /**
     * @param string      $name               Learning object full name, which will be used to converted into short
     *                                        name.
     * @param int         $learning_object_id Learning object's id, used for producing the short name.
     * @param int         $threshold          Number of times that we allow how many times to look up on database.
     *                                        By default it is 10.
     * @param string|null $suffix             Given the $suffix as value if we would want to start with.
     *                                        Otherwise leave it null, to generate a random uniqu suffix.
     * @return string
     */
    private static function get_short_name(
        string $name,
        int $learning_object_id,
        int $threshold = self::COURSE_SHORT_NAME_THRESHOLD,
        ?string $suffix = null
    ): string {
        $short_name = core_text::strtolower($name);
        $short_name = preg_replace('/\s+/', '_', $short_name) . '_' . $learning_object_id;

        $db = builder::get_db();

        if (null === $suffix) {
            $suffix = uniqid();
        }

        $search_shortname = $short_name . '_' . $suffix;
        $record_exist = $db->record_exists('course', ['shortname' => $search_shortname]);

        $trial = 0;
        while ($record_exist) {
            // We give the iteration 10 times look up only.
            if ($threshold <= $trial) {
                throw new coding_exception(
                    "The course short name look up had reached threshold"
                );
            }

            // The given suffix might have been tatken. Therefore, we are going to
            // randomly generated a new one.
            $suffix = uniqid();
            $search_shortname = $short_name . '_' . $suffix;

            $record_exist = $db->record_exists('course', ['shortname' => $search_shortname]);
            $trial++;
        }

        return $search_shortname;
    }
}