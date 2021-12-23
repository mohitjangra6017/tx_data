<?php
/**
 * Created by PhpStorm.
 * User: Gavin McMaster
 * Date: 08/06/20
 * Time: 11:57
 *
 * Assessment backup task that provides all the settings and steps to perform one
 * complete backup of the activity
 * @package   mod_assessment
 *
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/assessment/backup/moodle2/backup_assessment_stepslib.php');

class backup_assessment_activity_task extends backup_activity_task
{
    /**
     * Define (add) particular settings this activity can have
     */
    protected function define_my_settings()
    {
        // No particular settings for this activity.
    }

    /**
     * Define (add) particular steps this activity can have
     */
    protected function define_my_steps()
    {
        $this->add_step(new backup_assessment_activity_structure_step('assessment_structure', 'assessment.xml'));
    }

    /**
     * Encodes URLs to the view.php script
     *
     * @param string $content some HTML text that eventually contains URLs to the activity instance scripts
     * @return string the content with the URLs encoded
     */
    static public function encode_content_links($content, backup_task $task = null) {

        if (!self::has_scripts_in_content($content, 'mod/assessment', ['view.php'])) {
            // No scripts present in the content, simply continue.
            return $content;
        }

        if (empty($task)) {
            $content = self::encode_content_link_basic_id($content, "/mod/assessment/view.php?id=", 'ASSESSVIEWBYID');
        } else {
            // OK we have a valid task, we can translate just those links belonging to content that is being backed up.
            foreach ($task->get_tasks_of_type_in_plan('backup_assessment_activity_task') as $task) {
                /** @var backup_assessment_activity_task $content */
                $content = self::encode_content_link_basic_id($content, "/mod/assessment/view.php?id=", 'ASSESSVIEWBYID', $task->get_moduleid());
            }
        }

        return $content;
    }
}
