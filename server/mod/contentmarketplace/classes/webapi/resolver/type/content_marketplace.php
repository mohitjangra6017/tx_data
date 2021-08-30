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
 * @package mod_contentmarketplace
 */
namespace mod_contentmarketplace\webapi\resolver\type;

use coding_exception;
use core\format;
use core\webapi\execution_context;
use core\webapi\type_resolver;
use mod_contentmarketplace\interactor\content_marketplace_interactor;
use mod_contentmarketplace\model\content_marketplace as model;
use mod_contentmarketplace\formatter\content_marketplace as formatter;
use core\orm\query\builder;

/**
 * Type resolver for content marketplace.
 */
class content_marketplace implements type_resolver {
    /**
     * @param string $field
     * @param model $content_marketplace
     * @param array $args
     * @param execution_context $ec
     * @return mixed
     */
    public static function resolve(string $field, $content_marketplace, array $args, execution_context $ec) {
        global $CFG;

        if (!($content_marketplace instanceof model)) {
            throw new coding_exception('Expected content marketplace model');
        }

        if ($field === 'interactor') {
            return new content_marketplace_interactor($content_marketplace);
        }

        if ("self_completion" === $field) {
            require_once("{$CFG->dirroot}/lib/completionlib.php");

            $module = $content_marketplace->get_course_module();
            return COMPLETION_TRACKING_MANUAL == $module->completion;
        }

        if ("completion_status" === $field) {
            return self::resolve_completion_status($content_marketplace);
        }

        $context = $ec->has_relevant_context() ? $ec->get_relevant_context() : $content_marketplace->get_context();
        $formatter = new formatter($content_marketplace, $context);

        return $formatter->format($field, $args['format'] ?? format::FORMAT_PLAIN);
    }

    /**
     * @param model $content_marketplace
     * @return bool|null
     */
    private static function resolve_completion_status(model $content_marketplace): ?bool {
        global $CFG, $USER;

        $db = builder::get_db();
        $completion_record = $db->get_record(
            "course_modules_completion",
            [
                "userid" => $USER->id,
                "coursemoduleid" => $content_marketplace->get_cm_id()
            ],
            "id, completionstate"
        );

        if (empty($completion_record)) {
            // No record existing yet, hence not yet started.
            return null;
        }

        // As long as completion incomplete is not the value of the completion state then
        // the current user in session had completed the course.
        require_once("{$CFG->dirroot}/mod/contentmarketplace/lib.php");
        return COMPLETION_INCOMPLETE != $completion_record->completionstate;
    }
}