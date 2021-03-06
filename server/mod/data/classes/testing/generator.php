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

namespace mod_data\testing;

use stdClass, coding_exception;

/**
 * Data generator class for mod_data.
 *
 * @package    mod_data
 * @category   test
 * @copyright  2012 Petr Skoda {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


/**
 * Data generator class for mod_data.
 *
 * Currently, the field types in the ignoredfieldtypes array aren't supported.
 *
 * @package    mod_data
 * @category   test
 * @copyright  2012 Petr Skoda {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class generator extends \core\testing\mod_generator {

    /**
     * @var int keep track of how many database fields have been created.
     */
    protected $databasefieldcount = 0;

    /**
     * @var int keep track of how many database records have been created.
     */
    protected $databaserecordcount = 0;

    /**
     * @var array The field types which not handled by the generator as of now.
     */
    protected $ignoredfieldtypes = [];


    /**
     * To be called from data reset code only,
     * do not use in tests.
     * @return void
     */
    public function reset() {
        $this->databasefieldcount = 0;
        $this->databaserecordcount = 0;

        parent::reset();
    }

    /**
     * Creates a mod_data instance
     *
     * @param array $record
     * @param array $options
     * @return StdClass
     */
    public function create_instance($record = null, array $options = null) {
        // Note, the parent class does not type $record to cast to array and then to object.
        $record = (object) (array) $record;

        if (!isset($record->assessed)) {
            $record->assessed = 0;
        }
        if (!isset($record->scale)) {
            $record->scale = 0;
        }

        return parent::create_instance((array) $record, $options);
    }

    /**
     * Creates a field for a mod_data instance.
     * Currently, the field types in the ignoredfieldtypes array aren't supported.
     *
     * @param StdClass $record
     * @param stdClass $data
     * @return data_field_{type}
     */
    public function create_field(stdClass $record = null, $data = null) {
        $record = (array) $record;

        if (in_array($record['type'], $this->ignoredfieldtypes)) {
            throw new coding_exception('$record\'s type value must not be same as values in ignoredfieldtypes
                    in \mod_data\testing\generator::create_field()');
            return false;
        }

        $this->databasefieldcount++;

        if (!isset($data->course)) {
            throw new coding_exception('course must be present in \mod_data\testing\generator::create_field() $data');
        }

        if (!isset($data->id)) {
            throw new coding_exception('dataid must be present in \mod_data\testing\generator::create_field() $data');
        } else {
            $record['dataid'] = $data->id;
        }

        if (!isset($record['type'])) {
            throw new coding_exception('type must be present in \mod_data\testing\generator::create_field() $record');
        }

        if (!isset($record['required'])) {
            $record['required'] = 0;
        }

        if (!isset($record['name'])) {
            $record['name'] = "testField - " . $this->databasefieldcount;
        }

        if (!isset($record['description'])) {
            $record['description'] = " This is testField - " . $this->databasefieldcount;
        }

        if (!isset($record['param1'])) {
            if ($record['type'] == 'checkbox') {
                $record['param1'] = implode("\n", array('opt1', 'opt2', 'opt3', 'opt4'));
            } else if ($record['type'] == 'radiobutton') {
                $record['param1'] = implode("\n", array('radioopt1', 'radioopt2', 'radioopt3', 'radioopt4'));
            } else if ($record['type'] == 'menu') {
                $record['param1'] = implode("\n", array('menu1', 'menu2', 'menu3', 'menu4'));
            } else if ($record['type'] == 'multimenu') {
                $record['param1'] = implode("\n", array('multimenu1', 'multimenu2', 'multimenu3', 'multimenu4'));
            } else if (($record['type'] === 'text') || ($record['type'] === 'url')) {
                $record['param1'] = 1;
            } else {
                $record['param1'] = '';
            }
        }

        if (!isset($record['param2'])) {

            if ($record['type'] === 'textarea') {
                $record['param2'] = 60;
            } else {
                $record['param2'] = '';
            }
        }

        if (!isset($record['param3'])) {

            if (($record['type'] === 'textarea')) {
                $record['param3'] = 35;
            } else {
                $record['param3'] = '';
            }
        }

        if (!isset($record['param4'])) {

            if (($record['type'] === 'textarea')) {
                $record['param4'] = 1;
            }
        }

        if (!isset($record['param5'])) {
            if (($record['type'] === 'textarea')) {
                $record['param5'] = 0;
            }
        }

        $record = (object) $record;

        $field = data_get_field($record, $data);
        $field->insert_field();

        data_generate_default_template($data, 'addtemplate', 0, false, true);

        return $field;
    }

    /**
     * Creates a field for a mod_data instance.
     * Keep in mind the default data field params created in create_field() function!
     * ...if you haven't provided your own custom data field parameters there.
     * Currently, the field types in the ignoredfieldtypes array aren't supported.
     * The developers using the generator must adhere to the following format :
     *
     *   Syntax : $contents[ fieldid ] = fieldvalue
     *   $contents['checkbox'] = array('val1', 'val2', 'val3' .....)
     *   $contents['data'] = 'dd-mm-yyyy'
     *   $contents['menu'] = 'value';
     *   $contents['multimenu'] =  array('val1', 'val2', 'val3' .....)
     *   $contents['number'] = 'numeric value'
     *   $contents['radiobuton'] = 'value'
     *   $contents['text'] = 'text'
     *   $contents['textarea'] = 'text'
     *   $contents['url'] = 'example.url' or array('example.url', 'urlname')
     *
     * @param stdClass $data
     * @param array $contents
     * @param int $groupid
     * @param array $tags
     * @param array $options
     * @return integer record id
     */
    public function create_entry($data, array $contents, $groupid = 0, $tags = [], array $options = null) {
        global $DB;

        $this->databaserecordcount++;

        $recordid = data_add_record($data, $groupid);

        if (isset($options['approved'])) {
            data_approve_entry($recordid, !empty($options['approved']));
        } else {
            $approved = null;
        }

        $fields = $DB->get_records('data_fields', array('dataid' => $data->id));

        // Validating whether required field are filled.
        foreach ($fields as $field) {
            $fieldhascontent = true;

            if (in_array($field->type, $this->ignoredfieldtypes)) {
                continue;
            }

            $field = data_get_field($field, $data);

            $fieldid = $field->field->id;

            if ($field->type === 'date') {
                $values = array();

                $temp = explode('-', $contents[$fieldid], 3);

                $values['field_' . $fieldid . '_day'] = (int)trim($temp[0]);
                $values['field_' . $fieldid . '_month'] = (int)trim($temp[1]);
                $values['field_' . $fieldid . '_year'] = (int)trim($temp[2]);

                // Year should be less than 2038, so it can be handled by 32 bit windows.
                if ($values['field_' . $fieldid . '_year'] > 2038) {
                    throw new coding_exception('DateTime::getTimestamp resturns false on 32 bit win for year beyond ' .
                        '2038. Please use year less than 2038.');
                }

                $contents[$fieldid] = $values;

                foreach ($values as $fieldname => $value) {
                    if (!$field->notemptyfield($value, $fieldname)) {
                        $fieldhascontent = false;
                    }
                }

            } else if ($field->type === 'textarea') {
                $values = array();

                $values['field_' . $fieldid] = $contents[$fieldid];
                $values['field_' . $fieldid . '_content1'] = 1;

                $contents[$fieldid] = $values;

                $fieldname = 'field_' . $fieldid;
                if (!$field->notemptyfield($values[$fieldname], $fieldname)) {
                    $fieldhascontent = false;
                }

            } else if ($field->type === 'url') {
                $values = array();

                if (is_array($contents[$fieldid])) {
                    foreach ($contents[$fieldid] as $key => $value) {
                        $values['field_' . $fieldid . '_' . $key] = $value;
                    }
                } else {
                    $values['field_' . $fieldid . '_0'] = $contents[$fieldid];
                }

                $contents[$fieldid] = $values;
                $fieldname = 'field_' . $fieldid . '_0';
                if (!$field->notemptyfield($values[$fieldname], $fieldname)) {
                    $fieldhascontent = false;
                }

            } else if ($field->type === 'latlong') {
                $values = array();
                $values['field_' . $fieldid . '_0'] = $contents[$fieldid][0];
                $values['field_' . $fieldid . '_1'] = $contents[$fieldid][1];

                $contents[$fieldid] = $values;

                foreach ($values as $fieldname => $value) {
                    if (!$field->notemptyfield($value, $fieldname)) {
                        $fieldhascontent = false;
                    }
                }

            } else if ($field->type == 'file' || $field->type == 'picture') {
                // We're not following the usual process for file uploads so we don't want to do anything here.

                $fieldhascontent = true;
            } else {
                if ($field->notemptyfield($contents[$fieldid], 'field_' . $fieldid . '_0')) {
                    continue;
                }
            }

            if ($field->field->required && !$fieldhascontent) {
                return false;
            }
        }
        foreach ($contents as $fieldid => $content) {
            $field = data_get_field_from_id($fieldid, $data);

            if ($field->type == 'file' || $field->type == 'picture') {
                // Get the files data. We'll update this with the data_content id below.
                // There'll be two records, the first contains the filename.
                $files = $DB->get_records('files', array('itemid' => $content), 'filename DESC');
                $file = current($files);

                // We're not going to execute the standard $field->update_content as we're not following the
                // file upload process, we'll just create the data_content record and update the files record.
                if (!$data_content = $DB->get_record('data_content', array('fieldid' => $field->field->id, 'recordid' => $recordid))) {
                    $data_content = new stdClass();
                    $data_content->fieldid  = $field->field->id;
                    $data_content->recordid = $recordid;
                    $data_content->content = $file->filename;
                    $data_content_id = $DB->insert_record('data_content', $data_content);
                }

                // Update the files records with the data_content id for itemid.
                foreach ($files as $id => $file) {
                    $DB->update_record('files', (object) ['id'  => $id, 'itemid' => $data_content_id]);
                }

            } else if (is_array($content) && in_array($field->type, array('date', 'textarea', 'url', 'latlong'))) {

                foreach ($content as $fieldname => $value) {
                    $field->update_content($recordid, $value, $fieldname);
                }

            } else {
                $field->update_content($recordid, $content);
            }
        }

        if (!empty($tags)) {
            $cm = get_coursemodule_from_instance('data', $data->id);
            \core_tag_tag::set_item_tags('mod_data', 'data_records', $recordid,
                \context_module::instance($cm->id), $tags);
        }

        return $recordid;
    }
}
