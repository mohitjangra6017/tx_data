<?php

/**
 * @copyright City & Guilds Kineo 2019
 * @author Simon Adams <simon.adams@kineo.com>
 */

namespace local_leaderboard\rb\content;

use coding_exception;
use html_writer;
use ReflectionClass;
use ReflectionException;
use reportbuilder;
use totara_reportbuilder\rb\content\base;

defined('MOODLE_INTERNAL') || die;

class leaderboard_date_content extends base
{
    /**
     * @var string
     */
    private $type = '';

    /**
     * leaderboard_date_content constructor.
     * Named to not collide with core 'date_content' class
     * @param null $reportfor
     * @throws ReflectionException
     */
    public function __construct($reportfor = null)
    {
        $this->type = (new ReflectionClass($this))->getShortName();
        parent::__construct($reportfor);
    }

    /**
     * @param $field
     * @param $reportid
     * @return array
     */
    public function sql_restriction($field, $reportid)
    {
        $settings = reportbuilder::get_all_settings($reportid, $this->type);

        if (!$settings['enable']) {
            return ['1=1', []];
        }

        switch ($settings['daterange']) {
            case 'lastmonth' :

                $firstOfLastMonth = strtotime('midnight first day of last month');
                $lastOfLastMonth = strtotime('midnight last day of last month');
                return ["({$field} < {$lastOfLastMonth} AND {$field} > {$firstOfLastMonth})", []];

            default :
                return ['1=0', []];
        }
    }

    /**
     * @param $title
     * @param $reportid
     */
    public function text_restriction($title, $reportid)
    {
        // intentionally blank
    }

    /**
     * @param $form
     * @param $reportid
     * @param $title
     * @throws coding_exception
     */
    public function form_template(&$form, $reportid, $title)
    {
        $enable = reportbuilder::get_setting($reportid, $this->type, 'enable');
        $dateSetting = reportbuilder::get_setting($reportid, $this->type, 'daterange');
        $lastmonthStr = get_string('lastmonth', 'rb_source_leaderboard', date("M-Y", strtotime('-1 month')));


        $form->addElement(
            'header',
            'date_header',
            get_string(
                'showbyx',
                'totara_reportbuilder',
                lcfirst($title)
            )
        );

        $form->setExpanded('date_header');
        $form->addElement(
            'checkbox',
            'date_enable',
            '',
            get_string(
                'showbasedonx',
                'totara_reportbuilder',
                lcfirst($title)
            )
        );

        $form->setDefault('date_enable', $enable);
        $form->disabledIf('date_enable', 'contentenabled', 'eq', 0);
        $radiogroup = [];

        $radiogroup[] =& $form->createElement(
            'radio',
            'score_dates',
            '',
            $lastmonthStr,
            'lastmonth'
        );

        $form->addGroup(
            $radiogroup,
            'score_dates_group',
            get_string('includerecordsfrom', 'totara_reportbuilder'),
            html_writer::empty_tag('br'),
            false
        );

        $form->setDefault('score_dates', $dateSetting);
        $form->disabledIf('score_dates_group', 'contentenabled', 'eq', 0);
        $form->disabledIf('score_dates_group', 'date_enable', 'notchecked');
        $form->addHelpButton('date_header', 'localleaderboards', 'rb_source_leaderboard');
    }

    /**
     * @param $reportid
     * @param $fromform
     * @return bool
     */
    public function form_process($reportid, $fromform)
    {
        $status = true;

        $enable = (isset($fromform->date_enable) && $fromform->date_enable) ? 1 : 0;
        $when = isset($fromform->score_dates) ? $fromform->score_dates : 0;

        $status = $status && reportbuilder::update_setting($reportid, $this->type, 'enable', $enable);
        $status = $status && reportbuilder::update_setting($reportid, $this->type, 'daterange', $when);

        return $status;
    }
}