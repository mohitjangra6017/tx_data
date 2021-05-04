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
 * @package totara_contentmarketplace
 */

namespace totara_contentmarketplace;

/**
 * The class is to define abstract method for each sub plugins which needs to be extended
 */
abstract class sync_action {
    /**
     * Prevent constructor to be modified by child class
     *
     * lil_sync_action constructor.
     */
    public final function __construct() {
    }

    /**
     * @retrun void
     */
    public abstract function invoke(): void;

    /**
     * To check the initial sync runs or not.
     *
     * @return bool
     */
    public abstract function initial_run(): bool;
}