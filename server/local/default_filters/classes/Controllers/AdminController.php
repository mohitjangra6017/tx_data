<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_default_filters\Controllers;

use context;
use context_system;
use JsonSerializable;
use local_default_filters\Forms\DefaultSearchForm;
use moodle_url;
use reportbuilder;
use stdClass;
use totara_mvc\admin_controller;
use totara_mvc\viewable;

class AdminController extends admin_controller
{
    protected $admin_external_page_name = 'local_default_filters_settings';

    public function __construct()
    {
        global $CFG;

        require_once($CFG->dirroot . '/totara/reportbuilder/lib.php');
        require_once($CFG->dirroot . '/totara/reportbuilder/report_forms.php');

        parent::__construct();
    }

    protected function setup_context(): context
    {
        return context_system::instance();
    }

    /**
     * This is the default action and it can be overridden by the children.
     * If no action is passed to the render method this default action is called.
     * In this case it has to be defined in child classes.
     *
     * @return viewable|string|array|stdClass|JsonSerializable if it cannot be cast to a string the result will be json encoded
     */
    public function action()
    {
        global $OUTPUT, $DB, $SESSION;

        $reportId = optional_param('reportid', 0, PARAM_INT);
        if (!$reportId) {
            return $this->render(
                $OUTPUT->render_from_template(
                    'local_default_filters/filters',
                    [
                        'select' => $this->getReportSelector(),
                    ]
                )
            );
        }

        $report = reportbuilder::create($reportId);
        $report->include_js();
        $fields = $report->get_standard_filters();

        // Make sure to load the existing data into the session before loading the form, so it can set default values.
        $existing = $DB->get_record('local_default_filters', ['reportid' => $reportId]);
        if ($existing) {
            $SESSION->reportbuilder[$reportId] = unserialize($existing->data);
        }

        $form = new DefaultSearchForm(
            new moodle_url('/local/default_filters/index.php'),
            [
                'existing' => !!$existing,
                'fields' => $fields,
                'reportname' => $report->fullname,
                'reportid' => $reportId,
            ]
        );

        if ($form->is_cancelled()) {
            // Just redirect back to ourselves without the report ID.
            redirect($this->url);

        } else if ($data = $form->get_data()) {
            if (isset($data->delete)) {
                $DB->delete_records('local_default_filters', ['reportid' => $reportId]);
                unset($SESSION->reportbuilder[$reportId]);
                redirect($this->url, get_string('settings:deleted', 'local_default_filters'));
            }

            $record = new \stdClass();
            $record->reportid = $reportId;
            $record->data = [];

            foreach ($fields as $field) {
                $output = $field->check_data($data);
                if (!empty($output)) {
                    $record->data[$field->name] = $output;
                }
            }

            if ($existing) {
                $SESSION->reportbuilder[$reportId] = $record->data;
                $existing->data = serialize($record->data);
                $existing->timemodified = time();
                $DB->update_record('local_default_filters', $existing);
            } else {
                $record->timemodified = time();
                $record->data = serialize($record->data);
                $DB->insert_record('local_default_filters', $record);
            }
            redirect($this->url, get_string('settings:saved', 'local_default_filters'));
        }

        return $this->render(
            $OUTPUT->render_from_template(
                'local_default_filters/filters',
                [
                    'form' => $form->render(),
                ]
            )
        );
    }

    /**
     * @return string
     */
    private function getReportSelector(): string
    {
        global $DB, $OUTPUT;

        $sql = <<<SQL
SELECT id, fullname
FROM {report_builder} 
WHERE id IN (
  SELECT reportid FROM {report_builder_filters}
) 
ORDER BY fullname ASC
SQL;
        $filterableReports = $DB->get_records_sql_menu($sql);

        return $OUTPUT->single_select(
            new moodle_url('/local/default_filters/index.php'),
            'reportid',
            $filterableReports,
            '',
            ['' => get_string('settings:choose_report', 'local_default_filters')]
        );
    }

    private function render(string $view): string
    {
        global $OUTPUT;
        return $OUTPUT->header() . $view . $OUTPUT->footer();
    }
}