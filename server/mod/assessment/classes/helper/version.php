<?php
/**
 * @copyright 2010 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Alex Glover <alex.glover@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

namespace mod_assessment\helper;

use coding_exception;
use mod_assessment\model\rule;
use mod_assessment\model\ruleset;

class version
{

    /**
     * @param \mod_assessment\model\version $version
     * @return float
     * @throws coding_exception
     */
    public static function get_activation_warnings(\mod_assessment\model\version $version)
    {

        $roles = [role::EVALUATOR, role::REVIEWER];

        $warnings = [];

        $max_user_count = trim(get_config('assessment', 'maximumuserwarningcount'));
        if (empty($max_user_count) || !is_number($max_user_count)) {
            return false;
        }

        if ($version->singleevaluator && $version->singlereviewer) {
            return false;
        }

        foreach ($roles as $role) {
            if (($role == role::EVALUATOR && $version->singleevaluator) || ($role == role::REVIEWER && $version->singlereviewer)) {
                continue;
            }

            if ($rulesets = ruleset::instances_from_version($version, $role)) {
                foreach ($rulesets as $ruleset) {
                    $rules = rule::instances(['rulesetid' => $ruleset->id]);

                    foreach ($rules as $rule) {
                        $type = $rule->get_type();
                        $users_count = $rule->get_potential_users_count();
                        if ($users_count > $max_user_count) {
                            $typestr = ($rule->operator == $rule::OP_IN_NOTEQUAL ? get_string('ruledesc_not', 'assessment') . ' ' : '');
                            $typestr .= get_string('rule' . $type, 'assessment');
                            $a = (object)['rolename' => role::get_string($role), 'ruletype' => $typestr, 'usercount' => $users_count];
                            $warnings[$rule->id] = get_string('ruleusercountwarningx', 'assessment', $a);
                        }
                    }
                }
            }
        }

        $warnings = (empty($warnings) ? false : $warnings);

        return $warnings;
    }

}
