<?php
/**
 * This file is part of Totara Learn
 *
 * Copyright (C) 2020 onwards Totara Learning Solutions LTD
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
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Kunle Odusan <kunle.odusan@totaralearning.com>
 */

namespace performelement_redisplay;

use mod_perform\models\activity\element_plugin;
use performelement_redisplay\data_provider\redisplay_data;

class redisplay extends element_plugin {

    /**
     * @inheritDoc
     */
    public function get_sortorder(): int {
        return 90;
    }

    /**
     * @inheritDoc
     */
    public function get_group(): int {
        return self::GROUP_OTHER;
    }

    /**
     * @inheritDoc
     */
    public function has_title(): bool {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function get_title_text(): string {
        return get_string('instruction_text', 'performelement_redisplay');
    }

    /**
     * @inheritDoc
     */
    public function is_title_required(): bool {
        return false;
    }

    /**
     * Modify json data to add extra information to it.
     *
     * @param string|null $data
     * @return string|null
     */
    public function process_data(?string $data): ?string {
        if (empty($data)) {
            return $data;
        }
        $modified_data = (new redisplay_data())->include_extra_info(json_decode($data, true));

        return json_encode($modified_data);
    }
}