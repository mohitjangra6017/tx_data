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
 * - refactored to choose uses with roles generically (i.e. evaluators AND reviewers not just the former).
 */

namespace mod_assessment\dialog;

use core\orm\query\sql\query;
use mod_assessment\model\attempt;
use mod_assessment\model\version;
use mod_assessment\processor\role_user_processor;
use totara_dialog_content_users;
use function totara_search_get_keyword_where_clause;

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->dirroot . '/totara/core/dialogs/dialog_content.class.php');

class roleusers extends totara_dialog_content_users
{
    /** @var attempt */
    public attempt $attempt;

    /** @var version */
    public version $version;

    protected $role;

    public array $searchparams = [];

    public function __construct($role)
    {
        parent::__construct();

        $this->role = $role;
        $this->searchtype = 'this';
        $this->type = self::TYPE_CHOICE_SINGLE;
    }

    public function get_items(): array
    {
        $processor = new role_user_processor($this->version, $this->role);
        return $processor->get_valid_role_users($this->attempt);
    }

    public function put_search_info($search_info, &$formdata, $keywords)
    {
        global $DB;

        // Set hidden search data in the forms.
        if (!isset($formdata['hidden']) || !is_array($formdata['hidden'])) {
            $formdata['hidden'] = array();
        }

        foreach ($this->searchparams as $key => $value) {
            $formdata['hidden'][$key] = $value;
        }

        // Generate search info.
        $fields = get_all_user_name_fields();
        $processor = new role_user_processor($this->version, $this->role);
        [$searchsql, $searchparams] = totara_search_get_keyword_where_clause($keywords, $fields, SQL_PARAMS_NAMED);

        // The search_info->sql needs a statement without the 1st SELECT
        $builder = $processor->get_query_builder($this->attempt);
        $builder->where_raw($searchsql, $searchparams);
        $builder->select_raw('');
        list($sql, $params) = query::from_builder($builder)->build();
        $pos = strpos($sql, 'SELECT');
        if ($pos !== false) {
            $sql = substr_replace($sql, '', $pos, strlen('SELECT'));
        }

        $fullnamefields = totara_get_all_user_name_fields_join(null, null, true);
        $search_info->id = 'auser.id';
        $search_info->fullname = $DB->sql_concat_join("' '", $fullnamefields);
        $search_info->order = 'ORDER BY firstname, lastname';
        $search_info->params = $params;
        $search_info->sql = $sql;
    }

    public function set_attempt(attempt $attempt)
    {
        $this->attempt = $attempt;
    }

    public function set_version(version $version)
    {
        $this->version = $version;
    }
}
