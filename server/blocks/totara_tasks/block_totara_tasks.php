<?PHP //$Id$
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2010 onwards Totara Learning Solutions LTD
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
 * @author Piers Harding <piers@catalyst.net.nz>
 * @author Alastair Munro <alastair.munro@totaralms.com>
 * @package totara
 * @subpackage blocks_totara_tasks
 */

defined('MOODLE_INTERNAL') || die();

class block_totara_tasks extends block_base {
    /**
     * @return void
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_totara_tasks');
    }

    /**
     *  Only one instance of this block is required.
     * @return false
     */
    public function instance_allow_multiple() {
      return false;
    }

    /**
     * Label and button values can be set in admin.
     * @return bool
     */
    public function has_config() {
      return true;
    }

    /**
     * @return stdClass|null
     */
    public function get_content() {
        global $CFG, $FULLME, $DB, $OUTPUT, $PAGE;

        // Cache block contents
        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();
        // initialise jquery and confirm requirements
        require_once($CFG->dirroot.'/totara/message/messagelib.php');
        require_once($CFG->dirroot.'/totara/reportbuilder/lib.php');
        require_once($CFG->dirroot.'/totara/core/js/lib/setup.php');

        $code = array();
        $code[] = TOTARA_JS_DIALOG;
        local_js($code);
        $PAGE->requires->js_init_call('M.totara_message.init');

        // Just get the tasks for this user.
        $total = tm_messages_count('totara_task');
        $this->msgs = tm_messages_get('totara_task', 'timecreated DESC ');
        $count = is_array($this->msgs) ? count($this->msgs) : 0;

        $this->title = get_string('tasks', 'block_totara_tasks');

        if (empty($this->instance)) {
            return $this->content;
        }

        $output = '';
        if (!empty($this->msgs)) {
            $output .= html_writer::tag('p', get_string('showingxofx', 'block_totara_tasks', array('count' => $count, 'total' => $total)));
            $tasks = array();

            $processor_id = $DB->get_field('message_processors', 'id', ['name' => 'totara_task']);

            foreach ($this->msgs as $msg) {
                $task = '';

                $msgmeta = $DB->get_record('message_metadata', ['notificationid' => $msg->id, 'processorid' => $processor_id], '*', MUST_EXIST);
                $msgacceptdata = totara_message_eventdata($msg->id, 'onaccept', $msgmeta);
                $msgrejectdata = totara_message_eventdata($msg->id, 'onreject', $msgmeta);
                $msginfodata = totara_message_eventdata($msg->id, 'oninfo', $msgmeta);

                // User name + link.
                $userfrom_link = $CFG->wwwroot.'/user/profile.php?id='.$msg->useridfrom;
                $from = $DB->get_record('user', array('id' => $msg->useridfrom));
                $fromname = fullname($from);

                // Message creation time.
                $when = userdate($msg->timecreated, get_string('strftimedate', 'langconfig'));

                // Statement - multipart: user + statment + object.
                $cssclass = totara_message_cssclass($msg->msgtype);
                $msglink = !empty($msg->contexturl) ? $msg->contexturl : '';

                // Status icon.
                $task .= $OUTPUT->pix_icon('msgicons/' . $msg->icon, '', 'totara_core',
                    array('class'=>"msgicon {$cssclass}", 'title' => format_string($msg->subject)));
                $task .= ' ';

                // Details.
                $text = format_string($msg->subject ? $msg->subject : $msg->fullmessage);
                if (!empty($msglink)) {
                    $url = new moodle_url($msglink);
                    $attributes = array('href' => $url);
                    $task .= html_writer::tag('a', $text, $attributes);
                } else {
                    $task .= $text;
                }

                // Info icon/dialog.
                $detailbuttons = array();
                // Add 'accept' button.
                if (!empty($msgacceptdata) && count((array)$msgacceptdata)) {
                    $btn = new stdClass();
                    $btn->text = !empty($msgacceptdata->acceptbutton) ?
                        $msgacceptdata->acceptbutton : get_string('onaccept', 'block_totara_tasks');
                    $btn->action = "{$CFG->wwwroot}/totara/message/accept.php?id={$msg->id}&processor_type=totara_task";
                    $btn->redirect = !empty($msgacceptdata->data['redirect']) ?
                        $msgacceptdata->data['redirect'] : $FULLME;
                    $detailbuttons[] = $btn;
                }
                // Add 'reject' button.
                if (!empty($msgrejectdata) && count((array)$msgrejectdata)) {
                    $btn = new stdClass();
                    $btn->text = !empty($msgrejectdata->rejectbutton) ?
                        $msgrejectdata->rejectbutton : get_string('onreject', 'block_totara_tasks');
                    $btn->action = "{$CFG->wwwroot}/totara/message/reject.php?id={$msg->id}&processor_type=totara_task";
                    $btn->redirect = !empty($msgrejectdata->data['redirect']) ?
                        $msgrejectdata->data['redirect'] : $FULLME;
                    $detailbuttons[] = $btn;
                }
                // Add 'info' button.
                if (!empty($msginfodata) && count((array)$msginfodata)) {
                    $btn = new stdClass();
                    $btn->text = !empty($msginfodata->infobutton) ?
                        $msginfodata->infobutton : get_string('oninfo', 'block_totara_tasks');
                    $btn->action = "{$CFG->wwwroot}/totara/message/link.php?id={$msg->id}&processor_type=totara_task";
                    $btn->redirect = $msginfodata->data['redirect'];
                    $detailbuttons[] = $btn;
                }
                $moreinfotext = get_string('clickformoreinfo', 'block_totara_tasks');
                $icon = $OUTPUT->pix_icon('i/info', $moreinfotext, 'moodle', array('class'=>'msgicon', 'title' => $moreinfotext, 'alt' => $moreinfotext));
                $detailjs = totara_message_alert_popup($msg->id, $detailbuttons, 'detailtask', 'totara_task');
                $url = new moodle_url($msglink);
                $attributes = array('href' => $url, 'id' => 'detailtask'.$msg->id.'-dialog', 'class' => 'information');
                $task .= html_writer::tag('a', $icon, $attributes) . $detailjs;
                $tasks[] = $task;
            }
            $output .= html_writer::alist($tasks, array('class' => 'list'));
        } elseif (!empty($CFG->block_totara_tasks_showempty)) {
            $output = html_writer::tag('p', get_string('notasks', 'block_totara_tasks'));
        }

        $this->content->text = $output;
        if (!empty($this->msgs)) {
            $url = new moodle_url('/totara/message/tasks.php', array('sesskey' => sesskey()));
            $link = html_writer::link($url, get_string('viewallnot', 'block_totara_tasks'));
            $this->content->footer = html_writer::tag('div', $link, array('class' => 'viewall'));
        }
        return $this->content;
    }
}
