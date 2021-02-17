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
 * @author  Kian Nguyen <kian.nguyen@totaralearning.com>
 * @package totara_notification
 */
namespace core_user\totara_notification\placeholder;

use coding_exception;
use core\entity\user as user_entity;
use core_date;
use totara_notification\placeholder\abstraction\single_emptiable_placeholder;
use totara_notification\placeholder\option;

class user extends single_emptiable_placeholder {
    /**
     * @var user_entity|null
     */
    private $entity;

    /**
     * user constructor.
     * @param user_entity|null $entity
     */
    public function __construct(?user_entity $entity) {
        $this->entity = $entity;
    }

    /**
     * @param int  $id
     * @param bool $strict
     *
     * @return user
     */
    public static function from_id(int $id, bool $strict = false): user {
        global $DB;

        $strictness = $strict ? MUST_EXIST : IGNORE_MISSING;
        $user_record = $DB->get_record(user_entity::TABLE, ['id' => $id], '*', $strictness);

        $entity = !$user_record ? null : new user_entity($user_record);
        return new static($entity);
    }

    /**
     * @return option[]
     */
    public static function get_options(): array {
        return [
            option::create('firstname', get_string('firstname', 'moodle')),
            option::create('lastname', get_string('lastname', 'moodle')),
            option::create('fullname', get_string('fullname', 'moodle')),
            option::create('city', get_string('city', 'moodle')),
            option::create('country', get_string('country', 'moodle')),
            option::create('department', get_string('department', 'moodle')),
            option::create('firstnamephonetic', get_string('firstnamephonetic', 'moodle')),
            option::create('lastnamephonetic', get_string('lastnamephonetic', 'moodle')),
            option::create('middlename', get_string('middlename', 'moodle')),
            option::create('alternatename', get_string('alternatename', 'moodle')),
            option::create('timezone', get_string('timezone', 'moodle'))

            // Note that we are skipping email away from this list for now, as we need to somehow respect
            // the settings whether to display the email or not.
        ];
    }

    /**
     * @param string $key
     * @return bool
     */
    protected function is_available(string $key): bool {
        return null !== $this->entity;
    }

    /**
     * @param string $key
     * @return string
     */
    public function do_get(string $key): string {
        if (null === $this->entity) {
            throw new coding_exception("The user entity record is empty");
        }

        switch ($key) {
            case 'fullname':
                $user_record = $this->entity->get_record();
                return fullname($user_record);

            case 'timezone':
                $user_record = $this->entity->get_record();
                return core_date::get_localised_timezone(
                    core_date::get_user_timezone($user_record)
                );

            default:
                $invalid_keys = ['password'];
                if (!in_array($key, $invalid_keys) && $this->entity->has_attribute($key)) {
                    return (string) $this->entity->get_attribute($key);
                }
        }

        // If we follow the process from the template engine, there is no chance that the code should
        // go to this point. However it has to be here to warn developer that if they call this function
        // with the invalid $key directly from somewhere else.
        throw new coding_exception("Invalid key '{$key}' is not yet supported");
    }
}