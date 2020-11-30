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

use mod_perform\hook\pre_activity_deleted;
use mod_perform\hook\pre_section_deleted;
use mod_perform\hook\pre_section_element_deleted;
use performelement_redisplay\watcher\activity_deletion_check;
use performelement_redisplay\watcher\section_deletion_check;
use performelement_redisplay\watcher\section_element_deletion_check;

$watchers = [
    [
        'hookname' => pre_activity_deleted::class,
        'callback' => [activity_deletion_check::class, 'can_delete'],
    ],
    [
        'hookname' => pre_section_deleted::class,
        'callback' => [section_deletion_check::class, 'can_delete'],
    ],
    [
        'hookname' => pre_section_element_deleted::class,
        'callback' => [section_element_deletion_check::class, 'can_delete'],
    ]
];