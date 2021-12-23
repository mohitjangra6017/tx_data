<?php

/**
 * carousel block.
 *
 * @package    block_carousel
 * @copyright  2019 Hoogesh Dawoodarry <hoogesh.dawoodarry@kineo.com.au>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use block_carousel\helper;

class block_carousel extends block_base {

    /**
     * Initializes class member variables.
     */
    public function init() {
        // Needed by Moodle to differentiate between blocks.
        $this->title = get_string('pluginname', 'block_carousel');
    }

    public function has_config() {
        return true;
    }

    /**
     * Returns the block contents.
     *
     * @return stdClass The block contents.
     */
    public function get_content() {
        global $DB, $PAGE, $OUTPUT, $USER, $CFG;

        require_once($CFG->dirroot . '/blocks/carousel/lib.php');
        require_once($CFG->dirroot . '/blocks/carousel/curatedlib.php');
        
        if ($this->content !== null) {
            return $this->content;
        }

        if (empty($this->instance)) {
            $this->content = '';
            return $this->content;
        }

        $PAGE->requires->js_call_amd('block_carousel/social', 'init', array('#inst' . $this->instance->id));

        $renderer = $PAGE->get_renderer('block_carousel');

        $this->content = new stdClass();
        $this->content->text = '';
        $this->content->items = array();
        $this->content->icons = array();

        $carouseltype = isset($this->config->carouseltype) ? $this->config->carouseltype : $this->title;

        // Check cohort visibility has been set
        $cohort_visibility = helper::check_cohort_visibility($this->instance->id, $USER->id);

        if ($cohort_visibility) {
            $classname = '\\block_carousel\\carouseltype\\'.$carouseltype;
            if (class_exists($classname)) {
                $data = $this->base_template_context();
                $carouseltype = new $classname($this->config, $this->instance);
                $this->content->text = $carouseltype->generate($data);
            }
        }

        if (empty($this->content->text) && !empty($this->config->hidecompletedcourses)) {
            $this->content = '';
            return $this->content;
        }

        // MYOHAS-117
        if(empty($this->content->text)) {
            $this->content = new stdClass();
            return $this->content;
        }

        // PERHAS-95
        // Add custom styles
        if ($this->content->text) {
            $custom_styles = new \block_carousel\custom_styles($this->instance->id, $this->config);
            $styles_data = new stdClass();
            $styles_data->custom_styles = $custom_styles->load_stylesheets();

            $this->content->footer = $renderer->custom_styles($styles_data);
        }

        return $this->content;
    }

    /**
     * Defines configuration data.
     *
     * The function is called immediatly after init().
     */
    public function specialization() {

        // Load user defined title and make sure it's never empty.
        if (empty($this->config->title)) {
            $this->title = get_string('pluginname', 'block_carousel');
        } else {
            $this->title = $this->config->title;
        }
    }

    /**
     * Allow multiple instances in a single course?
     *
     * @return bool True if multiple instances are allowed, false otherwise.
     */
    public function instance_allow_multiple() {
        return true;
    }

    public function hide_header() {
        return true;
    }

    /**
     * Add base properties to the template data
     *
     * @return string
     */
    public function base_template_context() {
        $data = [];

        $data['id'] = $this->instance->id;
        if ($this->get_common_config_value('override_title')) {
            $data['title'] = $this->get_common_config_value('title');
        }

        if (!empty($this->config->showdesc)) {
            $data['description'] = $this->config->description;
        } else {
            $data['description'] = '';
        }

        // Card size
        $data['cardcount'] = \block_carousel\constants::BKC_CARD_SMALL_COUNT;
        $data['cardsize'] = \block_carousel\constants::BKC_CARD_SMALL;

        $card_size = $this->get_card_size();
        if ($card_size == \block_carousel\constants::BKC_CARD_LARGE) {
            $data['cardcount'] = \block_carousel\constants::BKC_CARD_LARGE_COUNT;
            $data['cardsize'] = \block_carousel\constants::BKC_CARD_LARGE;
        }

        // add carousel type to the data
        $data['carouseltype'] = !empty($this->config->carouseltype) ? $this->config->carouseltype : null;

        $data['template'] = get_config('block_carousel', 'template');
        if (empty($data['template'])) {
            $data['template'] = \block_carousel\constants::BKC_TEMPLATE_RED;
        }

        return $data;
    }

    /**
     * Get the card size
     * Checks if instance card size is set then
     * Checks if global card size it set
     * then returns the default if neither is set
     * 
     * @return type
     */
    protected function get_card_size() {
        $card_size = \block_carousel\constants::BKC_CARD_SMALL;
        if (empty($this->config->cardsize)) {
            $global_cardsize = get_config('block_carousel', 'cardsize');
            if (!empty($global_cardsize) && $global_cardsize == \block_carousel\constants::BKC_CARD_LARGE) {
                $card_size = \block_carousel\constants::BKC_CARD_LARGE;
            }
        } else {
            $card_size = $this->config->cardsize;
        }

        return $card_size;
    }

    /**
     * Delete any instance related data
     * In this instance mostly related to curated content 
     *
     */
    public function instance_delete() { 
        global $DB;

        require_once 'curatedlib.php';

        $blockinstanceid = $this->instance->id;

        // delete instance record from 
        // block_carousel_cohorts
        $DB->delete_records('block_carousel_cohorts', ['blockinstanceid' => $blockinstanceid]);

        $curated_tiles = $DB->get_records('block_carousel_curated', ['blockinstanceid' => $blockinstanceid]);
        if (!empty($curated_tiles)) {
            foreach ($curated_tiles as $curated) {
                // delete files
                if (!empty($curated->id) && !empty($curated->filename)) {
                    \block_carousel\delete_curated_image($blockinstanceid, $curated);
                }
                // block_carousel_curated_course
                $DB->delete_records('block_carousel_curated_course', ['curatedid' => $curated->id]);
                // block_carousel_curated_tags
                $DB->delete_records('block_carousel_curated_tags', ['curatedid' => $curated->id]);
            }
            // finally delete from block_carousel_curated
            $DB->delete_records('block_carousel_curated', ['blockinstanceid' => $blockinstanceid]);
        }
    }

    function instance_copy($fromid) {
        global $DB;

        $records = $DB->get_records('block_carousel_cohorts', array('blockinstanceid' => $fromid));

        foreach ($records as $rec) {
            unset($rec->id);
            $rec->blockinstanceid = $this->instance->id;
            $DB->insert_record('block_carousel_cohorts', $rec);
        }

        $fs = get_file_storage();

        $records = $DB->get_records('block_carousel_curated', array('blockinstanceid' => $fromid));

        foreach ($records as $rec) {
            $oldid = $rec->id;
            unset($rec->id);
            $rec->blockinstanceid = $this->instance->id;
            $rec->id = $DB->insert_record('block_carousel_curated', $rec);

            $courses = $DB->get_records('block_carousel_curated_course', array('curatedid' => $oldid));
            foreach ($courses as $c) {
                unset($c->id);
                $c->curatedid = $rec->id;
                $DB->insert_record('block_carousel_curated_course', $c);
            }

            $tags = $DB->get_records('block_carousel_curated_tags', array('curatedid' => $oldid));
            foreach ($tags as $t) {
                unset($t->id);
                $t->curatedid = $rec->id;
                $DB->insert_record('block_carousel_curated_tags', $t);
            }

            $filerec = $DB->get_records('files', array('component' => 'block_carousel', 'itemid' => $oldid));
            foreach ($filerec as $fc) {
                $file = $fs->get_file($fc->contextid, $fc->component, $fc->filearea, $fc->itemid, '/', $fc->filename);
                if (!$file) {
                    continue; // continue if file doesn't exist
                }
                if ($file && $fc->filename === '.') {
                    continue; // continue
                }
                $contents = $file->get_content();

                $filerecord = (object) array(
                    'contextid' => $fc->contextid, // course id
                    'component' => 'block_carousel',
                    'filearea' => $fc->filearea, // component name
                    'itemid' => $rec->id, // component id
                    'filepath' => '/',
                    'filename' => $fc->filename, // current file name 
                );

                $fs->create_file_from_string($filerecord, $contents);
            }
        }

        return true;
    }
}
