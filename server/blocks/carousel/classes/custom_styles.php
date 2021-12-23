<?php

/**
 * Comment
 *
 * @package    package
 * @subpackage sub_package
 * @copyright  &copy; 2020 CG Kineo {@link http://www.kineo.com}
 * @author     kaushtuv.gurung
 * @version    1.0
 */

namespace block_carousel;

class custom_styles {
        
    public $block_id = null;
    
    public $cache_component = 'block_carousel';
    
    public $cache_area = 'block_carousel_custom_styles';
    
    public $cache_key = null;
    
    public $block_config = null;
    
    public function __construct($block_id, $block_config = null) {
        $this->block_id = $block_id;
        $this->cache_key = 'blockKineoCarouselStyle#'.$block_id;
        $this->block_config = $block_config;
    }
    
    /**
     * Load custom stylesheets
     * 
     * @return type
     */
    public function load_stylesheets() {
        if (!empty($this->get_cached_styles())) {
            return $this->get_cached_styles();
        }
        
        $new_css = $this->make_stylesheet();
        $this->make_cache($new_css);
        return $this->get_cached_styles();
    }
    
    /**
     * Delete block cached styles
     * 
     * @return type
     */
    public function delete_block_styles() {
        $cache = \cache::make($this->cache_component, $this->cache_area);
        if ($cache->get($this->cache_key)) {
            $cache->delete($this->cache_key);
        }
    }
    
    /**
     * Get cached stylesheet
     * 
     * @return boolean
     */
    private function get_cached_styles() {
        $cache = \cache::make($this->cache_component, $this->cache_area);
        if ($cache->get($this->cache_key)) {
            return $cache->get($this->cache_key);
        }
        return false;
    }
    
    /**
     * Make a cache of the custom stylesheet
     * 
     * @param string $css
     */
    private function make_cache($css) {
        $cache = \cache::make($this->cache_component, $this->cache_area);
        $cache->set($this->cache_key, $css);
    }
    
    /**
     * Make custom stylessheet
     * 
     * @global type $CFG
     * @return type
     */
    private function make_stylesheet() {
        global $CFG;
        
        $custom_styles = file_get_contents($CFG->dirroot . '/blocks/carousel/custom.css');
        $styles = $this->get_styles();
        if (empty($styles)) {
            return null;
        }
        foreach ($styles as $key => $val) {
            $custom_styles = $this->fill_css_placeholder($custom_styles, $key, $val);
        }
        return $custom_styles;
    }
    
    /**
     * Fill css place holders
     * 
     * @param string $string
     * @param string $search
     * @param string $replace
     * @return string replaced css code
     */
    private function fill_css_placeholder($string, $search, $replace = '') {
        return str_replace('[[settings:'.$search.']]', $replace, $string);
    }
    
    
    /**
     * Get block custom styles
     * 
     * @return type
     */
    private function get_styles() {
        $prefix = 'style_';
        $configs = $this->block_config;
        $styles['blockid'] = $this->block_id;
        
        foreach ($configs as $key => $val) {
            if (substr($key, 0, strlen($prefix)) === $prefix) {
                $styles[str_replace($prefix,'',$key)] = $val;
            }
        }
        
        return $styles;
    }
}