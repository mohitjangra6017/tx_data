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
 * @author  Kian Nguyen <kian.nguyen@totaralearning.com>
 * @author  Mark Metcalfe <mark.metcalfe@totaralearning.com>
 * @package totara_xapi
 */

namespace totara_xapi\model;

use core\entity\user;
use core\orm\entity\model;
use totara_xapi\entity\xapi_statement as xapi_statement_entity;
use totara_xapi\handler\base_handler;
use totara_xapi\request\request;
use totara_xapi\response\facade\result;

/**
 * @property-read int $id
 * @property-read int $time_created
 * @property-read string|null $component
 * @property-read int|null $user_id
 * @property-read user|null $user
 *
 * @property-read array $statement
 */
class xapi_statement extends model {

    /**
     * @var xapi_statement_entity
     */
    protected $entity;

    /**
     * @var base_handler
     */
    protected $handler;

    /**
     * @var array
     */
    private $statement_parsed;

    /**
     * @var string[]
     */
    protected $entity_attribute_whitelist = [
        'id',
        'time_created',
        'component',
        'user_id',
        'user',
    ];

    /**
     * @var string[]
     */
    protected $model_accessor_whitelist = [
        'statement',
    ];

    /**
     * @return string
     */
    protected static function get_entity_class(): string {
        return xapi_statement_entity::class;
    }

    /**
     * Authenticate, log, and process the xAPI request statement.
     *
     * @param request $request
     * @param base_handler $handler
     * @return self
     */
    public static function create_from_request(request $request, base_handler $handler): self {
        // Creating a new xapi_statement entity.
        $entity = new xapi_statement_entity();
        $entity->statement = $request->get_content();
        $entity->component = $request->get_required_parameter("component", PARAM_COMPONENT);
        $handler->validate_structure($entity->statement);
        $entity->user_id = $handler->get_user_id($entity->statement);

        $entity->save();

        $model = static::load_by_entity($entity);
        $model->handler = $handler;

        return $model;
    }

    /**
     * Process this xAPI statement.
     *
     * @return result
     */
    public function process(): result {
        return $this->handler->process($this);
    }

    /**
     * @return array
     */
    public function get_statement(): array {
        if (!isset($this->statement_parsed)) {
            $this->statement_parsed = json_decode($this->entity->statement, true, 512, JSON_THROW_ON_ERROR);
        }
        return $this->statement_parsed;
    }

}
