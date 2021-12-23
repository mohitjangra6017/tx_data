<?php

/**
 * Comment
 *
 * @package    block
 * @subpackage block_course_recommendations
 * @copyright  &copy; 2017 CG Kineo {@link http://www.kineo.com}
 * @author     kaushtuv.gurung
 * @version    1.0
 */

class block_course_recommendations_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
        // Fields for editing block contents.
        $mform->addElement('header', 'configheader', get_string('whattodisplay', 'block_course_recommendations'));

        $mform->addElement('text', 'config_displayname', get_string('displayname', 'block_course_recommendations'));
        $mform->setType('config_displayname', PARAM_TEXT);
        $mform->addHelpButton('config_displayname' , 'displayname', 'block_course_recommendations');
        
        $mform->addElement('textarea', 'config_blockinfo', get_string('blockinfo', 'block_course_recommendations'));
        $mform->setType('config_blockinfo', PARAM_TEXT);
        $mform->addHelpButton('config_blockinfo' , 'blockinfo', 'block_course_recommendations');
    }
    
    public function get_data() {
        return parent::get_data();
    }
    
    public function set_data($defaults) {
        parent::set_data($defaults);
    }
}
