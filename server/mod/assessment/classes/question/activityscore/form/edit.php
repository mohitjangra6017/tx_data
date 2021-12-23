<?php
/**
 * @copyright 2019 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Alex Glover <alex.glover@kineo.com>
 * @package mod_assessment
 * @subpackage assquestion_activityscore
 */

namespace mod_assessment\question\activityscore\form;

use mod_assessment\factory\assessment_factory;
use mod_assessment\model\assessment;
use mod_assessment\question\activityscore;
use mod_assessment\question\edit_form;

class edit extends edit_form
{

    public static function get_type(): string
    {
        return 'activityscore';
    }

    protected function get_activity_list(): array
    {
        global $DB;

        $dbman = $DB->get_manager();

        static $activitylist = null;
        if (!is_null($activitylist)) {
            return $activitylist;
        }

        $activitylist = [];
        $assessment = assessment_factory::fetch(['id' => $this->get_required_data('version')->get_assessmentid()]);

        $sql = "SELECT cm.id AS cmid, cm.instance, m.name AS modname
                FROM {course_modules} cm
                JOIN {modules} m ON m.id = cm.module
                JOIN {grade_items} gi ON gi.courseid = cm.course AND gi.itemmodule = m.name AND gi.iteminstance = cm.instance
                WHERE m.visible = 1 AND cm.course = :courseid AND cm.id <> :thiscmid
                ORDER BY cm.section ASC, cm.id ASC";
        $params = ['courseid' => $assessment->course, 'thiscmid' => $assessment->get_cmid()];
        $recs = $DB->get_records_sql($sql, $params);

        foreach ($recs as $rec) {
            if (!$dbman->table_exists($rec->modname)) {
                continue;
            }

            $instance = $DB->get_record($rec->modname, ['id' => $rec->instance]);
            if (isset($instance->name)) {
                $pluginname = get_string('pluginname', $rec->modname);
                $activitylist[$rec->cmid] = $instance->name . ' [' . $pluginname . ']';
            }
        }

        return $activitylist;
    }

    protected function add_js()
    {
        // No JS required
    }

    protected function add_question_fields()
    {
        $this->_form->addElement(
            'select',
            'config_activityids',
            get_string('activitylist', 'assquestion_activityscore'),
            $this->get_activity_list(),
            ['multiple' => 'multiple']
        );
        $this->_form->addHelpButton('config_activityids', 'activitylist', 'assquestion_activityscore');
        $this->freeze_field('config_activityids');
    }
}
