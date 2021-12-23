<?php
/**
 * @copyright 2017-2019 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @author Alex Glover <alex.glover@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 *
 * DEVIOTIS2
 * - Refactored to generic role users processor
 */

namespace mod_assessment\processor;

use context_user;
use core\orm\query\builder;
use core\tenant_orm_helper;
use mod_assessment\entity\assessment_version_assignment;
use mod_assessment\model\attempt;
use mod_assessment\model\role;
use mod_assessment\model\rule;
use mod_assessment\model\ruleset;
use mod_assessment\model\version;

class role_user_processor
{

    /** @var version */
    protected version $version;

    /** @var role */
    protected role $role;

    /**
     *
     * @param version $version
     * @param role $role
     */
    public function __construct(version $version, role $role)
    {
        $this->version = $version;
        $this->role = $role;
    }

    /**
     * @return version
     */
    public function get_version()
    {
        return $this->version;
    }

    /**
     * @param attempt $attempt
     * @return array
     */
    public function get_where_sql(attempt $attempt): array
    {
        $rulesetsql = [];
        $params = [];
        $versionop = $this->version->operator == rule::OP_AND ? ') AND (' : ') OR (';

        $rulesets = ruleset::instances(['versionid' => $this->version->id, 'role' => $this->role->value()]);

        if (empty($rulesets)) {
            return ['1=0', $params];
        }

        foreach ($rulesets as $ruleset) {
            $rulesqls = [];
            $rulesetop = $ruleset->operator == rule::OP_AND ? ' AND ' : ' OR ';

            $rules = rule::instances(['rulesetid' => $ruleset->id]);
            foreach ($rules as $rule) {
                [$rulesql, $ruleparams] = $rule->get_sql($attempt);
                $params += $ruleparams;
                $rulesqls[] = $rulesql;
            }

            $rulesetsql[] = implode($rulesetop, $rulesqls);
        }

        // Cannot evaluate self.
        $sql = "(" . implode($versionop, $rulesetsql) . ")";
        $sql .= " AND NOT auser.id = :learnerid";
        $params['learnerid'] = $attempt->userid;

        return [$sql, $params];
    }

    /**
     * @param attempt $attempt
     * @param bool $populate_names
     * @return array
     */
    public function get_valid_role_users(attempt $attempt, $populate_names = true): array
    {
        $builder = $this->get_query_builder($attempt);
        $users = $builder->fetch();

        if ($users && $populate_names) {
            array_walk($users, function ($user) {
                $user->fullname = fullname($user);
            });
        }

        return $users;
    }

    /**
     * @param $attempt
     * @return builder
     */
    public function get_query_builder($attempt): builder
    {
        global $USER;

        [$wheresql, $params] = $this->get_where_sql($attempt);
        $params['avarole'] = $this->role->value();
        $params['avaversionid'] = $this->version->get_id();
        $assessmentid = $this->get_version()->get_assessmentid();
        $cm = get_coursemodule_from_instance('assessment', $assessmentid);
        $context = \context_module::instance($cm->id);

        $builder = new builder();
        return $builder->table('user', 'auser')
                       ->left_join(
                           [assessment_version_assignment::get_tablename(), 'ava'],
                           'ava.userid',
                           '=',
                           'auser.id'
                       )
                       ->where(
                           function (builder $builder) use ($wheresql, $params) {
                               $builder->where_raw($wheresql, $params)
                                       ->or_where(
                                           function (builder $builder) use ($params) {
                                               $builder->where('ava.versionid', '=', $params['avaversionid'])
                                                       ->where('ava.role', '=', $params['avarole']);
                                           }
                                       );
                           }
                       )
                       ->when(
                           true,
                           function (builder $builder) use ($context) {
                               tenant_orm_helper::restrict_users(
                                   $builder,
                                   'auser.id',
                                   $context
                               );
                           }
                       );
    }
}
