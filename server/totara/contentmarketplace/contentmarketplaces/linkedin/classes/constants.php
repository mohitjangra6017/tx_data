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
 * @author  Kian Nguyen <kian.nguyen@totaralearning.com>
 * @package contentmarketplace_linkedin
 */
namespace contentmarketplace_linkedin;

use coding_exception;

/**
 * Constant definition for the query.
 */
final class constants {
    /**
     * @var string
     */
    public const ASSET_TYPE_COURSE = 'COURSE';

    /**
     * @var string
     */
    public const ASSET_TYPE_LEARNING_PATH = 'LEARNING_PATH';

    /**
     * @var string
     */
    public const ASSET_TYPE_VIDEO = 'VIDEO';

    /**
     * @var string
     */
    public const ASSET_TYPE_CHAPTER = 'CHAPTER';

    /**
     * @var string
     */
    public const DIFFICULTY_LEVEL_BEGINNER = 'BEGINNER';

    /**
     * @var string
     */
    public const DIFFICULTY_LEVEL_INTERMEDIATE = 'INTERMEDIATE';

    /**
     * @var string
     */
    public const DIFFICULTY_LEVEL_ADVANCED = 'ADVANCED';

    /**
     * @var string
     */
    public const SORT_BY_POPULARITY = 'POPULARITY';

    /**
     * @var string
     */
    public const SORT_BY_RELEVANCE = 'RELEVANCE';

    /**
     * @var string
     */
    public const SORT_BY_RECENCY = 'RECENCY';

    /**
     * @param string $sort_by
     * @return bool
     */
    public static function is_valid_sort_by(string $sort_by): bool {
        return in_array($sort_by, [self::SORT_BY_POPULARITY, self::SORT_BY_RECENCY, self::SORT_BY_RELEVANCE]);
    }

    /**
     * @param string $asset_type
     * @return bool
     */
    public static function is_valid_asset_type(string $asset_type): bool {
        return in_array(
            $asset_type,
            [
                self::ASSET_TYPE_CHAPTER,
                self::ASSET_TYPE_COURSE,
                self::ASSET_TYPE_LEARNING_PATH,
                self::ASSET_TYPE_VIDEO
            ]
        );
    }

    /**
     * @param string $asset_type
     */
    public static function validate_asset_type(string $asset_type): void {
        if (!self::is_valid_asset_type($asset_type)) {
            throw new coding_exception("Invalid asset type: $asset_type");
        }
    }

    /**
     * @param string $level
     * @return bool
     */
    public static function is_valid_difficulty_level(string $level): bool {
        return in_array(
            $level,
            [
                static::DIFFICULTY_LEVEL_ADVANCED,
                static::DIFFICULTY_LEVEL_BEGINNER,
                static::DIFFICULTY_LEVEL_INTERMEDIATE
            ]
        );
    }

    /**
     * @param string $level
     */
    public static function validate_difficulty_level(string $level): void {
        if (!self::is_valid_difficulty_level($level)) {
            throw new coding_exception("Invalid difficulty level: $level");
        }
    }

}