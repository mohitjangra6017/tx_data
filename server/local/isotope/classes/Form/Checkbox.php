<?php
/**
 * @copyright City & Guilds Kineo 2018
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_isotope\Form;

use MoodleQuickForm;

class Checkbox extends Option
{
    public $type = 'advcheckbox';

    public function __construct($name, $label, array $options = ['0', '1'], $attributes = null)
    {
        parent::__construct($name, $label, $options, $attributes);
    }

    /**
     * @param MoodleQuickForm $form
     */
    public function extendForm(MoodleQuickForm $form)
    {
        $form->addElement(
            'advcheckbox',
            $this->name,
            $this->label,
            '',
            [],
            $this->options
        );
    }
}