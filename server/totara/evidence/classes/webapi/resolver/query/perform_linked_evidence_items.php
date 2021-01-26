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
 * @author Marco Song <marco.song@totaralearning.com>
 * @package totara_evidence
 */

namespace totara_evidence\webapi\resolver\query;

use core\collection;
use core\webapi\middleware\require_advanced_feature;
use performelement_linked_review\webapi\resolver\query\content_items;

class perform_linked_evidence_items extends content_items {

    /**
     * @inheritDoc
     */
    protected static function query_content(array $content_ids): array {
        return collection::new($content_ids)
            ->map(static function (int $id) {
                return (object) [
                    'id' => $id,
                ];
            })
            ->all();
    }

    /**
     * @inheritDoc
     */
    public static function get_middleware(): array {
        return array_merge(parent::get_middleware(), [
            new require_advanced_feature('evidence'),
        ]);
    }
}
