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

class block_awesome extends block_base {

    const TOTAL_TEMPLATES = 5;
    const DEFAULT_TEMPLATE = 'template_1';
    const DEFAULT_BG = '#333333';
    const DEFAULT_LINK_TEXT = 'Read more';
    const DEFAULT_ICONCOLOUR = '#333333';
    const DEFAULT_ICONBG = '#ffffff';
    const DEFAULT_TEXTCOLOUR = '#ffffff';

    /**
     * @throws coding_exception
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_awesome');
    }

    /**
     * @return bool
     */
    public function has_config() {
        return true;
    }

    /**
     * @return bool
     */
    public function hide_header() {
        return true;
    }

    /**
     * @return bool
     */
    public function instance_allow_multiple() {
        return true;
    }

    /**
     * @return array|bool[]
     */
    public function applicable_formats() {
        return array('all' => true);
    }

    public function specialization() {
        if(!$this->config) {
            $this->config = new stdClass();
        }
        $global = self::get_global_configs();

        foreach($global as $key => $val) {
            if(empty($this->config->$key)) {
                $this->config->$key = $val;
            }
        }
    }

    public function get_required_javascript() {
        parent::get_required_javascript();
        if (!empty($this->instance->id)) {
            $this->page->requires->js_init_call('M.block_awesome.init_block', array($this->instance, $this->config));
        }
    }

    /**
     * @return array|string[]
     */
    public function html_attributes() {
        $attributes = parent::html_attributes();
        if(!empty($this->config->template)) {
            $attributes['class'] .= ' '.$this->config->template;
        }
        return $attributes;
    }

    /**
     * @return stdClass|stdObject|null
     * @throws coding_exception
     */
    public function get_content() {
        global $CFG, $PAGE;
        require_once($CFG->dirroot. '/blocks/awesome/classes/output/renderer.php');
        // Uncomment for template dev to quick load css

        $renderer = $PAGE->get_renderer('block_awesome');

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->text = $renderer->render_block($this->config, $this->get_blockimage());
        $this->content->footer = '';
        if($PAGE->user_is_editing()) {
            $this->content->footer = html_writer::tag('button',
                    get_string('clone', 'block_awesome') . ' '.
                    html_writer::tag('i', null, array('class' => 'far fa-clone')),
                    array('class' => 'duplicate-awesome-block'));
        }

        return $this->content;
    }

    /**
     * There should be only one banner image per block instance
     *
     *
     * @return moodle_url or null
     * @return moodle_url|string|null
     * @throws coding_exception
     */
    public function get_blockimage() {
        global $OUTPUT;

        $fs = get_file_storage();
        $files = $fs->get_area_files($this->context->id, 'block_awesome', 'image', false, "itemid, filepath, filename", false);
        $image = '';
        foreach ($files as $file) { // Since there is only one real image file anyway
            $image = moodle_url::make_pluginfile_url(
                $file->get_contextid(),
                $file->get_component(),
                $file->get_filearea(),
                $file->get_itemid(),
                $file->get_filepath(),
                $file->get_filename()
            );
        }
        if(!$image) {
            $image = $OUTPUT->image_url('template','block_awesome');
        }
        if(empty($this->config->hideimage)) {
            return $image;
        }
        return null;
    }

    /**
     * @return mixed
     * @throws coding_exception
     */
    static function get_templates_select() {
        $templates[0] = get_string('selectone', 'block_awesome');
        $total_templates = self::TOTAL_TEMPLATES;
        for($i = 1; $i <= $total_templates; $i++) {
            $templates['template_'.$i] = get_string('templateno', 'block_awesome', $i);
        }
        return $templates;
    }

    /**
     * @return array
     */
    static function get_global_configs() {
        return array(
            'template' => !empty($config = get_config('block_awesome','global_defaulttemplate')) ? $config : self::DEFAULT_TEMPLATE,
            'backgroundcolour' => !empty($config = get_config('block_awesome','global_defaultbackgroundcolour')) ? $config : self::DEFAULT_BG,
            'linktext' => !empty($config = get_config('block_awesome','global_defaultlinktext')) ? $config : self::DEFAULT_LINK_TEXT,
            'iconcolour' => !empty($config = get_config('block_awesome','global_defaulticoncolour')) ? $config : self::DEFAULT_ICONCOLOUR,
            'iconbackgroundcolour' => !empty($config = get_config('block_awesome','global_defaulticonbackgroundcolour')) ? $config : self::DEFAULT_ICONBG,
            'textcolour' => !empty($config = get_config('block_awesome','global_defaulttextcolour')) ? $config : self::DEFAULT_TEXTCOLOUR,
            'headertextcolour' => !empty($config = get_config('block_awesome','global_defaultheadertextcolour')) ? $config : self::DEFAULT_TEXTCOLOUR
        );
    }

    /**
     * @return array
     * @throws coding_exception
     */
    static function get_text_align_options() {
        return array (
            'left' => get_string('left', 'block_awesome'),
            'center' => get_string('center', 'block_awesome'),
            'right' => get_string('right', 'block_awesome')
        );
    }

    /**
     * IHCHAS-220
     * @return array
     * @throws coding_exception
     */
    static function get_align_options() {
        return array (
            'left' => get_string('left', 'block_awesome'),
            'center' => get_string('center', 'block_awesome'),
            'right' => get_string('right', 'block_awesome')
        );
    }

    /**
     * KINSST-76
     * Add in-line styles
     * @param $config
     * @return string[]
     */
    static function inline_styles($config) {
        $block = $caption = $header = $subheader = $content = $link = $button = 'style="';

        // IHCHAS-220
        $border_radius = !empty($config->blockborderraidus) ? $config->blockborderraidus : '0px';

        $btn_align = !empty($config->buttonalign) ? $config->buttonalign : null;
        switch ($btn_align) {
            case 'left':
                $button .= 'left: 25px; right: auto;';
                break;
            
            case 'center':
                $button .= 'transform: translate(-50%, -50%);left: 50%;';
                break;

            case 'right':
                $button .= 'left: auto; right: 25px;';
                break;

            default:
                $button = '';
                break;
        }
        if(!empty($button)) {
            $button .= '"';
        }

        // caption
        $caption .= 'background-color: '. (!empty($config->backgroundcolour) ? $config->backgroundcolour : null) . ';';
        switch($config->template) {
            case 'template_1':
                // block
                $block .= 'border-bottom-left-radius: '. $border_radius.';';
                $block .= 'border-bottom-right-radius: '. $border_radius.';';
                // caption
                $caption .= 'border-top-left-radius: '. $border_radius.';';
                $caption .= 'border-top-right-radius: '. $border_radius.';';
                $caption .= 'border-color: '. (!empty($config->blockbordercolor) ? $config->blockbordercolor : null) .';';
                break;
            case 'template_3':
                $block .= 'border-radius: '. $border_radius.';';
                break;
             case 'template_5':
                // block
                $block .= 'border-top-left-radius: '. $border_radius.';';
                $block .= 'border-top-right-radius: '. $border_radius.';';
                // caption
                $caption .= 'border-color: ' . (!empty($config->headerbgcolour) ? $config->headerbgcolour : null) .';';
                $caption .= 'border-bottom-left-radius: '. $border_radius.';';
                $caption .= 'border-bottom-right-radius: '. $border_radius.';';
                break;
            default:
                // block
                $block .= 'border-top-left-radius: '. $border_radius.';';
                $block .= 'border-top-right-radius: '. $border_radius.';';
                // caption
                $caption .= 'border-bottom-left-radius: '. $border_radius.';';
                $caption .= 'border-bottom-right-radius: '. $border_radius.';';
                $caption .= 'border-color: '. (!empty($config->blockbordercolor) ? $config->blockbordercolor : null) .';';
                break;
        }
        // close block
        $block .= '"';
        // close caption
        $caption .= '"';

        // header
        $header .= 'color: '. (!empty($config->headertextcolour) ? $config->headertextcolour : null) .';';
        $header .= 'font-size: '. (!empty($config->headerfontsize) ? $config->headerfontsize : null) .';';
        $header .= 'font-weight: '. (!empty($config->headerfontweight) ? $config->headerfontweight : null) .';';
        $header .= 'background-color: '. (!empty($config->headerbgcolour) ? $config->headerbgcolour : null) .';';
        $header .= 'text-align: ' .(!empty($config->headertextalign) ? $config->headertextalign : 'left' ).';';
        $header .= '"';

        $subheader .= 'color: '. (!empty($config->subheadertextcolour) ? $config->subheadertextcolour : null) .';';
        $subheader .= 'font-size: '. (!empty($config->subheaderfontsize) ? $config->subheaderfontsize : null) .';';
        $subheader .= 'font-weight: '. (!empty($config->subheaderfontweight) ? $config->subheaderfontweight : null) .';';
        $subheader .= 'text-align: ' .(!empty($config->subheadertextalign) ? $config->subheadertextalign : 'left' ).';';
        $subheader .= '"';

        $content .= 'color: '. (!empty($config->textcolour) ? $config->textcolour : null) .';';
        $content .= 'font-size: '. (!empty($config->contentfontsize) ? $config->contentfontsize : null) .';';
        $content .= 'font-weight: '. (!empty($config->contentfontweight) ? $config->contentfontweight : null) .';';
        $content .= 'text-align: ' .(!empty($config->contenttextalign) ? $config->contenttextalign : 'left' ).';';
        $content .= '"';

        $link .= 'color: '. (!empty($config->linkcolour) ? $config->linkcolour : null) .';';
        $link .= 'font-size: '. (!empty($config->linkfontsize) ? $config->linkfontsize : null) .';';
        $link .= 'font-weight: '. (!empty($config->linkfontweight) ? $config->linkfontweight : null) .';';
        $link .= '"';

        return array(
            'block_styles' => $block,
            'caption_styles' => $caption,
            'header_styles' => $header,
            'subheader_styles' => $subheader,
            'content_styles' => $content,
            'link_styles' => $link,
            'btn_styles' => $button
        );
    }
}
