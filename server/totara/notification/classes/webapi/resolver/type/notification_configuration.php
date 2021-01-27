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
 * @package totara_notification
 */
namespace totara_notification\webapi\resolver\type;

use core\webapi\execution_context;
use core\webapi\type_resolver;
use totara_notification\notification\built_in_notification;
use coding_exception;

final class notification_configuration implements type_resolver {
    /**
     * @param string $field
     * @param built_in_notification $source
     * @param array $args
     * @param execution_context $ec
     * @return mixed|void
     */
    public static function resolve(string $field, $source, array $args, execution_context $ec) {
        /** @var built_in_notification $source */
        if (!($source instanceof built_in_notification)) {
            throw new \coding_exception("Invalid parameter \$source being passed in");
        }

        switch ($field) {
            case 'recipient':
                return $source::get_recipient_name();
            case 'title':
                return $source::get_title();
            default:
                throw new coding_exception("Field '{$field}' is not yet supported");
        }
    }
}