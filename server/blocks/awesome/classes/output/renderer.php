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
namespace block_awesome\output;

use moodle_exception;

defined('MOODLE_INTERNAL') || die();

class renderer extends \plugin_renderer_base {

    /**
     * @param $data
     * @param $image
     * @return bool|string
     * @throws moodle_exception
     */
    public function render_block($data, $image) {
        if(!$data) {
            $global_config = \block_awesome::get_global_configs();
            $data = new \stdClass();
            $data->template = $global_config['template'];
            $data->backgroundcolour = $global_config['backgroundcolour'];
            $data->linktext = $global_config['linktext'];           
        }

        // Support for translations
        $data->linktext = format_string($data->linktext);

        $data->additionalclass = '';
        // if the block has fontawesome icon
        if(!empty($data->faicon)) {
            $data->faicon = 'fa-'.$data->faicon;
            $data->additionalclass .= 'has-icon ';
        }
        // if the block has a responsive image
        // only required by template 4 as of 06-08-2017
        // update this if change is needed
        if(!empty($data->responsiveimage)) {
            $data->additionalclass .= 'has-responsive-image ';
        }
        // if a larger icon is to be used
        if(!empty($data->largeicon)) {
            $data->additionalclass .= 'has-larger-icon ';
        }
        // ICAHAS-26
        // if button has no chevron
        if(!empty($data->btnnochevron)) {
            $data->additionalclass .= 'no-chevron ';
        }
        
        $data->image = $image;
        
        // change newline to html page break br
        if(!empty($data->contenttext)) {
            $data->contenttext = nl2br($data->contenttext);
        } 

        // KINSST-76
        // build inline css
        $inline_styles = \block_awesome::inline_styles($data);
        foreach ($inline_styles as $key => $val) {
            $data->$key = $val;
        }

        $templateName = $data->template;
        if (!empty($data->url) && !empty($data->urlcoveringblock)) {
            $templateName .= '_linked';
        }
        
        return $this->render_from_template('block_awesome/'.$templateName, $data);
    }
}
