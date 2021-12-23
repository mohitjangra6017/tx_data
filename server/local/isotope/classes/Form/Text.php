<?php
/**
 * @copyright City & Guilds Kineo 2018
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_isotope\Form;

use MoodleQuickForm;

class Text extends Option
{
    public $paramType;

    public function __construct($name, $label, $paramType, array $options = [], $attributes = null)
    {
        $this->paramType = $paramType;
        parent::__construct($name, $label, $options, $attributes);
    }

    /**
     * @param MoodleQuickForm $form
     * @return mixed|void
     */
    public function extendForm(MoodleQuickForm $form)
    {
        $form->addElement('text', $this->name, $this->label, $this->attributes);
        $form->setType($this->name, $this->paramType);
    }

}