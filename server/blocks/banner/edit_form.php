<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

use block_banner\BackgroundOptions;

require_once(__DIR__ . '/lib.php');

defined('MOODLE_INTERNAL') || die;

class block_banner_edit_form extends block_edit_form
{

    /**
     * Enable general settings
     *
     * @return bool
     */
    protected function has_general_settings(): bool
    {
        return true;
    }

    /**
     * @param MoodleQuickForm $mform
     * @throws coding_exception
     */
    protected function specific_definition($mform): void
    {
        global $CFG;

        $strategyManager = block_banner_get_strategy_manager();
        $backgroundOptions = new BackgroundOptions();

        // Fields for editing HTML block title and contents.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $mform->addElement('text', 'config_selector', get_string('configselector', 'block_banner'));
        $mform->addHelpButton('config_selector', 'configselector', 'block_banner');
        $mform->setType('config_selector', PARAM_TEXT);

        $mform->addElement('text', 'config_colour', get_string('configcolour', 'block_banner'));
        $mform->addHelpButton('config_colour', 'configcolour', 'block_banner');
        $mform->setType('config_colour', PARAM_NOTAGS);

        $variables = $strategyManager->getStrategy('variables');
        $mform->addElement(
            'static',
            'variablelist',
            get_string('variables:list', 'block_banner', implode(', ', $variables->getVariables()))
        );

        $editoroptions = ['maxfiles' => EDITOR_UNLIMITED_FILES, 'noclean' => true, 'context' => $this->block->context];
        $mform->addElement('editor', 'config_text', get_string('configcontent', 'block_banner'), null, $editoroptions);
        $mform->addRule('config_text', null, 'required', null, 'client');
        $mform->setType('config_text', PARAM_RAW);

        if (!empty($CFG->block_banner_allowcssclasses)) {
            $mform->addElement('text', 'config_classes', get_string('configclasses', 'block_banner'));
            $mform->setType('config_classes', PARAM_TEXT);
            $mform->addHelpButton('config_classes', 'configclasses', 'block_banner');
        }

        $strategies = [];
        foreach ($strategyManager->getStrategies() as $name => $strategy) {
            $image_interfaces = array_filter(
                class_implements($strategy, false),
                function ($o) {
                    $split = explode("\\", $o);
                    return (array_pop($split) == 'ImageStrategyInterface');
                }
            );
            if (count($image_interfaces) > 0) {
                $strategies[$name] = $strategy;
            }
        }

        $strategies = array_map(
            function ($strategy) {
                return $strategy->getName();
            },
            $strategies
        );
        $mform->addElement(
            'select',
            'config_imagestrategy',
            get_string('configimagestrategy', 'block_banner'),
            $strategies,
            'first'
        );

        $text_colours = [
            'darktext' => get_string('darktext', 'block_banner'),
            'lighttext' => get_string('lighttext', 'block_banner'),
        ];
        $mform->addElement(
            'select',
            'config_textcolour',
            get_string('configtextcolour', 'block_banner'),
            $text_colours,
            'darktext'
        );
        $mform->addHelpButton('config_textcolour', 'configtextcolour', 'block_banner');

        $choose = [0 => get_string('choosedots')];

        $mform->addElement('header', 'backgroundoptions', get_string('backgroundoptions', 'block_banner'));
        $mform->setExpanded('backgroundoptions');
        // Background repeat options.
        $repeatOptions = $choose + $backgroundOptions->repeat();
        $mform->addElement(
            'select',
            'config_bg_repeat',
            get_string('configbgrepeat', 'block_banner'),
            $repeatOptions,
            0
        );
        $mform->addElement('static', 'config_bg_repeat_help', '', get_string('configbgrepeat_help', 'block_banner'));

        // Background position options.
        $positionOptions = $choose + $backgroundOptions->position();

        $positionElements = [];
        $positionElements[] = &$mform->createElement('select', 'config_bg_pos', '', $positionOptions, 0);

        $positionElements[] = &
            $mform->createElement(
                'text',
                'config_bg_pos_left',
                '',
                ['placeholder' => get_string('posleft', 'block_banner')]
            );
        $mform->setType('config_bg_pos_left', PARAM_TEXT);

        $positionElements[] = &
            $mform->createElement(
                'text',
                'config_bg_pos_top',
                '',
                ['placeholder' => get_string('postop', 'block_banner')]
            );
        $mform->setType('config_bg_pos_top', PARAM_TEXT);

        $mform->addGroup($positionElements, '', get_string('configbgpos', 'block_banner'), [' '], false);
        $mform->addElement('static', 'config_bg_pos_help', '', get_string('configbgpos_help', 'block_banner'));

        $mform->disabledIf('config_bg_pos_left', 'config_bg_pos', 'neq', 'custom');
        $mform->disabledIf('config_bg_pos_top', 'config_bg_pos', 'neq', 'custom');

        // Background size options.
        $sizeOptions = $choose + $backgroundOptions->size();

        $sizeElements = [];
        $sizeElements[] = &$mform->createElement('select', 'config_bg_size', '', $sizeOptions, 0);
        $sizeElements[] = &$mform->createElement(
            'text',
            'config_bg_size_width',
            '',
            ['placeholder' => get_string('sizewidth', 'block_banner')]
        );
        $mform->setType('config_bg_size_width', PARAM_TEXT);

        $minimalSizes = $choose + [
                'auto' => $sizeOptions['auto'],
                'custom' => $sizeOptions['custom'],
            ];
        $sizeElements[] = &$mform->createElement('select', 'config_bg_size_extra', '', $minimalSizes, 0);
        $sizeElements[] = &$mform->createElement(
            'text',
            'config_bg_size_height',
            '',
            ['placeholder' => get_string('sizeheight', 'block_banner')]
        );
        $mform->setType('config_bg_size_height', PARAM_TEXT);

        $mform->addGroup($sizeElements, '', get_string('configbgsize', 'block_banner'), [' '], false);
        $mform->addElement('static', 'config_bg_size_help', '', get_string('configbgsize_help', 'block_banner'));

        $attachments = $choose + $backgroundOptions->attachment();
        $mform->addElement(
            'select',
            'config_bg_attachment',
            get_string('configbgattachment', 'block_banner'),
            $attachments
        );
        $mform->addElement(
            'static',
            'config_bg_attachment_help',
            '',
            get_string('configbgattachment_help', 'block_banner')
        );

        $mform->disabledIf('config_bg_size_width', 'config_bg_size', 'neq', 'custom');
        $mform->disabledIf('config_bg_size_extra', 'config_bg_size', 'neq', 'custom');
        $mform->disabledIf('config_bg_size_height', 'config_bg_size_extra', 'neq', 'custom');

        // Content size options.
        $sizeElements = [];
        $sizeElements[] = &$mform->createElement('select', 'config_ct_size', '', $minimalSizes, 0);
        $sizeElements[] = &$mform->createElement(
            'text',
            'config_ct_size_width',
            '',
            ['placeholder' => get_string('sizewidth', 'block_banner')]
        );
        $mform->setType('config_ct_size_width', PARAM_TEXT);

        $sizeElements[] = &$mform->createElement(
            'text',
            'config_ct_size_height',
            '',
            ['placeholder' => get_string('sizeheight', 'block_banner')]
        );
        $mform->setType('config_ct_size_height', PARAM_TEXT);

        $mform->addGroup($sizeElements, '', get_string('configctsize', 'block_banner'), [' '], false);
        $mform->addElement('static', 'config_ct_size_help', '', get_string('configctsize_help', 'block_banner'));

        $mform->disabledIf('config_ct_size_width', 'config_ct_size', 'neq', 'custom');
        $mform->disabledIf('config_ct_size_height', 'config_ct_size', 'neq', 'custom');
    }

    /**
     * @param stdClass $defaults
     */
    public function set_data($defaults): void
    {
        if (!empty($this->block->config) && is_object($this->block->config)) {
            $text = !empty($this->block->config->text) ? $this->block->config->text : '';
            $draftid_editor = file_get_submitted_draft_itemid('config_text');
            if (empty($text)) {
                $currentText = '';
            } else {
                $currentText = $text;
            }
            $defaults->config_text['text'] =
                file_prepare_draft_area(
                    $draftid_editor,
                    $this->block->context->id,
                    'block_banner',
                    'content',
                    0,
                    ['subdirs' => true],
                    $currentText
                );
            $defaults->config_text['itemid'] = $draftid_editor;
        } else {
            $text = '';
        }

        unset($this->block->config->text);
        parent::set_data($defaults);
        if (!isset($this->block->config)) {
            $this->block->config = new stdClass();
        }

        $this->block->config->text = $text;
        if (isset($title)) {
            $this->block->config->title = $title;
        }
    }

    /**
     * @param array $data
     * @param array $files
     * @return array
     * @throws coding_exception
     */
    public function validation($data, $files): array
    {
        $errors = [];

        // Validate the values of the size fields.
        // If it is a number which contains the suffix pt/px/em/rem/dppx/ex/ch/vh/vw/vmin/vmax/mm/cm/in/pc/% allow it.

        $properties = [];
        if (!empty($data['config_bg_pos_left'])) {
            $properties['config_bg_pos_left'] = $data['config_bg_pos_left'];
        }
        if (!empty($data['config_bg_pos_top'])) {
            $properties['config_bg_pos_top'] = $data['config_bg_pos_top'];
        }
        if (!empty($data['config_bg_size_width'])) {
            $properties['config_bg_size_width'] = $data['config_bg_size_width'];
        }
        if (!empty($data['config_bg_size_height'])) {
            $properties['config_bg_size_height'] = $data['config_bg_size_height'];
        }
        if (!empty($data['config_ct_size_width'])) {
            $properties['config_ct_size_width'] = $data['config_ct_size_width'];
        }
        if (!empty($data['config_ct_size_height'])) {
            $properties['config_ct_size_height'] = $data['config_ct_size_height'];
        }

        $pattern = "/[0-9]*\.?[0-9]+\s?(?:pt|px|em|rem|dppx|ex|ch|vh|vw|vmin|vmax|mm|cm|in|pc|%)?/";
        foreach ($properties as $key => $value) {
            if (!preg_match($pattern, $value)) {
                $errors[$key] = get_string('invalidnumber', 'block_banner');
            }
        }

        return $errors;
    }
}
