<?php

/**
 * Comment
 *
 * @package    package
 * @subpackage sub_package
 * @copyright  &copy; 2019 CG Kineo {@link http://www.kineo.com}
 * @author     kaushtuv.gurung
 * @version    1.0
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir.'/form/text.php');

class MoodleQuickForm_colorpicker extends MoodleQuickForm_text {

    function __construct($elementName=null, $elementLabel=null, $attributes=null) {
        global $PAGE;
        $PAGE->requires->js('/blocks/awesome/jslib/minicolors/minicolors.js');
        $PAGE->requires->css('/blocks/awesome/jslib/minicolors/minicolors.css');

        parent::__construct($elementName, $elementLabel, $attributes);
    }

    public function toHtml() {
        global $PAGE;

        $this->_generateId();
        $PAGE->requires->js('/blocks/awesome/module.js');
        $PAGE->requires->js_init_call('M.block_awesome.init_color_picker', array($this->getAttribute('id')));
        return parent::toHtml();
    }

    public function getType() {
        return 'colorpicker';
    }
}

MoodleQuickForm::registerElementType('colorpicker', __FILE__, 'MoodleQuickForm_colorpicker');