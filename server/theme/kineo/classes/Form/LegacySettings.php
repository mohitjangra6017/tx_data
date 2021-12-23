<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon Adams <simon.adams@kineo.com>
 */

namespace theme_kineo\Form;


use core\files\file_helper;
use html_table;
use html_writer;
use moodle_url;
use moodleform;
use stdClass;
use stored_file;

global $CFG;
require_once $CFG->libdir . '/formslib.php';

class LegacySettings extends moodleform
{
    protected function definition()
    {
        global $OUTPUT;

        $form = $this->_form;

        $form->addElement('header', 'custom_fonts_header', get_string('form:legacy:heading:fonts', 'theme_kineo'));

        $customFontCss = $this->_customdata['custom_fonts_css'] ?? '';
        $customFonts = $this->_customdata['custom_fonts'] ?? null;
        $customImages = $this->_customdata['custom_images'] ?? null;

        $form->addElement(
            'filemanager',
            'custom_fonts',
            get_string('form:legacy:custom_fonts', 'theme_kineo'),
            null,
            [
                'accepted_types' => ['.woff', '.ttf', '.woff2', '.eot', '.fnt', '.otf'],
                'maxfiles' => 50,
                'subdirs' => false,
            ]
        );
        $form->setDefault('custom_fonts', $customFonts->custom_fonts ?? null);
        $form->addHelpButton('custom_fonts', 'form:legacy:custom_fonts', 'theme_kineo');

        $form->addElement('textarea', 'custom_fonts_css', get_string('form:legacy:custom_fonts:css', 'theme_kineo'));
        $form->setType('custom_fonts_css', PARAM_TEXT);
        $form->setDefault('custom_fonts_css', $customFontCss);


        $form->addElement('static',
            'custom_fonts_css_desc',
            '',
            '<pre>' . get_string('form:legacy:custom_fonts:css_desc', 'theme_kineo') . '</pre>'
        );

        $form->addElement('header', 'custom_images_header', get_string('form:legacy:heading:images', 'theme_kineo'));

        $form->addElement(
            'filemanager',
            'custom_images',
            get_string('form:legacy:custom_images', 'theme_kineo'),
            null,
            [
                'accepted_types' => ['.jpg', '.jpeg', '.svg', '.png'],
                'maxfiles' => 50,
                'subdirs' => false,
            ]
        );
        $form->setDefault('custom_images', $customImages->custom_images ?? null);
        $form->addHelpButton('custom_images', 'form:legacy:custom_images', 'theme_kineo');

        $fh = new file_helper('theme_kineo', 'custom_images', \context_system::instance());
        $fh->set_item_id($customImages->id);
        if ($files = $fh->get_stored_files()) {
            $tableObject = $this->getCustomImageTable($files);
            $form->addElement(
                'static',
                'custom_image_urls',
                '',
                $OUTPUT->render_from_template('core/table', $tableObject)
            );
        }

        $this->add_action_buttons();
    }

    function validation($data, $files)
    {
        global $USER;

        $errors = parent::validation($data, $files);

        $fh = new file_helper('user', 'draft', \context_user::instance($USER->id));
        $fh->set_item_id($data['custom_fonts']);
        $files = $fh->get_stored_files();

        $customCss = $data['custom_fonts_css'];
        $fontFaces = [];
        preg_match_all('~\s*@font-face\s*{(\s*|.*)*}\s*~', $customCss, $fontFaces);

        $fontCssErrors = [];
        foreach ($fontFaces as $fontFace) {
            $fontFace = reset($fontFace);
            if (empty($fontFace)) {
                continue;
            }
            if (!preg_match('~\s*font-family\s*:\s*[\'"].*?[\'"]\s*;~', $fontFace)) {
                $fontCssErrors[] = get_string('form:legacy:custom_fonts:css:error:font_family', 'theme_kineo');
            }
            if (!preg_match('~\s*font-weight\s*:\s*(?:normal|bold|[1-9]00)\s*;~', $fontFace)) {
                $fontCssErrors[] = get_string('form:legacy:custom_fonts:css:error:font_weight', 'theme_kineo');
            }
            if (!preg_match('~\s*font-style\s*:\s*(?:normal|italic|oblique)\s*;~', $fontFace)) {
                $fontCssErrors[] = get_string('form:legacy:custom_fonts:css:error:font_style', 'theme_kineo');
            }

            $srcUrls = [];
            preg_match_all('~\s*src:\s*url\(.*\s*\)|\s*url\(.*\)~', $fontFace, $srcUrls);

            foreach ($srcUrls as $srcUrl) {
                $srcUrl = reset($srcUrl);
                if (preg_match('~\s*format((?!woff|truetype|woff2|truetype|embedded-opentype|svg).)*$~', $srcUrl)) {
                    $fontCssErrors[] = get_string('form:legacy:custom_fonts:css:error:font_src_format', 'theme_kineo', $srcUrl);
                }

                $found = false;
                foreach ($files as $file) {
                    if (preg_match("~\s*@font:({$file->get_filename()})~", $srcUrl)) {
                        $found = true;
                    }
                }
                if (!$found) {
                    $fontCssErrors[] = get_string('form:legacy:custom_fonts:css:error:font_src', 'theme_kineo');
                }
            }
        }

        if (!empty($fontCssErrors)) {
            $errors['custom_fonts_css'] = implode('. ', $fontCssErrors);
        }

        return $errors;
    }

    /**
     * @param stored_file[] $files
     * @return stdClass
     */
    public function getCustomImageTable(array $files): stdClass
    {
        global $OUTPUT;

        $table = new html_table();
        $table->head = [
            get_string('form:legacy:custom_images:table_head::image', 'theme_kineo'),
            get_string('form:legacy:custom_images:table_head:paths', 'theme_kineo'),
        ];
        $table->attributes = ['class' => 'custom-image-table generaltable'];

        foreach ($files as $file) {
            $data = [];
            $url = moodle_url::make_pluginfile_url(
                $file->get_contextid(),
                $file->get_component(),
                $file->get_filearea(),
                $file->get_itemid(),
                $file->get_filepath(),
                $file->get_filename()
            );
            $data[] = html_writer::img($url->out(), $file->get_filename());
            $data[] = html_writer::span($url->out(), 'custom-image-path');

            $table->data[] = $data;
        }
        $table->colclasses = ['image', 'image-path'];
        return $table->export_for_template($OUTPUT);
    }
}