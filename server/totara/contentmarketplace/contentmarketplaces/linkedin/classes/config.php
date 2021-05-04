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
 * @author Kian Nguyen <kian.nguyen@totaralearning.com>
 * @package contentmarketplace_linkedin
 */
namespace contentmarketplace_linkedin;

use core\base_plugin_config;

class config extends base_plugin_config {
    /**
     * The configuration key.
     * @var string
     */
    public const LIL_SYNC_LAST_TIME_RUN = 'lil_sync_last_time_run';

    /**
     * @var string
     */
    public const ACCESS_TOKEN_ENDPOINT = 'https://www.linkedin.com/oauth/v2/accessToken';

    /**
     * This is Totara partner identifier within linkedin learning system.
     * For more information, see https://docs.microsoft.com/en-us/linkedin/learning/getting-started/partner-identifier
     *
     * @var string
     */
    public const PARTNER_IDENTIFIER = 'urn:li:partner:totara';

    /**
     * @return string
     */
    protected static function get_component(): string {
        return 'contentmarketplace_linkedin';
    }

    /**
     * @return string|null
     */
    public static function client_id(): ?string {
        return static::get('client_id');
    }

    /**
     * @return string|null
     */
    public static function client_secret(): ?string {
        return static::get('client_secret');
    }

    /**
     * Returns the value of access token.
     * @return string|null
     */
    public static function access_token(): ?string {
        return static::get('access_token');
    }

    /**
     * Returns the expiry dates of the access token.
     * @return int|null
     */
    public static function access_token_expiry(): ?int {
        $expiry = static::get('access_token_expiry');
        if (null === $expiry) {
            return null;
        }

        return (int) $expiry;
    }

    /**
     * @return string
     */
    public static function access_token_endpoint(): string {
        return static::get('access_token_endpoint', static::ACCESS_TOKEN_ENDPOINT);
    }

    /**
     * @param string|null $value
     */
    public static function save_access_token(?string $value): void {
        static::set('access_token', $value);
    }

    /**
     * @param int|null $value
     */
    public static function save_access_token_expiry(?int $value): void {
        static::set('access_token_expiry', $value);
    }

    /**
     * @param int|null $time
     */
    public static function set_last_time_run(?int $time = null): void {
        if (is_null($time)) {
            $time = time();
        }

        set_config(self::LIL_SYNC_LAST_TIME_RUN, $time, 'contentmarketplace_linkedin');
    }

    /**
     * @return int|null
     */
    public static function get_last_time_run(): ?int {
        return get_config('contentmarketplace_linkedin', self::LIL_SYNC_LAST_TIME_RUN);
    }
}