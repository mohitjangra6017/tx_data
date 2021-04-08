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
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Kunle Odusan <kunle.odusan@totaralearning.com>
 * @package mod_perform
 */

defined('MOODLE_INTERNAL') || die();

$plugin->version  = 2021032300;       // The current module version (Date: YYYYMMDDXX).
$plugin->requires = 2020101200;       // Requires this Totara version.
$plugin->component = 'performelement_competency_rating'; // To check on upgrade, that module sits in correct place
$plugin->dependencies = array(
    'performelement_linked_review' => 2021031502,
    'totara_competency' => 2021012600,
);
