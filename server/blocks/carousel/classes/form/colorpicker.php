<?php

/**
 * Color picker
 *
 * @package    block
 * @subpackage dashboard
 * @copyright  &copy; 2017 Kineo Pacific {@link http://kineo.com.au}
 * @author     tri.le
 * @version    1.0
 */

global $CFG;
require_once($CFG->libdir.'/form/text.php');

class MoodleQuickForm_colorpicker extends MoodleQuickForm_text {

    function __construct($elementName=null, $elementLabel=null, $attributes=null) {
        global $PAGE;
        $PAGE->requires->js('/blocks/carousel/js/minicolors/minicolors.js');
        $PAGE->requires->css('/blocks/carousel/js/minicolors/minicolors.css');

        parent::__construct($elementName, $elementLabel, $attributes);
    }

    public function toHtml() {
        global $PAGE;

        $this->_generateId();
        $PAGE->requires->js('/blocks/carousel/js/initcall.js');
        $PAGE->requires->js_init_call('M.kineo_carousel.init_color_picker', array($this->getAttribute('id')));
        return parent::toHtml();
    }

    public function getType() {
        return 'colorpicker';
    }
}

MoodleQuickForm::registerElementType('colorpicker', __FILE__, 'MoodleQuickForm_colorpicker');