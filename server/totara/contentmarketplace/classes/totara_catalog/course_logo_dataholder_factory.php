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

namespace totara_contentmarketplace\totara_catalog;

use totara_catalog\dataformatter\formatter;
use totara_catalog\dataholder;
use totara_contentmarketplace\entity\course_source;
use totara_contentmarketplace\totara_catalog\dataformatter\course_logo;

class course_logo_dataholder_factory {
    /**
     * @var string
     */
    public const DATAHOLDER_KEY = "content_marketplace";

    /**
     * @var string
     */
    public const DATAHOLDER_NAME = "markertplace course logo";

    /**
     * @return dataholder[]
     */
    public static function get_dataholders(): array {
        return [
            // Content marketplace logo
            new dataholder(
                self::DATAHOLDER_KEY,
                self::DATAHOLDER_NAME,
                [
                    formatter::TYPE_PLACEHOLDER_IMAGE => new course_logo(
                        'cs.learning_object_id',
                        'cs.marketplace_component'
                    )
                ],
                [
                    self::DATAHOLDER_KEY =>
                        'LEFT JOIN {totara_contentmarketplace_course_source} cs ON cs.course_id = base.id'
                ]
            )
        ];
    }

    /**
     * @return string|null
     */
    public static function get_course_logo_key(): ?string {
        return course_source::repository()->count() == 0 ? null : self::DATAHOLDER_KEY;
    }
}