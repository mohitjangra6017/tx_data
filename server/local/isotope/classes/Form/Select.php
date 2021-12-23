<?php
/**
 * @copyright City & Guilds Kineo 2018
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_isotope\Form;

use MoodleQuickForm;

class Select extends Option
{
    public $type = 'select';

    /**
     * @param MoodleQuickForm $form
     * @return mixed|void
     */
    public function extendForm(MoodleQuickForm $form)
    {
        $form->addElement(
            'select',
            $this->name,
            $this->label,
            $this->options,
            $this->attributes
        );
    }
}