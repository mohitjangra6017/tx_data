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

namespace totara_contentmarketplace\sync;

/**
 * The class is to define abstract method for each sub plugins which needs to be extended
 */
abstract class sync_action {
    /**
     * A flag to say that this action is running for the first time.
     *
     * @var bool
     */
    protected $initial_run;

    /**
     * sync_action constructor.
     * @param bool $initial_run
     */
    public function __construct(bool $initial_run = false) {
        $this->initial_run = $initial_run;
    }

    /**
     * @retrun void
     */
    abstract public function invoke(): void;
}