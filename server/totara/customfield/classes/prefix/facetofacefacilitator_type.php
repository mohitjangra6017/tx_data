<?php
/*
* This file is part of Totara Learn
*
* Copyright (C) 2019 onwards Totara Learning Solutions LTD
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
* @author Oleg Demeshev <oleg.demeshev@totaralearning.com>
* @package totara_customfield
*/

namespace totara_customfield\prefix;

defined('MOODLE_INTERNAL') || die();

class facetofacefacilitator_type extends type_base {

    public function __construct($prefix, $context, $extrainfo = array()) {
        parent::__construct($prefix, 'facetoface_facilitator', 'facetoface_facilitator', $context, $extrainfo);
    }

    public function get_capability_managefield(): string {
        return 'mod/facetoface:managecustomfield';
    }
}