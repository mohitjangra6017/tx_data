<?php
/**
 * User: Jo Jones (Jo.Jones@kineo.com)
 * Date: 14/05/2021
 * Time: 21:25
 */

namespace local_default_filters\Forms;


use moodleform;

defined('MOODLE_INTERNAL') || die;

class DefaultSearchForm extends moodleform
{

    public function definition()
    {
        $form = $this->_form;

        $fields = $this->_customdata['fields'];
        $existing = $this->_customdata['existing'];

        $form->disable_form_change_checker();

        if ($fields && is_array($fields) && count($fields) > 0) {
            $form->addElement(
                'header',
                'newfilterstandard',
                get_string('settings:setdefaults', 'local_default_filters', $this->_customdata['reportname'])
            );
            $form->addElement('hidden', 'reportid', $this->_customdata['reportid']);
            $form->setType('reportid', PARAM_INT);

            foreach ($fields as $ft) {
                $ft->advanced = 0;
                $ft->setupForm($form);
            }

            $submitGroup = [];
            $submitGroup[] =& $form->createElement(
                'submit',
                'save',
                get_string('settings:save', 'local_default_filters')
            );

            if ($existing) {
                $submitGroup[] =& $form->createElement(
                    'submit',
                    'delete',
                    get_string('settings:delete', 'local_default_filters')
                );
            }

            $submitGroup[] =& $form->createElement(
                'cancel',
                'back',
                get_string('settings:back', 'local_default_filters')
            );

            $form->addGroup($submitGroup, null, '&nbsp;', ' &nbsp; ');
        }
    }

    public function definition_after_data()
    {
        $form = $this->_form;
        $fields = $this->_customdata['fields'];

        if ($fields && is_array($fields) && count($fields) > 0) {
            foreach ($fields as $ft) {
                if (method_exists($ft, 'definition_after_data')) {
                    $ft->definition_after_data($form);
                }
            }
        }
    }
}