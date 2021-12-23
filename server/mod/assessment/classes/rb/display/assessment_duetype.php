<?php
/**
 * This file is part of Totara LMS
 *
 * Copyright (C) 2010-2020 Kineo Group Inc.
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
 */

/**
 * Form for assessment due dates
 *
 * @package     mod_assessment
 * @author      Russell England <Russell.England@kineo.com>
 * @copyright   2020 Kineo Group Inc. <http://www.kineo.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

namespace mod_assessment\rb\display;

use mod_assessment\helper;

use rb_column;
use reportbuilder;
use stdClass;
use totara_reportbuilder\rb\display\base;

defined('MOODLE_INTERNAL') || die();

class assessment_duetype extends base
{

    public static function display($value, $format, stdClass $row, rb_column $column, reportbuilder $report): ?string
    {
        $duetypes = helper\assessment_due_helper::get_duetypes();

        if (!empty($duetypes[$value])) {
            return $duetypes[$value];
        }

        return $value;
    }

}
