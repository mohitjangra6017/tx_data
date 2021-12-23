<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

namespace mod_assessment\form;

use core_component;
use mod_assessment\model\version;
use moodleform;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

class choosequestion extends moodleform
{

    protected function definition()
    {
        $mform = $this->_form;
        $version = $this->_customdata['version'];

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'versionid');
        $mform->setType('versionid', PARAM_INT);

        $mform->addElement('hidden', 'stageid');
        $mform->setType('stageid', PARAM_INT);

        $options = self::get_questions_menu($version);

        $group = [];
        $group[] = $mform->createElement('selectgroups', 'datatype', '', $options, null, true);
        $group[] = $mform->createElement('submit', 'submitbutton', get_string('add', 'assessment'));
        $mform->addGroup($group, 'addquestgroup', '', null, false);
    }

    public static function get_questions_menu(version $version): array
    {
        $options = [];
        $components = core_component::get_plugin_list('assquestion');
        foreach ($components as $qclassname => $component) {
            $qclassname = "\\mod_assessment\\question\\" . $qclassname . "\\model\\question";
            $qclass = new $qclassname();

            if ($qclass->is_question()) {
                if (!$version->is_draft()) {
                    continue;
                }
                $options[get_string('question', 'assessment')][$qclass->get_type()] = $qclass->get_displayname();
            } else {
                $options[get_string('nonquestion', 'assessment')][$qclass->get_type()] = $qclass->get_displayname();
            }
        }

        return $options;
    }

    public function validation($data, $files): array
    {
        $errors = parent::validation($data, $files);
        if (!isset($data['datatype'])) {
            $errors['selectgroups'] = get_string('required');
        }
        return $errors;
    }

}
