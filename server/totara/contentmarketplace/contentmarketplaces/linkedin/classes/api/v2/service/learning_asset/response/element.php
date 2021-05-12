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
namespace contentmarketplace_linkedin\api\v2\service\learning_asset\response;

use coding_exception;
use contentmarketplace_linkedin\api\response\element as base_element;
use contentmarketplace_linkedin\dto\locale;
use contentmarketplace_linkedin\dto\timespan;
use contentmarketplace_linkedin\dto\timestamp;
use core\json\schema\collection;
use core\json\schema\field\field_alpha;
use core\json\schema\field\field_alphaext;
use core\json\schema\field\field_clean_html;
use core\json\schema\field\field_collection;
use core\json\schema\field\field_int;
use core\json\schema\field\field_object;
use core\json\schema\field\field_text;
use core\json\schema\field\field_url;
use core\json\schema\object_container;

/**
 * @method static element create(array $json_data)
 */
class element extends base_element {
    /**
     * @return object_container
     */
    public static function get_json_schema(): object_container {
        $locale_obj = object_container::create(
            new field_alpha('language'),
            new field_alpha('country')
        );

        return object_container::create(
            new field_text('urn'),
            new field_object(
                'title',
                object_container::create(
                    new field_text('value'),
                    new field_object('locale', $locale_obj)
                ),
            ),
            new field_alphaext('type'),
            new field_object(
                'details',
                object_container::create(
                    new field_alpha('level', false),
                    new field_alpha('availability', false),
                    new field_int('lastUpdatedAt'),
                    new field_int('publishedAt'),
                    new field_object(
                        'images',
                        object_container::create(
                            new field_url('primary', false)
                        )
                    ),
                    new field_object(
                        'descriptionIncludingHtml',
                        object_container::create(
                            new field_clean_html('value'),
                            new field_object('locale', $locale_obj)
                        ),
                        false
                    ),
                    new field_object(
                        'description',
                        object_container::create(
                            new field_text('value'),
                            new field_object('locale', $locale_obj)
                        ),
                        false
                    ),
                    new field_collection(
                        'availableLocales',
                        new collection($locale_obj),
                        false
                    ),
                    new field_object(
                        'urls',
                        object_container::create(
                            new field_url('aiccLaunch', false),
                            new field_url('ssoLaunch', false),
                            new field_url('webLaunch', false)
                        )
                    ),
                    new field_object(
                        'shortDescription',
                        object_container::create(
                            new field_text('value'),
                            new field_object('locale', $locale_obj)
                        ),
                        false
                    ),
                    new field_object(
                        'shortDescriptionIncludingHtml',
                        object_container::create(
                            new field_clean_html('value'),
                            new field_object('locale', $locale_obj)
                        ),
                        false
                    ),
                    new field_object(
                        'timeToComplete',
                        object_container::create(
                            new field_int('duration'),
                            new field_alpha('unit')
                        ),
                        false
                    ),
                ),
                false
            ),
        );
    }

    /**
     * @return string
     */
    public function get_urn(): string {
        return $this->json_data['urn'];
    }

    /**
     * @return string
     */
    public function get_title_value(): string {
        if (!isset($this->json_data['title']) || !isset($this->json_data['title']['value'])) {
            throw new coding_exception(
                'The json data does not have title'
            );
        }

        return $this->json_data['title']['value'];
    }

    /**
     * @return locale
     */
    public function get_title_locale(): locale {
        $locale_json = $this->json_data['title']['locale'];

        return new locale(
            $locale_json['language'],
            $locale_json['country']
        );
    }

    /**
     * Get the last updated at timestamp.
     * Unit is in milliseconds.
     *
     * @return timestamp
     */
    public function get_last_updated_at(): timestamp {
        return new timestamp($this->json_data['details']['lastUpdatedAt'], timestamp::MILLISECONDS);
    }

    /**
     * Get the published at timestamp.
     * Unit is in milliseconds.
     *
     * @return timestamp
     */
    public function get_published_at(): timestamp {
        return new timestamp($this->json_data['details']['publishedAt'], timestamp::MILLISECONDS);
    }

    /**
     * Get the retired at timestamp.
     * Unit is in milliseconds.
     *
     * @return timestamp|null
     */
    public function get_retired_at(): ?timestamp {
        if (!isset($this->json_data['details']['retiredAt'])) {
            return null;
        }

        // TODO: Check if this is actually returned by the API.
        return new timestamp($this->json_data['details']['retiredAt'], timestamp::MILLISECONDS);
    }

    /**
     * @return string|null
     */
    public function get_description_value(): ?string {
        if (!isset($this->json_data['details']['description'])) {
            return null;
        }

        $details = $this->json_data['details']['description'];
        return $details['value'];
    }

    /**
     * @return locale|null
     */
    public function get_description_locale(): ?locale {
        if (!isset($this->json_data['details']['description'])) {
            return null;
        }

        $locale = $this->json_data['details']['description']['locale'];
        return new locale($locale['language'], $locale['country'] ?? null);
    }

    /**
     * @return string|null
     */
    public function get_description_include_html(): ?string {
        if (!isset($this->json_data['details']['descriptionIncludingHtml'])) {
            return null;
        }

        $details = $this->json_data['details']['descriptionIncludingHtml'];
        return $details['value'];
    }

    /**
     * @return locale|null
     */
    public function get_description_include_html_locale(): ?locale {
        if (!isset($this->json_data['details']['descriptionIncludingHtml'])) {
            return null;
        }

        $details = $this->json_data['details']['descriptionIncludingHtml'];
        $locale = $details['locale'];

        return new locale($locale['language'], $locale['country'] ?? null);
    }

    /**
     * @return string|null
     */
    public function get_short_description_value(): ?string {
        if (!isset($this->json_data['details']['shortDescription'])) {
            return null;
        }

        $short_description = $this->json_data['details']['shortDescription'];
        return $short_description['value'];
    }

    /**
     * @return locale|null
     */
    public function get_short_description_locale(): ?locale {
        if (!isset($this->json_data['details']['shortDescription'])) {
            return null;
        }

        $details = $this->json_data['details']['shortDescription'];
        $locale = $details['locale'];

        return new locale($locale['language'], $locale['country'] ?? null);
    }

    /**
     * @return string|null
     */
    public function get_level(): ?string {
        return $this->json_data['details']['level'] ?? null;
    }

    /**
     * @return string|null
     */
    public function get_primary_image_url(): ?string {
        $images = $this->json_data['details']['images'];
        return $images['primary'] ?? null;
    }

    /**
     * @return timespan|null
     */
    public function get_time_to_complete(): ?timespan {
        if (!isset($this->json_data['details']['timeToComplete'])) {
            return null;
        }

        $time_to_complete = $this->json_data['details']['timeToComplete'];

        return new timespan($time_to_complete['duration'], $time_to_complete['unit']);
    }

    /**
     * @return string|null
     */
    public function get_web_launch_url(): ?string {
        $urls = $this->json_data['details']['urls'];
        return $urls['webLaunch'] ?? null;
    }

    /**
     * @return string|null
     */
    public function get_sso_launch_url(): ?string {
        $urls = $this->json_data['details']['urls'];
        return $urls['ssoLaunch'] ?? null;
    }

    /**
     * @return string|null
     */
    public function get_type(): string {
        return $this->json_data['type'];
    }
}