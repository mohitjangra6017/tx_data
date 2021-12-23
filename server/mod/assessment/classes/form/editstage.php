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

use mod_assessment\model\stage;
use mod_assessment\model\version;
use mod_assessment\model\version_stage;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

class editstage extends \moodleform
{
    /** @var stage $stage */
    protected stage $stage;

    protected function definition()
    {
        $mform = $this->_form;
        $this->stage = $this->_customdata['stage'];

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'versionid');
        $mform->setType('versionid', PARAM_INT);

        $mform->addElement('text', 'name', get_string('stagename', 'assessment'), ['maxlength' => 255]);
        $mform->addRule('name', get_string('required'), 'required');
        $mform->setType('name', PARAM_TEXT);

        $mform->addElement('select', 'locked', get_string('stagelockoptions', 'assessment'), $this->get_lock_menu());

        $mform->addElement('header', 'layouthead', get_string('layout', 'assessment'));

        $mform->addElement('select', 'newpage', get_string('newpage', 'assessment'), $this->get_newpage_menu());

        $this->add_action_buttons();
    }

    public function validation($data, $files): array
    {
        $errors = parent::validation($data, $files);

        if (!$this->validate_lock($data)) {
            $errors['locked'] = get_string('error_stagelockonfirststage', 'assessment');
        }

        return $errors;
    }

    protected function get_lock_menu(): array
    {
        return [
            get_string('lock:unlocked', 'assessment'),
            get_string('lock:previousstagecompleted', 'assessment')
        ];
    }

    protected function get_newpage_menu(): array
    {
        $menu = [];
        $menu[0] = get_string('pageqall', 'assessment');
        $menu[1] = get_string('pageq1', 'assessment');

        for ($i = 2; $i <= 50; $i++) {
            $menu[$i] = get_string('pageqx', 'assessment', $i);
        }

        return $menu;
    }

    protected function validate_lock($data): bool
    {
        if (!$data['locked']) {
            return true;
        }

        if ($data['id']) {
            $vstage = version_stage::instance(['stageid' => $data['id'], 'versionid' => $data['versionid']]);
            return $vstage->get_sortorder() > 1;
        }

        return version_stage::get_next_sortorder($data['versionid']) > 1;
    }
}
