<?php

/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon.Thornett
 */

namespace local_email_log\rb\content;

use totara_reportbuilder\rb\content\base;
use totara_reportbuilder\rb\content\user_visibility;

class from_user_visibility extends base
{

    private $parent;

    public const TYPE = 'from_user_visibility_content';

    public function __construct($reportfor = null)
    {
        parent::__construct($reportfor);

        $this->parent = new user_visibility();
    }

    public function sql_restriction($field, $reportid)
    {
        return $this->parent->sql_restriction($field, $reportid);
    }

    public function text_restriction($title, $reportid) {
        return get_string('from_user_visibility', 'local_email_log');
    }

    public function form_template(&$mform, $reportid, $title)
    {
        $mform->addElement('header', 'from_user_visibility_header', get_string('from_user_visibility', 'local_email_log'));
        $mform->addHelpButton('user_visibility_header', 'user_visibility', 'totara_reportbuilder');
        $mform->setExpanded('from_user_visibility_header');

        $enable = \reportbuilder::get_setting($reportid, self::TYPE, 'enable');
        $mform->addElement('checkbox', 'from_user_visibility_enable', '', get_string('user_visibility_checkbox', 'totara_reportbuilder'));
        $mform->setDefault('from_user_visibility_enable', $enable);
        $mform->disabledIf('from_user_visibility_enable', 'contentenabled', 'eq', 0);    }

    public function form_process($reportid, $fromform)
    {
        $visibilityenable = $fromform->from_user_visibility_enable ?? 0;
        return \reportbuilder::update_setting($reportid, self::TYPE, 'enable', $visibilityenable);
    }
}
