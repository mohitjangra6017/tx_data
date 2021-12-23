<?php
/**
 * @copyright City & Guilds Kineo 2018
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_isotope\Form;

use coding_exception;
use dml_exception;
use MoodleQuickForm;

class CourseCategory extends Option
{
    const NOT_EQUAL_TO = 'not_equal';
    const EQUAL_TO = 'equal';

    /**
     * @param MoodleQuickForm $form
     * @throws coding_exception
     * @throws dml_exception
     */
    public function extendForm(MoodleQuickForm $form)
    {
        global $PAGE, $DB, $CFG;

        // These are the minimum fields required for the Totara category selector box.
        $elements = [];
        $elements[] = $form->createElement(
            'select',
            $this->name . '_op',
            '',
            [
                self::NOT_EQUAL_TO => get_string('isnotequalto', 'filters'),
                self::EQUAL_TO => get_string('isequalto', 'filters'),
            ]
        );
        $elements[] = $form->createElement(
            'static',
            'title' . $this->name,
            '',
            \html_writer::tag(
                'span',
                '',
                ['id' => $this->name . 'title', 'class' => 'dialog-result-title']
            )
        );
        $form->setType($this->name . '_op', PARAM_TEXT);

        $elements[] = $form
            ->createElement(
                'static',
                'selectorbutton',
                '',
                \html_writer::empty_tag(
                    'input',
                    [
                        'type' => 'button',
                        'class' => 'rb-filter-button rb-filter-choose-category',
                        'value' => get_string('config:course_category:choosecatplural', 'local_isotope'),
                        'id' => 'show-' . $this->name . '-dialog',
                    ]
                )
            )
            ->set_allow_xss(true);

        $content = \html_writer::tag(
            'div',
            '',
            ['class' => 'rb-filter-content-list list-' . $this->name]
        );
        $elements[] = $form->createElement('static', $this->name . '_list', '', $content);

        $form->addElement('group', $this->name . '_grp', $this->label, $elements, '', false);

        $default = !empty($this->value) ? $this->value : '';

        [$sql, $params] = $DB->get_in_or_equal(explode(',', $default));
        if ($default && $categories = $DB->get_records_select('course_categories', "id {$sql}", $params)) {
            $names = \coursecat::make_categories_list();
            $out = \html_writer::start_tag('div', ['class' => 'rb-filter-content-list list-' . $this->name]);
            foreach ($categories as $category) {
                $out .= $this->displaySelectedCategoryItem($names, $category, $this->name);
            }
            $out .= \html_writer::end_tag('div');

            $form->setDefault($this->name . '_list', $out);
        }

        $form->addElement('hidden', $this->name, '');
        $form->setType($this->name, PARAM_SEQUENCE);

        if ($default) {
            $form->setDefault($this->name, $default);
        }

        local_js([TOTARA_JS_DIALOG, TOTARA_JS_TREEVIEW]);

        $PAGE->requires->strings_for_js(['choosecatplural'], 'totara_reportbuilder');
        if (file_exists($CFG->dirroot . '/totara/reportbuilder/amd/src/filter_dialogs.js')) {
            $PAGE->requires->js_call_amd('totara_reportbuilder/filter_dialogs', 'init', ['filter_to_load' => 'category']);
        }
    }

    /**
     * @param $names
     * @param $item
     * @param $filterName
     * @return string
     * @throws coding_exception
     */
    private function displaySelectedCategoryItem($names, $item, $filterName)
    {
        global $OUTPUT;

        $strDelete = get_string('delete');
        $itemName = (isset($names[$item->id])) ? $names[$item->id] : $item->name;
        $output = \html_writer::start_tag(
            'div',
            [
                'data-filtername' => $filterName,
                'data-id' => $item->id,
                'class' => 'multiselect-selected-item',
            ]
        );
        $output .= format_string($itemName);
        $output .= $OUTPUT->action_icon(
            '#',
            new \pix_icon('/t/delete', $strDelete, 'moodle'),
            null,
            ['class' => 'action-icon delete']
        );

        $output .= \html_writer::end_tag('div');
        return $output;
    }
}
