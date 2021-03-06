<?php
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2010 onwards Totara Learning Solutions LTD
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
 * @author Aaron Wells <aaronw@catalyst.net.nz>
 * @package totara
 * @subpackage cohort
 */
if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.'); //  It must be included from a Moodle page
}

global $CFG;
require_once($CFG->dirroot.'/totara/reportbuilder/rb_sources/rb_source_user.php');

/**
 * A report builder source for users that aren't in any cohort table.
 */
class rb_source_cohort_orphaned_users extends rb_source_user {
    /**
     * Constructor
     */
    public function __construct($groupid, rb_global_restriction_set $globalrestrictionset = null) {
        global $CFG;
        if ($groupid instanceof rb_global_restriction_set) {
            throw new coding_exception('Wrong parameter orders detected during report source instantiation.');
        }
        // Remember the active global restriction set.
        $this->globalrestrictionset = $globalrestrictionset;

        $this->cacheable = false;

        // Global Report Restrictions applied in rb_source_user are also reflect on orphaned user report.

        require_once($CFG->dirroot.'/cohort/lib.php');
        parent::__construct($groupid, $globalrestrictionset);
        $this->base = "(
            select *
            from {user} u
            where
                not exists (
                    select 1
                    from {cohort_members} cm
                        inner join {cohort} c
                        on cm.cohortid=c.id
                    where
                        cm.userid=u.id
                        and " . totara_cohort_date_where_clause( 'c' ) . '
                )
                AND u.id <> 1
                AND u.deleted = 0
                AND u.confirmed = 1
            )';
        $this->sourcetitle = get_string('sourcetitle', 'rb_source_cohort_orphaned_users');
        $this->sourcesummary = get_string('sourcesummary', 'rb_source_cohort_orphaned_users');
        $this->sourcelabel = get_string('sourcelabel', 'rb_source_cohort_orphaned_users');

    }

    /**
     * Global report restrictions are implemented in this source.
     * @return boolean
     */
    public function global_restrictions_supported() {
        return true;
    }
}
