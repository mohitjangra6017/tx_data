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

namespace contentmarketplace_linkedin\controllers;

use totara_contentmarketplace\controllers\catalog_import as base_catalog_import;
use totara_mvc\tui_view;

final class catalog_import extends base_catalog_import {
    /**
     * @inheritDoc
     */
    public function action(): tui_view {
        return $this->create_tui_view(
            'contentmarketplace_linkedin/pages/CatalogImport',
            ['canManageContent' => $this->can_manage_marketplace_plugins()]
        )->set_title($this->explorer->get_heading());
    }
}