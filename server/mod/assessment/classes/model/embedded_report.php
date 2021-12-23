<?php
/**
 * @copyright   City & Guilds Kineo 2017
 * @author      Steven Hughes <steven.hughes@kineo.com>
 */

namespace mod_assessment\model;

use reportbuilder;

class embedded_report
{

    /**
     * @var reportbuilder
     */
    protected reportbuilder $report;

    /**
     * @var string
     */
    public string $table = '';

    /**
     * @var string
     */
    public string $heading = '';

    /**
     * @var string
     */
    public string $recordsshown = '';

    /**
     * @var string
     */
    public string $search = '';

    /**
     * @var string
     */
    public string $sidebarsearch = '';

    /**
     * @var string
     */
    public string $debug;

    /**
     * embedded_report constructor.
     *
     * @param $heading
     * @param reportbuilder $report
     */
    public function __construct($heading, reportbuilder $report)
    {
        $this->report = $report;
        $this->heading = $heading;
        $this->table = $this->get_output_string($this->report, 'display_table');
        $this->recordsshown = $this->get_records_shown();
        $this->search = $this->get_output_string($this->report, 'display_search');
        $this->sidebarsearch = $this->get_output_string($this->report, 'display_sidebar_search');
        $this->debug = optional_param('debug', false, PARAM_BOOL) ? $this->get_output_string($this->report, 'debug') : '';
    }

    /**
     * @param $object
     * @param $method
     * @param array $args
     * @return string
     */
    protected function get_output_string($object, $method, $args = []): string
    {
        ob_start();
        call_user_func_array([$object, $method], $args);
        return ob_get_clean();
    }

    /**
     * @return string
     */
    protected function get_records_shown(): string
    {
        $filteredcount = $this->report->get_filtered_count();

        $candisplay = true;
        if (method_exists($this->report, 'can_display_total_count')) {
            $candisplay = $this->report->can_display_total_count();
        }
        $fullcount = $candisplay ? $this->report->get_full_count() : 0;

        // Get pluralisation right.
        $resultstr = $fullcount == 1 ? 'record' : 'records';

        if ($filteredcount == $fullcount) {
            $recordsshown = get_string('x' . $resultstr, 'totara_reportbuilder', $fullcount);
        } else {
            $a = new \stdClass();
            $a->filtered = $filteredcount;
            $a->unfiltered = $fullcount;
            $recordsshown = get_string('xofy' . $resultstr, 'totara_reportbuilder', $a);
        }

        return $recordsshown;
    }

    /**
     * @return array
     */
    public function to_array(): array
    {
        return get_object_vars($this);
    }
}