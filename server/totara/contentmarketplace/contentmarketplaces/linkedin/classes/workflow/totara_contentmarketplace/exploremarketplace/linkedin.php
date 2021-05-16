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
 * @package contentmarketplace_linkedin
 */
namespace contentmarketplace_linkedin\workflow\totara_contentmarketplace\exploremarketplace;

use totara_contentmarketplace\plugininfo\contentmarketplace;
use totara_workflow\workflow\base;
use moodle_url;

/**
 * linkedIn explore marketplace workflow implementation.
 */
class linkedin extends base {
    /**
     * @inheritDoc
     */
    public function get_name(): string {
        return get_string('explore_lil_marketplace', 'contentmarketplace_linkedin');
    }

    /**
     * @inheritDoc
     */
    public function get_description(): string {
        return get_string('explore_lil_marketplace_description', 'contentmarketplace_linkedin');
    }

    /**
     * @inheritDoc
     */
    protected function get_workflow_url(): moodle_url {
        return new moodle_url('/totara/contentmarketplace/explorer.php', ['marketplace' => 'linkedin']);
    }

    /**
     * @inheritDoc
     */
    public function get_image(): moodle_url {
        return new moodle_url('/totara/contentmarketplace/contentmarketplaces/linkedin/pix/logo.png');
    }

    /**
     * @inheritDoc
     */
    public function can_access(): bool {
        $plugin = contentmarketplace::plugin('linkedin');
        if ($plugin === null || !$plugin->is_enabled()) {
            return false;
        }
        return true;
    }

}
