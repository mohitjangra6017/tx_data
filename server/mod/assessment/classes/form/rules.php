<?php
/**
 * @copyright 2017-2019 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @author Alex Glover <alex.glover@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

namespace mod_assessment\form;

use html_writer;
use mod_assessment\helper\role;
use mod_assessment\model\rule;
use mod_assessment\model\ruleset;
use mod_assessment\model\version;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

class rules extends \moodleform
{
    /** @var version */
    protected version $version;

    /** @var int */
    protected int $role;

    protected function definition()
    {
        $mform = $this->_form;
        $this->version = $this->_customdata['version'];

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'versionid');
        $mform->setType('versionid', PARAM_INT);

        $this->role = $this->_customdata['role'];
        $mform->addElement('hidden', 'role', $this->role);
        $mform->setType('role', PARAM_INT);
        $rolename = role::get_string($this->role);

        $freezefields = [];

        if ($this->role == role::EVALUATOR) {
            $mform->addElement('advcheckbox', 'singleevaluator', get_string('label:singleevaluator', 'assessment'));
            $default = get_config('assessment', 'defaultsingleevaluator');
            $mform->setDefault('singleevaluator', $default);
            $freezefields[] = 'singleevaluator';
        } elseif ($this->role == role::REVIEWER) {
            $mform->addElement('advcheckbox', 'singlereviewer', get_string('label:singlereviewer', 'assessment'));
            $default = get_config('assessment', 'defaultsinglereviewer');
            $mform->setDefault('singlereviewer', $default);
            $freezefields[] = 'singlereviewer';
        }

        $operators = [];
        $operators[] = $mform->createElement(
            'radio',
            'operator',
            '',
            get_string('label:operatorand', 'assessment', $rolename),
            rule::OP_AND
        );
        $operators[] = $mform->createElement(
            'radio',
            'operator',
            '',
            get_string('label:operatoror', 'assessment', $rolename),
            rule::OP_OR
        );
        $mform->addGroup($operators, 'operator', get_string('label:operator', 'assessment', $rolename), html_writer::empty_tag('br'), false);
        $mform->addHelpButton('operator', 'label:operator', 'assessment');
        $mform->setDefault('operator', rule::OP_AND);
        $mform->setType('operator', PARAM_INT);
        $freezefields[] = 'operator';

        $rulesets = ruleset::instances_from_version($this->version, $this->role);
        $rulesetcount = 0;
        foreach ($rulesets as $ruleset) {
            $rulesetcount++;
            $mform->addElement('header', 'ruleset' . $rulesetcount, get_string('rulesetx', 'assessment', $rulesetcount));
            $mform->setExpanded('ruleset' . $rulesetcount);

            $rulesetfieldid = 'rulesetoperator[' . $ruleset->id . ']';
            $freezefields[] = $rulesetfieldid;

            $operators = [];
            $operators[] = $mform->createElement(
                'radio',
                $rulesetfieldid,
                '',
                get_string('label:operatorand', 'assessment', $rolename),
                rule::OP_AND
            );
            $operators[] = $mform->createElement(
                'radio',
                $rulesetfieldid,
                '',
                get_string('label:operatoror', 'assessment', $rolename),
                rule::OP_OR
            );
            $mform->addGroup(
                $operators,
                $rulesetfieldid,
                get_string('label:operator', 'assessment', $rolename),
                html_writer::empty_tag('br'),
                false
            );
            $mform->setDefault($rulesetfieldid, rule::OP_AND);
            $mform->setType($rulesetfieldid, PARAM_INT);

            $rules = rule::instances(['rulesetid' => $ruleset->id]);
            $mform->addElement('html', html_writer::start_tag('ul', ['class' => 'cohort-editing_ruleset unlist fitem']));
            foreach ($rules as $rule) {
                $rule->add_to_form($this);
            }
            $mform->addElement('html', html_writer::end_tag('ul'));

            if ($this->version->is_draft()) {
                $mform->addElement(
                    'select',
                    '',
                    get_string('addrule', 'totara_cohort'),
                    $this->get_rule_menu(),
                    ['class' => 'ruleselector', 'data-rulesetid' => $ruleset->id]
                );
            }
        }

        if (!$this->version->is_draft()) {
            // Disable everything!
            $mform->freeze($freezefields);
        } else {
            // The menu to add a new ruleset.
            $mform->addElement('header', 'addruleset', get_string('addruleset', 'totara_cohort'));
            $mform->addElement(
                'select',
                'addrulesetmenu',
                get_string('addrule', 'totara_cohort'),
                $this->get_rule_menu(),
                ['class' => 'ruleselector']
            );
            $mform->setDefault('addrulesetmenu', 'default');
        }
    }

    protected function get_rule_menu(): array
    {
        global $CFG;

        $menu = ['' => get_string('choose')];

        $rulefiles = scandir($CFG->dirroot . '/mod/assessment/classes/rule');
        foreach ($rulefiles as $rulefile) {
            if (!is_dir($rulefile) && preg_match('/^(.*?)\.php/', $rulefile, $matches)) {
                $rulename = str_replace('.php', '', $rulefile);
                $ruleclass = '\\mod_assessment\rule\\' . $rulename;
                $rule = new $ruleclass();
                $menu[$rulename] = $rule->get_name();
            }
        }

        return $menu;
    }

    public function get_version(): version
    {
        return $this->version;
    }
}
