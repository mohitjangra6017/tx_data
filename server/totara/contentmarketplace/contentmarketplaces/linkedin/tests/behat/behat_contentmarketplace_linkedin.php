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
 * @author  Mark Metcalfe <mark.metcalfe@totaralearning.com>
 * @package contentmarketplace_linkedin
 */

use contentmarketplace_linkedin\testing\generator;

require_once(__DIR__ . '/../../../../../../lib/behat/behat_base.php');

class behat_contentmarketplace_linkedin extends behat_base {

    /**
     * @Given /^I set up the LinkedIn Learning content marketplace plugin$/
     */
    public function set_up_configuration(): void {
        behat_hooks::set_step_readonly(false);

        $this->execute('behat_totara_contentmarketplace::enable_contentmarketplace_plugin', ['linkedin', 'enabled']);

        generator::instance()->set_up_configuration();
    }

}
