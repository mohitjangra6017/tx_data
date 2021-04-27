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
 * @package core
 */
namespace core\json\schema;

interface schema {
    /**
     * Returns an error message, which describes what went wrong.
     * Note that this is meant for developers to debug/understand.
     * It is not something for the end user to understand. Therefore, it
     * is safe to return non localised string here, better be English.
     *
     * @param mixed $value
     * @return string|null
     */
    public function validate($value): ?string;

    /**
     * Cleans the parameter.
     *
     * @param mixed|null $value
     * @return mixed|null
     */
    public function clean($value);
}