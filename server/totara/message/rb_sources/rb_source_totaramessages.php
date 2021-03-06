<?php
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2010 onwards Totara Learning Solutions LTD
 * Copyright (C) 1999 onwards Martin Dougiamas
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
 * @author Simon Coggins <simon.coggins@totaralms.com>
 * @package totara
 * @subpackage reportbuilder
 */

global $CFG;

use totara_core\advanced_feature;

require_once($CFG->dirroot . '/totara/message/lib.php');

defined('MOODLE_INTERNAL') || die();

class rb_source_totaramessages extends rb_base_source {
    use \totara_job\rb\source\report_trait;

    public function __construct($groupid, rb_global_restriction_set $globalrestrictionset = null) {
        if ($groupid instanceof rb_global_restriction_set) {
            throw new coding_exception('Wrong parameter orders detected during report source instantiation.');
        }
        // Remember the active global restriction set.
        $this->globalrestrictionset = $globalrestrictionset;

        // Apply global user restrictions.
        $this->add_global_report_restriction_join('msg', 'useridfrom');
        $this->add_global_report_restriction_join('msg', 'useridto');

        $this->base = '{message_metadata}';
        $this->joinlist = $this->define_joinlist();
        $this->columnoptions = $this->define_columnoptions();
        $this->filteroptions = $this->define_filteroptions();
        $this->contentoptions = $this->define_contentoptions();
        $this->paramoptions = $this->define_paramoptions();
        $this->defaultcolumns = $this->define_defaultcolumns();
        $this->defaultfilters = $this->define_defaultfilters();
        $this->requiredcolumns = $this->define_requiredcolumns();
        $this->sourcetitle = get_string('sourcetitle', 'rb_source_totaramessages');
        $this->sourcesummary = get_string('sourcesummary', 'rb_source_totaramessages');
        $this->sourcelabel = get_string('sourcelabel', 'rb_source_totaramessages');
        $this->usedcomponents[] = 'totara_message';
        $this->sourcewhere = '(base.timeread IS NULL)';

        parent::__construct();
    }

    /**
     * Global report restrictions are implemented in this source.
     * @return boolean
     */
    public function global_restrictions_supported() {
        return true;
    }

    //
    //
    // Methods for defining contents of source
    //
    //

    protected function define_joinlist() {
        $joinlist = [
            new rb_join(
                'msg',
                'INNER',
                '{notifications}',
                'msg.id = base.notificationid',
                REPORT_BUILDER_RELATION_ONE_TO_ONE
            ),
            new rb_join(
                'processor',
                'INNER',
                '{message_processors}',
                'base.processorid = processor.id',
                REPORT_BUILDER_RELATION_ONE_TO_ONE,
                ['base']
            ),
        ];

        // Include a join for the user that the message was sent to.
        $this->add_core_user_tables($joinlist, 'msg', 'useridto', 'userto');

        // Include some standard joins. Including the user the message was sent from.
        $this->add_core_user_tables($joinlist, 'msg', 'useridfrom');
        $this->add_totara_job_tables($joinlist, 'msg', 'useridfrom');

        return $joinlist;
    }

    /**
     * @return rb_column_option[]
     */
    protected function define_columnoptions() {
        global $DB;
        $columnoptions = [
            new rb_column_option(
                'message_values',
                'subject',
                get_string('subject', 'rb_source_totaramessages'),
                'msg.subject',
                [
                    'joins' => 'msg',
                    'dbdatatype' => 'text',
                    'outputformat' => 'text',
                    'displayfunc' => 'format_string',
                ]
            ),
            new rb_column_option(
                'message_values',
                'statement',
                get_string('statement', 'rb_source_totaramessages'),
                'msg.fullmessagehtml',
                [
                    'joins' => 'msg',
                    'dbdatatype' => 'text',
                    'outputformat' => 'text',
                    'displayfunc' => 'format_text',
                ]
            ),
            new rb_column_option(
                'message_values',
                'urgency',
                get_string('msgurgencyicon', 'rb_source_totaramessages'),
                'base.urgency',
                [
                    'defaultheading' => get_string('msgurgency', 'rb_source_totaramessages'),
                    'displayfunc' => 'message_urgency_link',
                ]
            ),
            new rb_column_option(
                'message_values',
                'urgency_text',
                get_string('msgurgency', 'rb_source_totaramessages'),
                'base.urgency',
                ['displayfunc' => 'message_urgency_text']
            ),
            new rb_column_option(
                'message_values',
                'msgtype',
                get_string('msgtype', 'rb_source_totaramessages'),
                'base.msgtype',
                [
                    'joins' => ['msg'],
                    'displayfunc' => 'message_type_link',
                    'extrafields' => [
                        'msgid' => 'base.notificationid',
                        'msgsubject' => 'msg.subject',
                        'msgicon' => 'base.icon',
                    ],
                ]
            ),
            new rb_column_option(
                'message_values',
                'msgtype_text',
                get_string('msgtypetext', 'rb_source_totaramessages'),
                'base.msgtype',
                [
                    'defaultheading' => get_string('msgtype', 'rb_source_totaramessages'),
                    'displayfunc' => 'message_type_text',
                ]
            ),
            new rb_column_option(
                'message_values',
                'category',
                get_string('msgcategory', 'rb_source_totaramessages'),
                // icon uses format like 'competency-regular'
                // strip from first '-' to get general message category
                "CASE WHEN " . $DB->sql_position("'-'", 'base.icon') . " > 0 THEN " .
                $DB->sql_substr('base.icon', 1, $DB->sql_position("'-'", 'base.icon') . '-1') .
                " ELSE 'base.icon' " .
                " END ",
                ['displayfunc' => 'message_category_text']
            ),
            new rb_column_option(
                'message_values',
                'sent',
                get_string('sent', 'rb_source_totaramessages'),
                'msg.timecreated',
                [
                    'joins' => 'msg',
                    'displayfunc' => 'nice_date',
                    'dbdatatype' => 'timestamp',
                ]
            ),
            new rb_column_option(
                'message_values',
                'checkbox',
                get_string('select', 'rb_source_totaramessages'),
                'base.notificationid',
                [
                    'displayfunc' => 'message_checkbox',
                    'extrafields' => [
                        'useridto' => 'msg.useridto',
                        'msgsubject' => 'msg.subject',
                    ],
                    'noexport' => true,
                    'nosort' => true,
                ]
            ),
            new rb_column_option(
                'message_values',
                'msgid',
                get_string('msgid', 'rb_source_totaramessages'),
                'base.notificationid',
                [
                    'nosort' => true,
                    'noexport' => true,
                    'hidden' => 1,
                    'displayfunc' => 'integer',
                ]
            ),
        ];

        // Add columns for the user the message was sent to.
        $this->add_core_user_columns($columnoptions, 'userto', 'userto', true);

        // Include some standard columns. Including the user that the message was sent from.
        $this->add_core_user_columns($columnoptions, 'auser', 'user', true);
        $this->add_totara_job_columns($columnoptions);

        return $columnoptions;
    }

    protected function define_filteroptions() {
        $filteroptions = [
            new rb_filter_option(
                'message_values',       // type
                'sent',                 // value
                get_string('datesent', 'rb_source_totaramessages'),            // label
                'date',                 // filtertype
                []                 // options
            ),
            new rb_filter_option(
                'message_values',
                'statement',
                get_string('statement', 'rb_source_totaramessages'),
                'text'
            ),
            new rb_filter_option(
                'message_values',
                'urgency',
                get_string('msgurgency', 'rb_source_totaramessages'),
                'select',
                [
                    'selectfunc' => 'message_urgency_list',
                    'attributes' => rb_filter_option::select_width_limiter(),
                ]
            ),
            new rb_filter_option(
                'message_values',
                'category',
                get_string('msgtype', 'rb_source_totaramessages'),
                'multicheck',
                [
                    'selectfunc' => 'message_type_list',
                ]
            ),
        ];

        // Add filters for the user the message was sent to.
        $this->add_core_user_filters($filteroptions, 'userto', true);

        // Include some standard filters. Including the user that the message was sent from.
        $this->add_core_user_filters($filteroptions, 'user', true);
        $this->add_totara_job_filters($filteroptions, 'msg', 'useridfrom'); // Note these relate to the sender.

        return $filteroptions;
    }

    protected function define_contentoptions() {
        $contentoptions = [];

        // Add the manager/position/organisation content options.
        $this->add_basic_user_content_options($contentoptions, 'userto');

        // Add the time created content option.
        $contentoptions[] = new rb_content_option(
            'date',
            get_string('timecreated', 'rb_source_user'),
            'base.timecreated'
        );

        return $contentoptions;
    }

    protected function define_paramoptions() {
        // this is where you set your hardcoded filters
        $paramoptions = [
            new rb_param_option(
                'userid',        // parameter name
                'msg.useridto',  // field
                'msg'            // joins
            ),
            new rb_param_option(
                'name',            // parameter name
                'processor.name',  // field
                'processor'        // joins
            ),
        ];

        return $paramoptions;
    }

    protected function define_defaultcolumns() {
        $defaultcolumns = [
            [
                'type' => 'user',
                'value' => 'fullname',
            ],
            [
                'type' => 'userto',
                'value' => 'fullname',
            ],
            [
                'type' => 'message_values',
                'value' => 'subject',
            ],
            [
                'type' => 'message_values',
                'value' => 'msgtype',
            ],
            [
                'type' => 'message_values',
                'value' => 'sent',
            ],
        ];
        return $defaultcolumns;
    }

    /**
     * @return array
     */
    protected function define_defaultfilters() {
        return [];
    }

    /**
     * @return rb_column[]
     */
    protected function define_requiredcolumns() {
        return [
            new rb_column(
                'message_values',
                'dismiss_link',
                get_string('dismissmsg', 'rb_source_totaramessages'),
                'base.notificationid',
                [
                    'displayfunc' => 'message_dismiss_link',
                    'joins' => ['processor'],
                    'extrafields' => [
                        'useridto' => 'msg.useridto',
                        'msgsubject' => 'msg.subject',
                        'processor_name' => 'processor.name'
                    ],
                    'required' => true,
                    'noexport' => true,
                    'nosort' => true,
                ]
            ),
        ];
    }

    //
    //
    // Source specific column display methods
    //
    //

    /**
     * Generate urgency icon link
     *
     * @param     $comp
     * @param     $row
     * @param int $export
     * @return mixed
     * @deprecated Since Totara 12.0
     */
    public function rb_display_urgency_link($comp, $row, $export = 0) {
        global $OUTPUT;
        debugging(
            'rb_source_totaramessages::rb_display_urgency_link has been deprecated since Totara 12.0. " .
            "Use totara_message\rb\display\message_urgency_link::display',
            DEBUG_DEVELOPER
        );

        $display = totara_message_urgency_text($row->message_values_urgency);
        if ($export) {
            return $display['text'];
        }

        return $OUTPUT->pix_icon(
            $display['icon'],
            $display['text'],
            'moodle',
            [
                'title' => $display['text'],
                'class' => 'iconsmall'
            ]
        );
    }

    /**
     * Generate urgency text
     *
     * @param $urgency
     * @param $row
     * @return mixed
     * @deprecated Since Totara 12.0
     */
    public function rb_display_urgency_text($urgency, $row) {
        debugging(
            'rb_source_totaramessages::rb_display_urgency_text has been deprecated since Totara 12.0. " . 
            "Use totara_message\rb\display\message_urgency_text::display',
            DEBUG_DEVELOPER
        );

        $display = totara_message_urgency_text($urgency);
        return $display['text'];
    }

    /**
     * Generate type icon link
     *
     * @param     $comp
     * @param     $row
     * @param int $export
     * @return mixed
     * @deprecated Since Totara 12.0
     */
    public function rb_display_msgtype_link($comp, $row, $export = 0) {
        debugging(
            'rb_source_totaramessages::rb_display_msgtype_link has been deprecated since Totara 12.0. " .
            "Use totara_message\rb\display\message_type_link::display',
            DEBUG_DEVELOPER
        );

        global $OUTPUT;
        $subject = format_string($row->msgsubject);
        $icon = !empty($row->msgicon) ? format_string($row->msgicon) : 'default';
        if ($export) {
            return $this->rb_display_msgtype_text($comp, $row);
        }
        return $OUTPUT->pix_icon("/msgicons/" . $icon, $subject, 'totara_core', ['title' => $subject]);
    }

    /**
     * Generate message type text
     *
     * @param $msgtype
     * @param $row
     * @return mixed
     * @deprecated Since Totara 12.0
     */
    public function rb_display_msgtype_text($msgtype, $row) {
        debugging(
            'rb_source_totaramessages::rb_display_msgtype_text has been deprecated since Totara 12.0. " .
            "Use totara_message\rb\display\message_type_text::display',
            DEBUG_DEVELOPER
        );

        $display = totara_message_msgtype_text($msgtype);
        return $display['text'];
    }

    /**
     * Display category
     *
     * @param string   $comp
     * @param stdClass $row
     * @return string
     * @deprecated Since Totara 12.0
     */
    public function rb_display_msgcategory_text($comp, $row) {
        global $TOTARA_MESSAGE_CATEGORIES;

        debugging(
            'rb_source_totaramessages::rb_display_msgcategory_text has been deprecated since Totara 12.0. " . 
            "Use totara_message\rb\display\message_category_text::display',
            DEBUG_DEVELOPER
        );

        if ($comp != '' && in_array($comp, $TOTARA_MESSAGE_CATEGORIES)) {
            return get_string($comp, 'totara_message');
        }

        return $comp;
    }

    /**
     * Generate dismiss message link
     *
     * @param $id
     * @param $row
     * @return string
     * @deprecated Since Totara 12.0
     */
    public function rb_display_dismiss_link($id, $row) {
        debugging(
            'rb_source_totaramessages::rb_display_dismiss_link has been deprecated since Totara 12.0. " . 
            "Use totara_message\rb\display\message_dismiss_link::display',
            DEBUG_DEVELOPER
        );

        $out = totara_message_dismiss_action($id);
        $out .= html_writer::checkbox(
            'totara_message_' . $id,
            $id,
            false,
            '',
            [
                'id' => 'totara_msgcbox_' . $id,
                'class' => "selectbox"
            ]
        );

        return $out;
    }

    /**
     * Generate message checkbox
     *
     * @param $id
     * @param $row
     * @return string
     * @deprecated Since Totara 12.0
     */
    public function rb_display_message_checkbox($id, $row) {
        debugging('rb_source_totaramessages::rb_display_message_checkbox has been deprecated since Totara 12.0', DEBUG_DEVELOPER);
        return html_writer::checkbox('totara_message_' . $id, $id, false, '', ['id' => 'totara_message', 'class' => "selectbox"]);
    }

    //
    //
    // Source specific filter display methods
    //
    //

    public function rb_filter_message_urgency_list() {
        global $CFG;
        require_once($CFG->dirroot . '/totara/message/messagelib.php');
        $urgencyselect = [];
        $urgencyselect[TOTARA_MSG_URGENCY_NORMAL] = get_string('urgencynormal', 'totara_message');
        $urgencyselect[TOTARA_MSG_URGENCY_URGENT] = get_string('urgencyurgent', 'totara_message');
        return $urgencyselect;
    }

    public function rb_filter_message_type_list() {
        global $OUTPUT;
        $out = [];

        $componentskeys = array_flip([
            'competency',
            'course',
            'evidence',
            'facetoface',
            'learningplan',
            'objective',
            'resource',
            'program'
        ]);

        if (advanced_feature::is_disabled('competencies')) {
            unset($componentskeys['competency']);
        }
        if (advanced_feature::is_disabled('learningplans')) {
            unset($componentskeys['learningplan']);
        }
        if (advanced_feature::is_disabled('programs') && advanced_feature::is_disabled('certifications')) {
            unset($componentskeys['program']);
        }
        if (advanced_feature::is_disabled('evidence')) {
            unset($componentskeys['evidence']);
        }
        $components = array_flip($componentskeys);

        foreach ($components as $type) {
            $typename = get_string($type, 'totara_message');
            $out[$type] = $OUTPUT->pix_icon('/msgicons/' . $type . '-regular', $typename, 'totara_core') . '&nbsp;' . $typename;
        }

        return $out;
    }

    /**
     * @return array
     */
    public function get_required_jss() {
        global $CFG;

        require_once($CFG->dirroot . '/totara/core/js/lib/setup.php');
        $code = [];
        $code[] = TOTARA_JS_DIALOG;
        local_js($code);

        $jsdetails = new stdClass();
        $jsdetails->initcall = 'M.totara_message.init';
        $jsdetails->jsmodule = [
            'name' => 'totara_message',
            'fullpath' => '/totara/message/module.js'
        ];

        $jsdetails->strings = [
            'block_totara_alerts' => ['reviewitems'],
        ];

        return [$jsdetails];
    }
}