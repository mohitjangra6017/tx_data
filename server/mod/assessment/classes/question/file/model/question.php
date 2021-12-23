<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2017 Kineo
 * @package mod_assessment
 * @subpackage assquestion_file
 */

namespace mod_assessment\question\file\model;

use context_module;
use html_writer;
use mod_assessment\model\assessment;
use mod_assessment\model\version;
use mod_assessment\model\version_question;
use mod_assessment\question\file\form\element\filemanager;
use moodle_url;
use rb_column;
use reportbuilder;
use stdClass;
use totara_form\form;

class question extends \mod_assessment\model\question
{

    public function encode_value($value, form $form)
    {
        return $value;
    }

    public function get_default()
    {
        return null;    // No default for file uploads.
    }

    public function get_displayname(): string
    {
        return get_string('pluginname', 'assquestion_file');
    }

    public function get_element(): filemanager
    {
        return new filemanager("q_{$this->id}", $this->question, [
            'accept' => null,    // Accept anything for now.
            'context' => $this->get_file_context(),
            'maxfiles' => $this->get_maxuploads(),
            'subdirs' => true,
        ]);
    }

    public function get_file_context()
    {
        $vq = current(version_question::instances(['questionid' => $this->id]));
        $version = version::instance(['id' => $vq->versionid], MUST_EXIST);
        $assessment = assessment::instance(['id' => $version->assessmentid], MUST_EXIST);

        return context_module::instance($assessment->get_cmid());
    }

    /**
     * @return int
     */
    public function get_maxuploads(): int
    {
        return $this->get_config()->maxuploads;
    }

    public function get_type(): string
    {
        return 'file';
    }

    public function is_gradable(): bool
    {
        return false;
    }

    public function is_question(): bool
    {
        return true;
    }

    public function report_display($value, $format, stdClass $row, rb_column $column, reportbuilder $report): string
    {
        $fs = get_file_storage();

        // Get the files from one db query.  It is faster.
        $files = $fs->get_area_files(
            $this->get_file_context()->id,
            'mod_assessment',
            'answer',
            $this->id,
            "itemid, filepath, filename",
            false
        );

        $filelinks = [];
        foreach ($files as $file) {
            $url = moodle_url::make_pluginfile_url(
                $file->get_contextid(),
                $file->get_component(),
                $file->get_filearea(),
                $file->get_itemid(),
                $file->get_filepath(),
                $file->get_filename()
            );

            $filelinks[] = html_writer::link($url, $file->get_filename());
        }

        return implode(', ', $filelinks);
    }
}
