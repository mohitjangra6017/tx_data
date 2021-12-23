<?php

/**
 * Form for editing block_carousel block instances.
 *
 * @package    block_carousel
 * @copyright  2019 Hoogesh Dawoodarry <hoogesh.dawoodarry@kineo.com.au>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use block_carousel\constants;
use block_carousel\helper;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/blocks/carousel/classes/form/colorpicker.php');
require_once($CFG->dirroot . '/blocks/carousel/lib.php');

class block_carousel_edit_form extends block_edit_form {

    /**
     * Extends the configuration form for block_carousel.
     */
    protected function specific_definition($mform) {
        global $DB, $CFG;

        // Section header title.
        $mform->addElement('header', 'configheader', get_string('blockinstancesetting', 'block_carousel'));
        
        $carouseltypes = constants::get_carouseltypes();
        
        // local_coachingsession
        if (!file_exists($CFG->dirroot.'/local/coachingsession/version.php')) {
            unset($carouseltypes[constants::BKC_EVENTS]);
        }

        // block_shoutout
        if (!file_exists($CFG->dirroot.'/blocks/shoutout/version.php')) {
            unset($carouseltypes[constants::BKC_SHOUTOUTS]);
        }

        // local_course_recommend
        if (!file_exists($CFG->dirroot.'/blocks/course_recommend/version.php')) {
            unset($carouseltypes[constants::BKC_RECOMMENDED]);
        }

        // block_whats_hot
        if (!file_exists($CFG->dirroot.'/blocks/whats_hot/version.php')) {
            unset($carouseltypes[constants::BKC_WHATS_HOT]);
        }

        // block_enrol_accordion
        if (!file_exists($CFG->dirroot.'/blocks/enrol_accordion/version.php')) {
            unset($carouseltypes[constants::BKC_TODOLIST]);
        }
                
        // mod_facetoface
        if (!file_exists($CFG->dirroot.'/mod/facetoface/version.php')) {
            unset($carouseltypes[constants::BKC_FACETOFACE]);
        }

        // block_whatshappening
        if (!file_exists($CFG->dirroot.'/blocks/event_feed/version.php')) {
            unset($carouseltypes[constants::BKC_WHATS_HAPPENING]);
        }

        // Slides type
        $mform->addElement('select', 'config_carouseltype', get_string('carouseltype','block_carousel'), $carouseltypes);
        
        $mform->addElement('advcheckbox', 'config_showdesc', get_string('showdesc', 'block_carousel'));
        
        $mform->addElement('textarea', 'config_description', get_string('description', 'block_carousel'));
        $mform->hideIf('config_description', 'config_showdesc', 'notchecked');
        
        // Filtered enrolled settings
        \block_carousel\carouseltype\filteredenrolledlist::extend_form($mform, $this->block->instance);
        
        // Card size
        $mform->addElement('select', 'config_cardsize', get_string('cardsize','block_carousel'), constants::get_cardsizes());
        
        //To do list settings 
        \block_carousel\carouseltype\todolist::extend_form($mform, $this->block->instance);
        
        // What's hot settings
        \block_carousel\carouseltype\whatshot::extend_form($mform, $this->block->instance);
        
        // What's new settings
        \block_carousel\carouseltype\whatsnew::extend_form($mform, $this->block->instance);

        // FacetoFace's settings
        \block_carousel\carouseltype\facetoface::extend_form($mform, $this->block->instance);
        
        // What's happening settings
        \block_carousel\carouseltype\whatshappening::extend_form($mform, $this->block->instance);

        // Course specific carousel settings
        $this->add_course_settings($mform);
        
        // user custom fields
        $this->add_user_settings($mform);
        
        // Curated content settings
        \block_carousel\carouseltype\curated::extend_form($mform, $this->block->instance);

        // Shoutout settings
        \block_carousel\carouseltype\shoutouts::extend_form($mform, $this->block->instance);

        // Programs settings
        \block_carousel\carouseltype\programs::extend_form($mform, $this->block->instance);
        
        // Block styles
        $this->add_styles($mform);
    }

    public function get_data() {
        return parent::get_data();
    }
    
    /**
     * 
     * @param array $data
     * @param array $files
     */
    public function validation($data, $files) {
        global $CFG;
        
        // First up ask the form to do the basic stuff.
        $errors = parent::validation($data, $files);
        
        // PERHAS-95
        // nothing much to validate
        // using this as a hack to purge the 
        // custom css cache 
        // otherwise the style changes won't reflect
        $custom_styles = new \block_carousel\custom_styles($this->block->instance->id);
        $custom_styles->delete_block_styles();
        
        return $errors;
    }

    private function add_styles(MoodleQuickForm $mform)
    {
        // Styles panel
        $mform->addElement('header', 'configstylepanel', get_string('stylepanel', 'block_carousel'));

        // header text colour
        $mform->addElement(
            'colorpicker',
            'config_style_headertextcolour',
            get_string('headertextcolour', 'block_carousel')
        );
        $mform->setType('config_style_headertextcolour', PARAM_TEXT);

        $mform->addElement('text', 'config_style_headerfontsize', get_string('headerfontsize', 'block_carousel'));
        $mform->setType('config_style_headerfontsize', PARAM_TEXT);

        $mform->addElement('text', 'config_style_headerfontweight', get_string('headerfontweight', 'block_carousel'));
        $mform->setType('config_style_headerfontweight', PARAM_TEXT);

        // Text colours
        // Tile
        $mform->addElement(
            'colorpicker',
            'config_style_tiletextcolour',
            get_string('tiletextcolour', 'block_carousel')
        );
        $mform->setType('config_style_tiletextcolour', PARAM_TEXT);

        // Details
        $mform->addElement(
            'colorpicker',
            'config_style_detailstextcolour',
            get_string('detailstextcolour', 'block_carousel')
        );
        $mform->setType('config_style_detailstextcolour', PARAM_TEXT);

        // Tags
        $mform->addElement('colorpicker', 'config_style_tagtextcolour', get_string('tagtextcolour', 'block_carousel'));
        $mform->setType('config_style_tagtextcolour', PARAM_TEXT);

        $mform->addElement('colorpicker', 'config_style_tagbgcolour', get_string('tagbgcolour', 'block_carousel'));
        $mform->setType('config_style_tagbg', PARAM_TEXT);

        $mform->addElement(
            'colorpicker',
            'config_style_tagbordercolour',
            get_string('tagbordercolour', 'block_carousel')
        );
        $mform->setType('config_style_tagbordercolour', PARAM_TEXT);

        // PERHAS-95 added a panel to group the styles
        $mform->addElement('colorpicker', 'config_style_progressbarbg', get_string('progressbarbg', 'block_carousel'));
        $mform->setType('config_style_progressbarbg', PARAM_TEXT);
        $mform->hideIf('config_style_progressbarbg', 'config_carouseltype', 'neq', constants::BKC_FILTERED_ENROLLED_LIST);

        $mform->addElement(
            'colorpicker',
            'config_style_progressbarcolour',
            get_string('progressbarcolour', 'block_carousel')
        );
        $mform->setType('config_style_progressbarcolour', PARAM_TEXT);
        $mform->hideIf('config_style_progressbarcolour', 'config_carouseltype', 'neq', constants::BKC_FILTERED_ENROLLED_LIST);
    }

    private function add_course_settings(MoodleQuickForm $mform)
    {
        global $PAGE;

        $mform->addElement(
            'advcheckbox',
            'config_hidecompletedcourses',
            get_string('hidecompletedcourses', 'block_carousel')
        );
        $mform->hideIf('config_hidecompletedcourses', 'config_carouseltype', 'eq', constants::BKC_EVENTS);
        $mform->hideIf('config_hidecompletedcourses', 'config_carouseltype', 'eq', constants::BKC_SHOUTOUTS);
        $mform->hideIf('config_hidecompletedcourses', 'config_carouseltype', 'eq', constants::BKC_FILTERED_ENROLLED_LIST);
        $mform->hideIf('config_hidecompletedcourses', 'config_carouseltype', 'eq', constants::BKC_TODOLIST);
        $mform->hideIf('config_hidecompletedcourses', 'config_carouseltype', 'eq', constants::BKC_WHATS_HAPPENING);
        $mform->hideIf('config_hidecompletedcourses', 'config_carouseltype', 'eq', constants::BKC_FACETOFACE);
        $mform->hideIf('config_hidecompletedcourses', 'config_carouseltype', 'eq', constants::BKC_PROGRAMS);

        $mform->addElement('text', 'config_coursecustomfields', get_string('coursecustomfields', 'block_carousel'));
        $mform->addHelpButton('config_coursecustomfields', 'coursecustomfields', 'block_carousel');
        $mform->setType('config_coursecustomfields', PARAM_TEXT);
        $mform->hideIf('config_coursecustomfields', 'config_carouseltype', 'eq', constants::BKC_EVENTS);
        $mform->hideIf('config_coursecustomfields', 'config_carouseltype', 'eq', constants::BKC_SHOUTOUTS);
        $mform->hideIf('config_coursecustomfields', 'config_carouseltype', 'eq', constants::BKC_FILTERED_ENROLLED_LIST);
        $mform->hideIf('config_coursecustomfields', 'config_carouseltype', 'eq', constants::BKC_PROGRAMS);

        $mform->addElement(
            'text',
            'config_coursecustomfieldsdetails',
            get_string('coursecustomfieldsdetails', 'block_carousel')
        );
        $mform->addHelpButton('config_coursecustomfieldsdetails', 'coursecustomfieldsdetails', 'block_carousel');
        $mform->setType('config_coursecustomfieldsdetails', PARAM_TEXT);
        $mform->hideIf('config_coursecustomfieldsdetails', 'config_carouseltype', 'eq', constants::BKC_EVENTS);
        $mform->hideIf('config_coursecustomfieldsdetails', 'config_carouseltype', 'eq', constants::BKC_SHOUTOUTS);
        $mform->hideIf('config_coursecustomfieldsdetails', 'config_carouseltype', 'eq', constants::BKC_FILTERED_ENROLLED_LIST);
        $mform->hideIf('config_coursecustomfieldsdetails', 'config_carouseltype', 'eq', constants::BKC_PROGRAMS);

        $mform->addElement(
            'select',
            'config_counttype',
            get_string('counttype', 'block_carousel'),
            constants::get_counttypes()
        );
        $mform->hideIf('config_counttype', 'config_carouseltype', 'eq', constants::BKC_EVENTS);
        $mform->hideIf('config_counttype', 'config_carouseltype', 'eq', constants::BKC_SHOUTOUTS);
        $mform->hideIf('config_counttype', 'config_carouseltype', 'eq', constants::BKC_FILTERED_ENROLLED_LIST);
        $mform->hideIf('config_counttype', 'config_carouseltype', 'eq', constants::BKC_WHATS_HAPPENING);
        $mform->hideIf('config_counttype', 'config_carouseltype', 'eq', constants::BKC_PROGRAMS);

        $mform->addElement(
            'button',
            'addcohort',
            get_string('addcohort', 'block_carousel'),
            ['id' => 'btn-cohort-config-trigger']
        );
        $mform->hideIf('addcohort', 'addcohort', 'eq', constants::BKC_PROGRAMS);

        $cohorts = helper::get_carousel_cohorts($this->block->instance->id);
        if (!empty($cohorts)) {
            $data = new stdClass();
            foreach ($cohorts as $cohort) {
                $data->cohorts[] = $cohort;
            }

            $renderer = $PAGE->get_renderer('block_carousel');
            $existing_cohorts = $renderer->get_existing_cohorts($data);

            $mform->addElement(
                'static',
                'description',
                get_string('existing_cohorts', 'block_carousel'),
                $existing_cohorts
            );
        }

        $PAGE->requires->js_call_amd('block_carousel/cohort', 'init', [$this->block->instance->id]);
    }

    private function add_user_settings(MoodleQuickForm $mform)
    {
        $mform->addElement('text', 'config_usercustomfields', get_string('usercustomfields', 'block_carousel'));
        $mform->addHelpButton('config_usercustomfields', 'usercustomfields', 'block_carousel');
        $mform->setType('config_usercustomfields', PARAM_TEXT);
        $mform->hideIf('config_usercustomfields', 'config_carouseltype', 'eq', constants::BKC_JUMPBACKIN);
        $mform->hideIf('config_usercustomfields', 'config_carouseltype', 'eq', constants::BKC_RECOMMENDED);
        $mform->hideIf('config_usercustomfields', 'config_carouseltype', 'eq', constants::BKC_FILTERED_ENROLLED_LIST);
        $mform->hideIf('config_usercustomfields', 'config_carouseltype', 'eq', constants::BKC_TODOLIST);
        $mform->hideIf('config_usercustomfields', 'config_carouseltype', 'eq', constants::BKC_FACETOFACE);
        $mform->hideIf('config_usercustomfields', 'config_carouseltype', 'eq', constants::BKC_CLUSTER);
        $mform->hideIf('config_usercustomfields', 'config_carouseltype', 'eq', constants::BKC_CURATED);
        $mform->hideIf('config_usercustomfields', 'config_carouseltype', 'eq', constants::BKC_WHATS_NEW);
        $mform->hideIf('config_usercustomfields', 'config_carouseltype', 'eq', constants::BKC_WHATS_HOT);
        $mform->hideIf('config_usercustomfields', 'config_carouseltype', 'eq', constants::BKC_COURSELETS);

        $mform->addElement(
            'text',
            'config_usercustomfieldsdetails',
            get_string('usercustomfieldsdetails', 'block_carousel')
        );
        $mform->addHelpButton('config_usercustomfieldsdetails', 'usercustomfieldsdetails', 'block_carousel');
        $mform->setType('config_usercustomfieldsdetails', PARAM_TEXT);
        $mform->hideIf('config_usercustomfieldsdetails', 'config_carouseltype', 'eq', constants::BKC_JUMPBACKIN);
        $mform->hideIf('config_usercustomfieldsdetails', 'config_carouseltype', 'eq', constants::BKC_RECOMMENDED);
        $mform->hideIf('config_usercustomfieldsdetails', 'config_carouseltype', 'eq', constants::BKC_FILTERED_ENROLLED_LIST);
        $mform->hideIf('config_usercustomfieldsdetails', 'config_carouseltype', 'eq', constants::BKC_TODOLIST);
        $mform->hideIf('config_usercustomfieldsdetails', 'config_carouseltype', 'eq', constants::BKC_FACETOFACE);
        $mform->hideIf('config_usercustomfieldsdetails', 'config_carouseltype', 'eq', constants::BKC_CLUSTER);
        $mform->hideIf('config_usercustomfieldsdetails', 'config_carouseltype', 'eq', constants::BKC_CURATED);
        $mform->hideIf('config_usercustomfieldsdetails', 'config_carouseltype', 'eq', constants::BKC_WHATS_NEW);
        $mform->hideIf('config_usercustomfieldsdetails', 'config_carouseltype', 'eq', constants::BKC_WHATS_HOT);
        $mform->hideIf('config_usercustomfieldsdetails', 'config_carouseltype', 'eq', constants::BKC_COURSELETS);
    }

}
