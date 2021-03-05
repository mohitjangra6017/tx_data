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
 * @author  Qingyang Liu<qingyang.liu@totaralearning.com>
 * @package totara_notification
 */
namespace totara_notification\webapi\resolver\type;

use coding_exception;
use core\webapi\execution_context;
use core\webapi\type_resolver;
use totara_notification\local\helper;
use \totara_notification\recipient\recipient as notification_recipient;

final class recipient implements type_resolver {
    /**
     * @param string $field
     * @param notification_recipient $source
     * @param array $args
     * @param execution_context $ec
     * @return mixed
     */
    public static function resolve(string $field, $source, array $args, execution_context $ec) {
        if (!helper::is_valid_recipient_class($source)) {
            throw new coding_exception(
                "Invalid recipient passed to the resolver"
            );
        }

        switch ($field) {
            case 'name':
                return $source::get_name();

            case 'class_name':
                return $source;

            default:
                throw new coding_exception("The field '{$field}' had not yet supported");
        }
    }
}