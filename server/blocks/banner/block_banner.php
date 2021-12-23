<?php

require_once($CFG->dirroot . '/totara/core/js/lib/setup.php');
require_once($CFG->dirroot . '/blocks/banner/lib.php');
require_once($CFG->dirroot . '/totara/hierarchy/prefix/position/lib.php');

// Banner Block
class block_banner extends block_base
{
    /** @var string */
    private $textonly = '';

    /** @var int[] */
    private $image_size = [
        'height' => 0,
        'width' => 0,
    ];

    /** @var bool */
    private $selfSelector = true;

    /**
     * @throws coding_exception
     */
    public function init(): void
    {
        $this->title = get_string('pluginname', 'block_banner');
    }

    /**
     * @return bool
     */
    public function has_config(): bool
    {
        return true;
    }

    /**
     * @return array|bool[]
     */
    public function applicable_formats(): array
    {
        return ['all' => true];
    }

    public function specialization(): void
    {
        if (!isset($this->config) || is_null($this->config)) {
            $this->config = new stdClass();

            $this->config->classes = null;

            $this->config->bg_repeat = null;
            $this->config->bg_pos = null;
            $this->config->bg_pos_left = null;
            $this->config->bg_pos_top = null;
            $this->config->bg_size = null;
            $this->config->bg_size_width = null;
            $this->config->bg_size_extra = null;
            $this->config->bg_size_height = null;
            $this->config->bg_attachment = null;

            $this->config->ct_size = null;
            $this->config->ct_size_width = null;
            $this->config->ct_size_extra = null;
            $this->config->ct_size_height = null;
        }

        if (empty($this->config->imagestrategy)) {
            $this->config->imagestrategy = 'first';
        }

        if (empty($this->config->textcolour)) {
            $this->config->textcolour = 'darktext';
        }
    }

    /**
     * @return bool
     */
    public function instance_allow_multiple(): bool
    {
        return true;
    }

    /**
     * @return object|null
     * @throws Exception
     */
    public function get_content(): ?object
    {
        global $CFG, $PAGE;

        require_once($CFG->libdir . '/filelib.php');

        if ($this->content !== null) {
            return $this->content;
        }

        $filterOpt = new stdClass();
        $filterOpt->overflowdiv = true;
        if ($this->content_is_trusted()) {
            $filterOpt->noclean = true;
        }

        $this->content = new stdClass();
        if (isset($this->config)) {
            $this->config->banner = isset($this->config->text) ? $this->config->text : false;
        }
        if ($PAGE->user_is_editing()) {
            if (isset($this->config->text)) {
                $this->config->text =
                    file_rewrite_pluginfile_urls(
                        $this->config->text,
                        'pluginfile.php',
                        $this->context->id,
                        'block_banner',
                        'content',
                        null
                    );
                // Default to FORMAT_HTML which is what will have been used before the
                // ...editor was properly implemented for the block.
                $format = FORMAT_HTML;
                // Check to see if the format has been properly set on the config.
                if (isset($this->config->format)) {
                    $format = $this->config->format;
                }
                $this->content->text = format_text($this->config->text, $format, $filterOpt);
            }
        } else {
            $this->content->text = '';
        }
        $image = $this->getImage();

        $PAGE->requires->js('/blocks/banner/js/media.parser.js');

        if (!isset($this->config->selector) || empty($this->config->selector)) {
            $this->config->selector = '#inst' . $this->instance->id;
            $this->selfSelector = true;
        } else {
            $this->selfSelector = false;
        }

        $bgStyle = '';
        if (!empty($this->config->bg_repeat)) {
            $bgStyle .= "$('{$this->config->selector}').css('background-repeat', '{$this->config->bg_repeat}');";
        }

        if (!empty($this->config->bg_pos)) {
            if ($this->config->bg_pos === 'custom') {
                $left = (!empty($this->config->bg_pos_left)) ? $this->config->bg_pos_left : 0;
                $top = (!empty($this->config->bg_pos_top)) ? $this->config->bg_pos_top : 0;
                $bgStyle .= "$('{$this->config->selector}').css('background-position', '{$left} {$top}');";
            } else {
                $bgStyle .= "$('{$this->config->selector}').css('background-position', '{$this->config->bg_pos}');";
            }
        }

        if (!empty($this->config->bg_size)) {
            if ($this->config->bg_size === 'custom') {
                $width = (!empty($this->config->bg_size_width)) ? $this->config->bg_size_width : 'auto';
                $height = 'auto';
                if ($this->config->bg_size_extra === 'custom') {
                    $height = (!empty($this->config->bg_size_height)) ? $this->config->bg_size_height : 'auto';
                }
                $bgStyle .= "$('{$this->config->selector}').css('background-size', '{$width} {$height}');";
            } else {
                $bgStyle .= "$('{$this->config->selector}').css('background-size', '{$this->config->bg_size}');";
            }
        }

        if (!empty($this->config->bg_attachment)) {
            $bgStyle .= "$('{$this->config->selector}')";
            $bgStyle .= ".css('background-attachment', '{$this->config->bg_attachment}');";
        }

        if (!empty($this->config->ct_size)) {
            if ($this->config->ct_size === 'auto') {
                $width = $this->image_size['width'];
                $height = $this->image_size['height'];
            } else {
                $width = $this->config->ct_size_width;
                $height = $this->config->ct_size_height;
            }

            if ($width) {
                $bgStyle .= "$('{$this->config->selector}').css('width', '{$width}');";
            }
            if ($height) {
                $bgStyle .= "$('{$this->config->selector}').css('height', '{$height}');";
            }
        }

        $function_name = 'banner_instance_' . $this->instance->id;

        /*
         * The idea behind this function is so it's called after the document is ready and set everything required.
         */
        $js = "function {$function_name}() {
                        %BACKGROUND%
                        %PAGEEDIT%
                        %STYLE%
                        %CLASSES%
                   }

                   if (typeof banner_instances === 'undefined') {
                        var banner_instances = [];
                   }
                   banner_instances.push('{$function_name}');
                  ";
        $background = '';
        $pageedit = '';
        $classes = '';

        if (!empty($this->config->colour)) {
            $background = "$('{$this->config->selector}').css('background-color', '#{$this->config->colour}');";
        }

        if (!$this->page->user_is_editing() && !$this->selfSelector) {
            $pageedit = "(function($){\$('#inst{$this->instance->id}').hide();})(jQuery);";
        }

        if ($this->selfSelector && !empty($this->config->classes)) {
            $classes = "$('{$this->config->selector}').addClass('{$this->config->classes}');";
        }

        $js = str_replace(
            ['%BACKGROUND%', '%PAGEEDIT%', '%STYLE%', '%CLASSES%'],
            [$background, $pageedit, $bgStyle, $classes],
            $js
        );

        $this->content->text = '<script type="text/javascript">' . $js . '</script>';
        $this->content->text .= $this->textonly;
        $this->content->bgimg = (empty($image) ? '' : "background-image: url('{$image}');");

        $this->content->text = $this->replaceVariables($this->content->text);

        return $this->content;
    }

    /**
     * Serialize and store config data
     * @param object $data
     * @param false $nolongerused
     */
    public function instance_config_save($data, $nolongerused = false): void
    {
        $config = clone($data);
        // Move embedded files into a proper filearea and adjust HTML links to match.
        $config->text =
            file_save_draft_area_files(
                $data->text['itemid'],
                $this->context->id,
                'block_banner',
                'content',
                0,
                ['subdirs' => true],
                $data->text['text']
            );
        $config->format = $data->text['format'];

        if ($config->bg_pos !== 'custom') {
            $config->bg_pos_left = '0';
            $config->bg_pos_top = '0';
        }

        if ($config->bg_size !== 'custom') {
            $config->bg_size_width = null;
            $config->bg_size_height = null;
            $config->bg_size_extra = '0';
        } else if ($config->bg_size_extra !== 'custom') {
            $config->bg_size_height = null;
        }

        if ($config->ct_size !== 'custom') {
            $config->ct_size_width = null;
            $config->ct_size_height = null;
        }

        parent::instance_config_save($config, $nolongerused);
    }

    /**
     * @param int $fromId
     * @return bool
     * @throws coding_exception
     * @throws file_exception
     * @throws file_reference_exception
     * @throws stored_file_creation_exception
     */
    public function instance_copy($fromId): bool
    {
        $fs = get_file_storage();

        $context = context_block::instance($fromId);

        $origFiles = $fs->get_area_files($context->id, 'block_banner', 'content', false, 'id', false);
        foreach ($origFiles as $origFile) {
            $fileRecord = new stdClass();
            $fileRecord->contextid = $this->context->id;
            $fs->create_file_from_storedfile($fileRecord, $origFile);
        }

        return true;
    }

    /**
     * @return bool
     */
    public function instance_delete(): bool
    {
        $fs = get_file_storage();
        $fs->delete_area_files($this->context->id, 'block_banner');

        return true;
    }

    /**
     * @return bool
     * @throws coding_exception
     */
    public function content_is_trusted(): bool
    {
        global $SCRIPT;

        if (!$context = context::instance_by_id($this->instance->parentcontextid, IGNORE_MISSING)) {
            return false;
        }
        // Find out if this block is on the profile page.
        if ($context->contextlevel == CONTEXT_USER) {
            if ($SCRIPT === '/my/index.php') {
                return true;
            } else {
                return false;
            }
        }

        return true;
    }

    /**
     * The block should only be dockable when the title of the block is not empty
     * and when parent allows docking.
     *
     * @return bool
     */
    public function instance_can_be_docked(): bool
    {
        return (!empty($this->get_title()) && parent::instance_can_be_docked());
    }

    /**
     * Add custom html attributes to aid with theming and styling
     * @return array
     */
    public function html_attributes(): array
    {
        global $CFG;

        $attributes = parent::html_attributes();
        $attributes['selector'] = '';
        if (!empty($CFG->block_banner_allowcssclasses)) {
            // Only add classes to itself, if it is not pointing to another selector.
            if (!empty($this->config->classes) && $this->selfSelector) {
                $attributes['class'] .= ' ' . $this->config->classes;
            }
        }
        if (!empty($this->config->selector)) {
            $attributes['selector'] = $this->config->selector;
        }
        if (!empty($this->config->colour)) {
            $attributes['colour'] = $this->config->colour;
        }

        if (!empty($this->config->textcolour)) {
            $attributes['class'] .= ' ' . $this->config->textcolour;
            $attributes['textcolour'] = $this->config->textcolour;
        }

        return $attributes;
    }

    /**
     * If overridden and set to false by the block it will not be hidable when
     * editing is turned on.
     *
     * @return bool
     */
    public function instance_can_be_hidden(): bool
    {
        return true;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getImage(): string
    {
        if (!isset($this->config->banner)) {
            return '';
        }
        $strategyManager = block_banner_get_strategy_manager();
        $strategy = $strategyManager->getStrategy($this->config->imagestrategy);
        [
            $image,
            $textOnly,
            $this->image_size['height'],
            $this->image_size['width'],
        ] = $strategy->getImage($this->context->id, $this->config->banner);

        // Rewrite urls for the rest of the content to display videos or links.
        $textOnly =
            file_rewrite_pluginfile_urls(
                $textOnly,
                'pluginfile.php',
                $this->context->id,
                'block_banner',
                'content',
                null
            );
        $format = isset($this->config->format) ? $this->config->format : FORMAT_HTML;
        $filterOpt = new stdClass();
        $filterOpt->overflowdiv = true;
        $filterOpt->noclean = true;
        $this->textonly = format_text($textOnly, $format, $filterOpt);

        return $image;
    }

    /**
     * extend function to add the uploaded graphic as inline style backgound image at block level
     * @param $output
     * @return block_contents|null
     */
    public function get_content_for_output($output): ?block_contents
    {
        $bc = parent::get_content_for_output($output);
        // Kineo amend to implement the AD - add bg at block level.
        if (!empty($this->content->bgimg) && !empty($bc)) {
            $bc->attributes['style'] = $this->content->bgimg;
        }

        return $bc;
    }

    /**
     * @param string $text
     * @return mixed
     * @throws Exception
     */
    public function replaceVariables(string $text)
    {
        global $USER;

        $strategyManager = block_banner_get_strategy_manager();
        $strategy = $strategyManager->getStrategy('variables');

        return $strategy->replaceVariables($text, $USER->id, true);
    }
}
