<?php
/**
 * This file is part of Totara Learn
 *
 * Copyright (C) 2020 onwards Totara Learning Solutions LTD
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
 * @author Marco Song <marco.song@totaralearning.com>
 * @package mod_perform
 */

namespace mod_perform\hook;

use coding_exception;
use core\orm\collection;
use mod_perform\hook\dto\pre_deleted_dto;
use totara_core\hook\base;

/**
 * Hook for section element deletion
 *
 * @package mod_perform\hook
 */
class pre_section_element_deleted extends base {

    /** @var int $section_element_id */
    private $section_element_id;

    /** @var collection $reasons */
    private $reasons = [];

    /** @var bool $can_delete */
    private $can_delete = true;

    public function __construct(int $section_element_id) {
        $this->section_element_id = $section_element_id;
    }

    /**
     * Get current section element id
     *
     * @return int
     */
    public function get_section_element_id() {
        return $this->section_element_id;
    }

    /**
     * Add can not delete reason
     *
     * @param string $key
     * @param string $description
     * @param array $data
     */
    public function add_reason(string $key, string $description, array $data) {
        if ($this->can_delete) {
            $this->can_delete = false;
        }
        $this->reasons[$key] = new pre_deleted_dto($description, $data);
    }

    /**
     * Get can not delete reason
     *
     * @return array
     */
    public function get_reasons(): array {
        return $this->reasons;
    }

    /**
     * If a section element can be deleted
     *
     * @return bool
     */
    public function can_delete() {
        return $this->can_delete;
    }
}
