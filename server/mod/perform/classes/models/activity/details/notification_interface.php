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
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Tatsuhiro Kirihara <tatsuhiro.kirihara@totaralearning.com>
 * @package mod_perform
 */

namespace mod_perform\models\activity\details;

use core\orm\query\builder;
use mod_perform\models\activity\activity;

/**
 * notification interface
 */
interface notification_interface {
    /**
     * Return the parent activity.
     *
     * @return activity
     */
    public function get_activity(): activity;

    /**
     * Returns the registered class key.
     *
     * @return string
     */
    public function get_class_key(): string;

    /**
     * Return the active state.
     *
     * @return boolean
     */
    public function get_active(): bool;

    /**
     * Modify the builder to obtain associated recipients.
     * *Do not call this function directly!!*
     *
     * @param builder $builder a partially set up builder: see notification_recipient::load_by_notification()
     *                         * {perform_section} s
     *                         * {perform_section_relationship} sr
     *                         * {totara_core_relationship} r
     * @param boolean $active_only get only active recipients
     */
    public function recipients_builder(builder $builder, bool $active_only = false): void;

    /**
     * Return the array of trigger values.
     *
     * @return integer[]
     */
    public function get_triggers(): array;

    /**
     * Return the last run time.
     *
     * @return integer
     */
    public function get_last_run_at(): int;

    /**
     * Return true if the underlying record exists in the database.
     *
     * @return boolean
     */
    public function exists(): bool;

    /**
     * Activate this notification setting.
     *
     * @param boolean $active
     * @return notification_interface
     */
    public function activate(bool $active = true): notification_interface;

    /**
     * Update event trigger values.
     *
     * @param array $values
     * @return notification_interface
     */
    public function set_triggers(array $values): notification_interface;

    /**
     * Delete the current notification setting.
     *
     * @return notification_interface
     */
    public function delete(): notification_interface;

    /**
     * Reload the internal bookkeeping.
     *
     * @return notification_interface
     */
    public function refresh(): notification_interface;
}
