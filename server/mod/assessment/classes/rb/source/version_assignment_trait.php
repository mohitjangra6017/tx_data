<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

namespace mod_assessment\rb\source;

use mod_assessment\model\role;

trait version_assignment_trait {
    public function add_version_assignment_columns(&$columnoptions, string $join) {
        $columnoptions[] = new \rb_column_option(
            'assessment_version_assignment',
            'role',
            get_string('role', 'rb_source_assessment_version_assignment'),
            "{$join}.role",
            ['displayfunc' => 'assessment_role']
        );
    }

    public function add_version_assignment_filters(&$filteroptions) {
        $filteroptions[] = new \rb_filter_option(
            'assessment_version_assignment',
            'role',
            get_string('role', 'rb_source_assessment_version_assignment'),
            'select',
            ['selectchoices' => role::get_assignable_roles()]
        );
    }
}
