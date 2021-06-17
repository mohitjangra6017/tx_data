<?php
/**
 * This file is part of Totara Learn
 *
 * Copyright (C) 2021 onwards Totara Learning Solutions LTD
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Qingyang Liu <qingyang.liu@totaralearning.com>
 * @package totara_contentmarketplace
 */
namespace totara_contentmarketplace\course;

use context_course;
use file_exception;
use stored_file;

class course_image_downloader {
    /**
     * @var int
     */
    protected $context_id;

    /**
     * @var string
     */
    protected $url;

    /**
     * course_image_downloader constructor.
     * @param int $course_id
     * @param string $url
     */
    public function __construct(int $course_id, string $url) {
        global $CFG;
        require_once("{$CFG->dirroot}/lib/filelib.php");

        $context = context_course::instance($course_id);
        $this->context_id = $context->id;
        $this->url = $url;
    }

    /**
     * Create file record when return ture, the file exists, we do not want to create it again.
     *
     * @return bool|stored_file
     */
    public function download_image_for_course() {
        $filename = $this->make_filename();
        $info = $this->get_file_info($filename);
        $fs = get_file_storage();

        if ($fs->file_exists($this->context_id, $info['component'], $info['filearea'], $info['itemid'], $info['filepath'], $info['filename'])) {
            return true;
        }

        try {
            $file = $fs->create_file_from_url($info, $this->url, null, true);
        } catch (file_exception $e) {
            debugging('Unable to download remote image ' . $e->getMessage(), DEBUG_DEVELOPER);
            return false;
        }

        return $file;
    }

    /**
     * @return string
     */
    private function make_filename(): string {
        $pathinfo = pathinfo($this->url);
        $filename = $pathinfo['filename'];
        return sha1($filename) . '.' .$pathinfo['extension'];
    }

    /**
     * @return array
     */
    private function get_file_info(string $filename): array {
        // Compatible with course
        return [
            'contextid' => $this->context_id,
            'component' => 'course',
            'filearea' => 'images',
            'itemid' => 0,
            'filepath' => '/',
            'filename' => $filename
        ];
    }
}