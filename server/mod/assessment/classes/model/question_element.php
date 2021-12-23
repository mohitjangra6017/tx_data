<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

namespace mod_assessment\model;

use renderer_base;

interface question_element
{
    public function export_for_template_role(renderer_base $output, role $questionrole): array;
}
