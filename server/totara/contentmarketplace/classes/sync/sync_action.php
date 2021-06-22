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

use null_progress_trace;
use progress_trace;

/**
 * The class is to define abstract method for each sub plugins which needs to be extended.
 *
 * Note: please do not call to finish the trace (a.k.a output) object within the sync action.
 */
abstract class sync_action {
    /**
     * A flag to say that this action is running for the first time.
     *
     * @var bool
     */
    protected $initial_run;

    /**
     * @var progress_trace
     */
    protected $trace;

    /**
     * sync_action constructor.
     * @param bool $initial_run
     * @param progress_trace|null $trace
     */
    public function __construct(bool $initial_run = false, ?progress_trace $trace = null) {
        if (null === $trace) {
            $trace = new null_progress_trace();
        }

        $this->initial_run = $initial_run;
        $this->trace = $trace;
    }

    /**
     * @param bool $initial_run
     */
    final public function set_initial_run(bool $initial_run): void {
        $this->initial_run = $initial_run;
    }

    /**
     * @param progress_trace $trace
     * @return void
     */
    public function set_trace(progress_trace $trace): void {
        $this->trace = $trace;
    }

    /**
     * @retrun void
     */
    abstract public function invoke(): void;

    /**
     * Whether the schedule task will skip this action or not.
     *
     * @return bool
     */
    abstract public function is_skip(): bool;
}