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
 * @author Kian Nguyen <kian.nguyen@totaralearning.com>
 * @package totara_notification
 */
namespace totara_notification\placeholder\abstraction;

/**
 * Empty-able placeholder which can default the value as representation string
 * for not able to resolve the value of the placeholder key.
 */
abstract class single_emptiable_placeholder implements single_placeholder {
    /**
     * Checking whether the placeholder is available with the given data for key
     * {$key} or not.
     *
     * @param string $key
     * @return bool
     */
    abstract protected function is_available(string $key): bool;

    /**
     * This function is called as part of {@see placeholder::get()}.
     *
     * The child class will have extend this function in order to be able to
     * provide the value at placeholder key.
     *
     * @param string $key
     * @return string
     */
    abstract protected function do_get(string $key): string;

    /**
     * Give the representation string for not resolveable placeholder key, due to data does not exist.
     * Extend this function to change the representation string for empty value.
     *
     * @param string $key
     * @return string
     * @deprecated since Totara 14.1
     */
    protected function get_representation_string_for_empty(string $key): string {
        debugging(
            'single_emptiable_placeholder::get_representation_string_for_empty has been deprecated. Empty values ' .
            'from single_emptiable_placeholder::get will automatically be replaced by the no_available_data_for_key string.',
            DEBUG_DEVELOPER
        );
        return get_string('no_available_data_for_key', 'totara_notification', $key);
    }

    /**
     * @inheritDoc
     * @param string $key
     * @return string
     */
    public function get(string $key): string {
        if (!$this->is_available($key)) {
            return '';
        }

        return $this->do_get($key);
    }

    /**
     * @param string $key
     * @return bool
     */
    public static function is_safe_html(string $key): bool {
        return false;
    }
}