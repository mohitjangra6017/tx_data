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
 * @author  Kian Nguyen <kian.nguyen@totaralearning.com>
 * @package weka_simple_multi_lang
 */
defined('MOODLE_INTERNAL') || die();

$plugin->version = 2021110500;        // The current plugin version (Date: YYYYMMDDXX)
$plugin->requires = 2021110500;        // Requires this Totara version
$plugin->component = 'weka_simple_multi_lang';        // Full name of the plugin (used for diagnostics)

$plugin->dependencies = [
    'filter_multilang' => 2020101200,
    'weka_notification_placeholder' => 2021022400,
    'totara_notification' => 2021012003
];