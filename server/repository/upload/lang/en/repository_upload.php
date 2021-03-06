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

/**
 * Strings for component 'repository_upload', language 'en', branch 'MOODLE_20_STABLE'
 *
 * @package   repository_upload
 * @copyright 1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['configplugin'] = 'Configuration for upload plugin';
$string['pluginname_help'] = 'Upload a file to Totara.';
$string['pluginname'] = 'Upload a file';
$string['mimetype_whitelist'] = 'Mimetype whitelist';
$string['mimetype_whitelist_help'] = 'Use this setting to restrict the file types that users can upload into the site. Add one mimetype per line.

image/jpeg<br />
image/png<br />
image/svg<br />
application/zip

This setting is forced on all files uploaded via this repository. Areas permitting files to be uploaded may further restrict accepted types.

When the whitelist is empty all mimetypes are allowed.
';
$string['mimetype_whitelist_link'] = 'Repositories#Repositories-Uploadafile';
$string['mimetype_whitelist_validation_errors'] = 'The provided mimetype whitelist is not valid, the following errors were found: {$a}';
$string['mimetype_whitelist_validation_empties'] = 'Empty values are invalid, please remove any empty lines.';
$string['mimetype_whitelist_validation_duplicates'] = 'Duplicate values have been found, please remove them.';
$string['mimetype_whitelist_validation_invalid_type'] = 'The following is not a valid mimetype: {$a}';
$string['upload:view'] = 'Use uploading in file picker';
$string['upload_error_ini_size'] = 'The uploaded file exceeds the upload_max_filesize directive in php.ini.';
$string['upload_error_form_size'] = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';
$string['upload_error_partial'] = 'The uploaded file was only partially uploaded.';
$string['upload_error_no_file'] = 'No file was uploaded.';
$string['upload_error_no_tmp_dir'] = 'PHP is missing a temporary folder.';
$string['upload_error_cant_write'] = 'Failed to write file to disk.';
$string['upload_error_extension'] = 'A PHP extension stopped the file upload.';
$string['upload_error_invalid_file'] = 'The file \'{$a}\' is either empty or a folder. To upload folders zip them first.';
