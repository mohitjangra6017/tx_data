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
 * Model for assessment due dates
 *
 * @package     mod_assessment
 * @author      Russell England <Russell.England@kineo.com>
 * @copyright   2020 Kineo Group Inc. <http://www.kineo.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

namespace mod_assessment\model;

use mod_assessment\entity\SimpleDBO;
use model\assessment;

defined('MOODLE_INTERNAL') || die();

class assessment_due extends SimpleDBO
{

    public const TABLE = 'assessment_due';

    public int $assessmentid;
    public int $userid;
    public int $timedue;

    public function get_daysdue()
    {
        if (empty($this->timedue)) {
            return 0;
        }

        return (int)($this->timedue - time()) / 24 / 60 / 60;
    }

}
