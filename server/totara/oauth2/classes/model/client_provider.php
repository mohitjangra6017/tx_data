<?php
/**
 * This file is part of Totara Core
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
 * @package totara_oauth2
 */
namespace totara_oauth2\model;

use core\orm\entity\model;
use totara_oauth2\entity\client_provider as entity;

/**
 *
 * @property-read int $id
 * @property-read string $client_id
 * @property-read string $client_secret
 * @property-read string $id_number
 * @property-read string $name
 * @property-read string|null $description
 * @property-read int|null $description_format
 * @property-read string|null $scope
 * @property-read string|null $grant_types
 * @property-read int $time_created
 * @property-read string|null $component
 *
 * @property-read string $oauth_url
 * @property-read string $xapi_url
 */
class client_provider extends model {
    /**
     * @var string[]
     */
    protected $entity_attribute_whitelist = [
        'id',
        'client_id',
        'client_secret',
        'id_number',
        'name',
        'description',
        'description_format',
        'scope',
        'grant_types',
        'time_created',
        'component'
    ];

    /**
     * @var string[]
     */
    protected $model_accessor_whitelist = [
        'oauth_url',
        'xapi_url'
    ];

    /**
     * @return string
     */
    protected static function get_entity_class(): string {
        return entity::class;
    }

    /**
     * @return string
     */
    public function get_oauth_url(): string {
        global $CFG;
        return "{$CFG->wwwroot}/totara/oauth2/token.php";
    }

    /**
     * @return string
     */
    public function get_xapi_url(): string {
        global $CFG;
        return "{$CFG->wwwroot}/totara/xapi/receiver.php?component={$this->component}";
    }
}