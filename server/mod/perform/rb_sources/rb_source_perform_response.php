<?php
/**
 *
 * This file is part of Totara Perform
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
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Simon Coggins <simon.coggins@totaralearning.com>
 * @author Fabian Derschatta <fabian.derschatta@totaralearning.com>
 * @package mod_perform
 *
 */

use mod_perform\rb\traits\course_visibility_trait;
use mod_perform\rb\util;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/mod/perform/rb_sources/rb_source_perform_response_base.php');

/**
 * Performance reporting response report.
 *
 * This is an extension of the rb_source_perform_response source but with additional capability checks applied.
 *
 * Class rb_source_perform_response
 */
class rb_source_perform_response extends rb_source_perform_response_base {

    use course_visibility_trait;

    /**
     * Constructor.
     *
     * @param mixed $groupid
     * @param rb_global_restriction_set|null $globalrestrictionset
     * @throws coding_exception
     */
    public function __construct($groupid, rb_global_restriction_set $globalrestrictionset = null) {
        parent::__construct($groupid, $globalrestrictionset);

        // This source is not available for user selection - it is used by the embedded report only.
        $this->selectable = false;

        $this->sourcetitle = get_string('sourcetitle', 'rb_source_perform_response');
        $this->sourcesummary = get_string('sourcesummary', 'rb_source_perform_response');
        $this->sourcelabel = get_string('sourcelabel', 'rb_source_perform_response');

        $this->add_course_visibility('perform');

        // NOTE: This is necessary here to support restrictions added in $this->post_config()
        // Not ideal but there isn't a way to force joins to be added in post_config
        if (!in_array('subject_instance', $this->sourcejoins)) {
            $this->sourcejoins[] = 'subject_instance';
        }
    }

    public function post_config(reportbuilder $report) {
        // NOTE: For this to work, subject_instance must be included in the $this->sourcejoins array defined in the constructor.
        // Not ideal but there isn't a way to force joins to be added in post_config
        $restrictions = util::get_report_on_subjects_sql($report->reportfor, "subject_instance.subject_user_id");
        $restrictions = $this->create_course_visibility_restrictions($report, $restrictions);

        $report->set_post_config_restrictions($restrictions);
    }

}
