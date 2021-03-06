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
 * Google Docs repository data generator
 *
 * @package    repository_googledocs
 * @category   test
 * @copyright  2013 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace repository_googledocs\testing;

/**
 * Google Docs repository data generator class
 *
 * @package    repository_googledocs
 * @category   test
 * @copyright  2013 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class generator extends \core\testing\repository_generator {

    /**
     * Fill in type record defaults.
     *
     * @param array $record
     * @return array
     */
    protected function prepare_type_record(array $record) {
        $record = parent::prepare_type_record($record);
        if (!isset($record['clientid'])) {
            $record['clientid'] = 'clientid';
        }
        if (!isset($record['secret'])) {
            $record['secret'] = 'secret';
        }
        if (!isset($record['documentformat'])) {
            $record['documentformat'] = 'pdf';
        }
        if (!isset($record['presentationformat'])) {
            $record['presentationformat'] = 'pdf';
        }
        if (!isset($record['drawingformat'])) {
            $record['drawingformat'] = 'pdf';
        }
        if (!isset($record['spreadsheetformat'])) {
            $record['spreadsheetformat'] = 'pdf';
        }
        return $record;
    }

}
