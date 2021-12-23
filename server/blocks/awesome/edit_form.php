<?php

/**
 * Comment
 *
 * @package    block
 * @subpackage awesome
 * @copyright  &copy; 2017 CG Kineo {@link http://www.kineo.com}
 * @author     kaushtuv.gurung
 * @version    1.0
 */


defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/blocks/awesome/classes/form/colorpicker.php');

class block_awesome_edit_form extends block_edit_form {

    /**
     * @param MoodleQuickForm $mform
     * @throws coding_exception
     */
    protected function specific_definition($mform) {

        $mform->addElement('header', 'configheader', get_string('whattodisplay', 'block_awesome'));

        // templates
        $mform->addElement('select', 'config_template', get_string('template', 'block_awesome'), block_awesome::get_templates_select());
        $mform->setType('config_template', PARAM_TEXT);
        $mform->addHelpButton('config_template' , 'template', 'block_awesome');

        // block image
        $mform->addElement('filepicker', 'config_image', get_string('image','block_awesome'), null,
                   array('accepted_types' => 'image'));

        // hide image
        $mform->addElement('advcheckbox', 'config_hideimage', get_string('hideimage', 'block_awesome'));
        $mform->setType('config_hideimage', PARAM_TEXT);

        // force image to be 100%
        $mform->addElement('advcheckbox', 'config_responsiveimage', get_string('responsiveimage', 'block_awesome'));
        $mform->setType('config_responsiveimage', PARAM_TEXT);
        $mform->addHelpButton('config_responsiveimage' , 'responsiveimage', 'block_awesome');

        // link text
        $mform->addElement('text', 'config_linktext', get_string('linktext', 'block_awesome'));
        $mform->setType('config_linktext', PARAM_TEXT);

        // link url
        $mform->addElement('text', 'config_url', get_string('url', 'block_awesome'));
        $mform->setType('config_url', PARAM_TEXT);
        $mform->addHelpButton('config_url' , 'url', 'block_awesome');
        $mform->addElement('advcheckbox', 'config_urlcoveringblock', get_string('urlcoveringblock', 'block_awesome'));
        $mform->disabledIf('config_urlcoveringblock', 'config_url', 'eq', '');

        // open link in new tab
        $mform->addElement('advcheckbox', 'config_newtab', get_string('newtab', 'block_awesome'));
        $mform->setType('config_newtab', PARAM_TEXT);

        // header
        $mform->addElement('text', 'config_headertext', get_string('header', 'block_awesome'));
        $mform->setType('config_headertext', PARAM_TEXT);
        $mform->addHelpButton('config_headertext' , 'header', 'block_awesome');

        // sub header
        $mform->addElement('text', 'config_subheadertext', get_string('subheader', 'block_awesome'));
        $mform->setType('config_subheadertext', PARAM_TEXT);
        $mform->addHelpButton('config_subheadertext' , 'subheader', 'block_awesome');

        // sub header link url
        $mform->addElement('text', 'config_subheaderurl', get_string('subheaderurl', 'block_awesome'));
        $mform->setType('config_subheaderurl', PARAM_TEXT);
        $mform->addHelpButton('config_subheaderurl', 'subheaderurl', 'block_awesome');
        $mform->disabledIf('config_subheaderurl', 'config_urlcoveringblock', 'checked');

        // content
        $mform->addElement('textarea', 'config_contenttext', get_string('content', 'block_awesome'), array('rows' => 10, 'cols' => 80));
        $mform->setType('config_contenttext', PARAM_TEXT);
        $mform->addHelpButton('config_contenttext' , 'content', 'block_awesome');

        // fontawesome icon
        $mform->addElement('text', 'config_faicon', get_string('faicon', 'block_awesome'));
        $mform->setType('config_faicon', PARAM_TEXT);
        $mform->addHelpButton('config_faicon' , 'faicon', 'block_awesome');

        // large icon
        $mform->addElement('advcheckbox', 'config_largeicon', get_string('largeicon', 'block_awesome'));
        $mform->setType('config_largeicon', PARAM_TEXT);
        $mform->addHelpButton('config_largeicon' , 'largeicon', 'block_awesome');

        // ICAHAS-26 - configuration to remove the chevron from teh button
        $mform->addElement('advcheckbox', 'config_btnnochevron', get_string('btnnochevron', 'block_awesome'));
        $mform->setType('config_btnnochevron', PARAM_TEXT);

        // KINSST-76 added a panel to group the styles
        // Style panel
        $mform->addElement('header', 'configstylepanel', get_string('stylepanel', 'block_awesome'));

        // header text colour
        $mform->addElement('colorpicker','config_headertextcolour', get_string('headertextcolour', 'block_awesome'),array('placeholder' => '#333333'));
        $mform->setType('config_headertextcolour', PARAM_TEXT);
        $mform->addHelpButton('config_headertextcolour' , 'headertextcolour', 'block_awesome');

        // KINSST-84
        // header background colour
        $mform->addElement('colorpicker','config_headerbgcolour', get_string('headerbgcolour', 'block_awesome'),array('placeholder' => '#333333'));
        $mform->setType('config_headerbgcolour', PARAM_TEXT);
        $mform->addHelpButton('config_headerbgcolour' , 'headerbgcolour', 'block_awesome');


        // sub header text colour
        $mform->addElement('colorpicker','config_subheadertextcolour', get_string('subheadertextcolour', 'block_awesome'),array('placeholder' => '#333333'));
        $mform->setType('config_subheadertextcolour', PARAM_TEXT);
        $mform->addHelpButton('config_subheadertextcolour' , 'subheadertextcolour', 'block_awesome');

        // text colour
        $mform->addElement('colorpicker','config_textcolour', get_string('textcolour', 'block_awesome'),array('placeholder' => '#333333'));
        $mform->setType('config_textcolour', PARAM_TEXT);
        $mform->addHelpButton('config_textcolour' , 'textcolour', 'block_awesome');

        // link colour
        $mform->addElement('colorpicker','config_linkcolour', get_string('linkcolour', 'block_awesome'),array('placeholder' => '#333333'));
        $mform->setType('config_linkcolour', PARAM_TEXT);
        $mform->addHelpButton('config_linkcolour' , 'linkcolour', 'block_awesome');

        // icon colour
        $mform->addElement('colorpicker', 'config_iconcolour', get_string('iconcolour', 'block_awesome'), array('placeholder' => '#ffffff'));
        $mform->setType('config_iconcolour', PARAM_TEXT);
        $mform->addHelpButton('config_iconcolour' , 'iconcolour', 'block_awesome');

        // icon background colour
        $mform->addElement('colorpicker', 'config_iconbackgroundcolour', get_string('iconbackgroundcolour', 'block_awesome'), array('placeholder' => 'transparent'));
        $mform->setType('config_iconbackgroundcolour', PARAM_TEXT);
        $mform->addHelpButton('config_iconbackgroundcolour' , 'iconbackgroundcolour', 'block_awesome');

        // background colour
        $mform->addElement('colorpicker','config_backgroundcolour', get_string('backgroundcolour', 'block_awesome'),array('placeholder' => '#333333'));
        $mform->setType('config_backgroundcolour', PARAM_TEXT);
        $mform->addHelpButton('config_backgroundcolour' , 'backgroundcolour', 'block_awesome');

        // KINSST-76
        // Font styles
        $mform->addElement('text','config_headerfontsize', get_string('headerfontsize', 'block_awesome'),array('placeholder' => '30px'));
        $mform->setType('config_headerfontsize', PARAM_TEXT);

        $mform->addElement('text','config_subheaderfontsize', get_string('subheaderfontsize', 'block_awesome'),array('placeholder' => '20px'));
        $mform->setType('config_subheaderfontsize', PARAM_TEXT);

        $mform->addElement('text','config_contentfontsize', get_string('contentfontsize', 'block_awesome'),array('placeholder' => '14px'));
        $mform->setType('config_contentfontsize', PARAM_TEXT);

        $mform->addElement('text','config_linkfontsize', get_string('linkfontsize', 'block_awesome'),array('placeholder' => '14px'));
        $mform->setType('config_linkfontsize', PARAM_TEXT);

        $mform->addElement('text','config_headerfontweight', get_string('headerfontweight', 'block_awesome'));
        $mform->setType('config_headerfontweight', PARAM_TEXT);

        $mform->addElement('text','config_subheaderfontweight', get_string('subheaderfontweight', 'block_awesome'));
        $mform->setType('config_subheaderfontweight', PARAM_TEXT);

        $mform->addElement('text','config_contentfontweight', get_string('contentfontweight', 'block_awesome'));
        $mform->setType('config_contentfontweight', PARAM_TEXT);

        $mform->addElement('text','config_linkfontweight', get_string('linkfontweight', 'block_awesome'));
        $mform->setType('config_linkfontweight', PARAM_TEXT);

        // PETHAS-41
        // Main header alignment
        $mform->addElement('select', 'config_headertextalign', get_string('header_textalign', 'block_awesome'), block_awesome::get_text_align_options());

        $mform->addElement('select', 'config_subheadertextalign', get_string('subheader_textalign', 'block_awesome'), block_awesome::get_text_align_options());

        $mform->addElement('select', 'config_contenttextalign', get_string('content_textalign', 'block_awesome'), block_awesome::get_text_align_options());
        
        // IHCHAS-220
        $mform->addElement('text','config_blockborderraidus', get_string('blockborderraidus', 'block_awesome'));
        $mform->setType('config_blockborderraidus', PARAM_TEXT);
        
        $mform->addElement('colorpicker','config_blockbordercolor', get_string('blockbordercolor', 'block_awesome'));
        $mform->setType('config_blockbordercolor', PARAM_TEXT);
        
        $mform->addElement('select', 'config_buttonalign', get_string('buttonalign', 'block_awesome'), block_awesome::get_align_options());
    }

    /**
     * @return object|null
     */
    public function get_data() {
        return parent::get_data();
    }

    /**
     * @param stdClass $defaults
     */
    public function set_data($defaults) {
        $draftitemid = file_get_submitted_draft_itemid('config_image');

        file_prepare_draft_area($draftitemid, $this->block->context->id, 'block_awesome', 'image', 0,
                    array('subdirs'=>true));
        if(isset($this->block->config->image)) {
            $this->block->config->image = $draftitemid;
        }

        parent::set_data($defaults);
        if (($data = parent::get_data())) {
            file_save_draft_area_files($data->config_image, $this->block->context->id, 'block_awesome', 'image', 0,
                array('subdirs' => true));
        }
    }
}
