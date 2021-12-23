<?php

namespace theme_kineo\Form;

defined('MOODLE_INTERNAL') || die;
global $CFG;
require_once $CFG->libdir . '/form/selectgroups.php';

class NullableSelectGroup extends \MoodleQuickForm_selectgroups
{
    public function exportValue(&$submitValues, $assoc = false)
    {
        $value = parent::exportValue($submitValues, $assoc);
        if (!is_array($value) && is_null($value)) {
            return [$this->getName() => []];
        }

        return $value;
    }

    public function toHtml()
    {
        if ($this->_flagFrozen) {
            return parent::toHtml();
        } else {
            return '<input' . $this->_getAttrString(
                    [
                        'type' => 'hidden',
                        'name' => $this->getName(),
                        'value' => '',
                    ]
                ) . ' />' . parent::toHtml();
        }
    }
}