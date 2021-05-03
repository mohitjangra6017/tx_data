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
 * @author Mark Metcalfe <mark.metcalfe@totaralearning.com>
 * @package contentmarketplace_linkedin
 */

namespace contentmarketplace_linkedin\entity;

use core\orm\entity\entity;

/**
 * A LinkedIn learning object that has been fetched and stored locally within Totara.
 *
 * Properties:
 * @property string $urn
 * @property string $title
 * @property string|null $description
 * @property string|null $description_include_html
 * @property string|null $short_description
 * @property string $locale_language
 * @property string $locale_country
 * @property int $last_updated_at
 * @property int $published_at
 * @property int|null $retired_at
 * @property string|null $level
 * @property string|null $primary_image_url
 * @property int|null $time_to_complete
 * @property string|null $web_launch_url
 * @property string|null $sso_launch_url
 *
 * @method static learning_object_repository repository
 *
 * @package contentmarketplace_linkedin\entity
 */
class learning_object extends entity {

    public const TABLE = 'marketplace_linkedin_learning_object';

}
