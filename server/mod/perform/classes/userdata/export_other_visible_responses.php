<?php
/*
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
* @author Oleg Demeshev <oleg.demeshev@totaralearning.com>
* @package mod_perform
*/

namespace mod_perform\userdata;

use context;
use core_component;
use core\collection;
use mod_perform\entity\activity\element_response;
use mod_perform\userdata\traits\export_trait;
use totara_userdata\userdata\export;
use totara_userdata\userdata\item;
use totara_userdata\userdata\target_user;

class export_other_visible_responses extends item {
    use export_trait;

    /**
     * Count user data for this item.
     * @param target_user $user
     * @param context $context restriction for counting i.e., system context for everything and course context for course data
     * @return int amount of data or negative integer status code (self::RESULT_STATUS_ERROR or self::RESULT_STATUS_SKIPPED)
     */
    protected static function count(target_user $user, context $context): int {
        $element_response_count = (element_response::repository())
            ->filter_for_export()
            ->filter_by_context($context)
            ->filter_by_subject_for_export($user->id)
            ->filter_by_subject_can_view($user->id)
            ->count();

        [$custom_response_count, $element_response_offset] = self::count_custom_userdata($user, $context);

        $final_count = $element_response_count - $element_response_offset + $custom_response_count;
        return $final_count > 0 ? $final_count : 0;
    }

    /**
     * Export user data from this item.
     * @param target_user $user
     * @param context $context restriction for exporting i.e., system context for everything and course context for course export
     * @return export|int result object or integer error code self::RESULT_STATUS_ERROR or self::RESULT_STATUS_SKIPPED
     */
    protected static function export(target_user $user, context $context) {
        $responses = (element_response::repository())
            ->filter_for_export()
            ->filter_by_context($context)
            ->filter_by_subject_for_export($user->id)
            ->filter_by_subject_can_view($user->id)
            ->get(true)
            ->map(function ($response) use ($user) {
                return self::process_response_record($response, $user->id);
            })
            ->filter(
                // Because some responses can be processed explicitly via custom
                // handlers, they may return an empty data set in process_response_record().
                function (array $export): bool {
                    return !empty($export);
                }
            );

        $custom_exports = self::export_custom_userdata($user, $context);

        $export = new export();
        $export->data = array_merge($responses->to_array(), $custom_exports->all());
        $export->files = static::get_response_files($responses->pluck('id'));
        return $export;
    }

    /**
     * Counts custom user data exports for the given subject.
     *
     * @param target_user $user the activity subject whose data is to be
     *        exported.
     * @param context $context restriction for purging e.g., system context for
     *        everything, course context for purging one course.
     *
     * @return int[] a tuple containing *2* elements: the actual response count
     *         and the offset that needs to be subtracted from the main
     *         element_response count if any.
     */
    private static function count_custom_userdata(
        target_user $user,
        context $context
    ): array {
        return self::custom_userdata_items()
            ->reduce(
                function (array $counts, custom_userdata_item $item) use ($user, $context): array {
                    [$running_count, $running_offset] = $counts;
                    [$count, $offset] = $item->count_other_visible_responses($user, $context);

                    return [$running_count + $count, $running_offset + $offset];
                },
                [0, 0]
            );
    }

    /**
     * Executes custom user data exports for the given participant.
     *
     * @param target_user $user the activity participant whose data is to be
     *        exported.
     * @param context $context restriction for purging e.g., system context for
     *        everything, course context for purging one course.
     *
     * @return collection the exported data as a list of records; each record is
     *         an associative array.
     */
    private static function export_custom_userdata(
        target_user $user,
        context $context
    ): collection {
        $all = [];
        foreach (self::custom_userdata_items() as $item) {
            $exports = $item
                ->export_other_visible_responses($user, $context)
                ->all();

            $all = array_merge($all, $exports);
        }

        return collection::new($all);
    }

    /**
     * Returns the custom userdata items to execute for the export.
     *
     * @param target_user $user the activity participant whose data is to be
     *        exported.
     * @param context $context restriction for purging e.g., system context for
     *        everything, course context for purging one course.
     *
     * @return collection|custom_userdata_item[] the custom user data items.
     */
    private static function custom_userdata_items(): collection {
        $factories = core_component::get_namespace_classes(
            'userdata', custom_userdata_item::class
        );

        return collection::new($factories)
            ->map(
                function (string $class): custom_userdata_item {
                    return $class::create();
                }
            );
    }
}