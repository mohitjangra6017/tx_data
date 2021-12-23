<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Admin config settings class
 *
 * @package    auth_simplesamlidp
 * @author     Ben Lobo <ben.lobo@kineo.com>
 * @copyright  2019 City and Guilds Kineo
 * @license    http://www.kineo.com
 */

namespace auth_simplesamlidp\Settings;

use admin_setting_heading;

defined('MOODLE_INTERNAL') || die();

/**
 * Settings for a text only admin setting.
 *
 * @package    auth_simplesamlidp
 * @author     Ben Lobo <ben.lobo@kineo.com>
 * @copyright  Matt Porritt <mattp@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class TextOnly extends admin_setting_heading
{
    /**
     * {@inheritdoc}
     */
    public function output_html($data, $query = '')
    {
        return format_admin_setting($this, $this->visiblename, '', $this->description);
    }
}
