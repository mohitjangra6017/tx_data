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
 * @author Kunle Odusan <kunle.odusan@totaralearning.com>
 */

use mod_perform\hook\post_element_response_submission;
use mod_perform\hook\pre_section_relationship_deleted;
use performelement_linked_review\watcher\post_response_submission;
use performelement_linked_review\watcher\section_relationship_deletion_check;

$watchers = [
    [
        'hookname' => pre_section_relationship_deleted::class,
        'callback' => [section_relationship_deletion_check::class, 'can_delete'],
    ],
    [
        'hookname' => post_element_response_submission::class,
        'callback' => [post_response_submission::class, 'process_content_child_element_responses'],
    ],
];
