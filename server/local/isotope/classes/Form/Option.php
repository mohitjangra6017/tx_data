<?php
/**
 * @copyright City & Guilds Kineo 2017
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_isotope\Form;

use MoodleQuickForm;

abstract class Option
{
    public $name;
    public $type;
    public $label;
    public $options;
    public $attributes;
    public $value;

    public function __construct($name, $label, $options = [], $attributes = null)
    {
        $this->name = 'config_' . $name;
        $this->label = $label;
        $this->options = $options;
        $this->attributes = $attributes;
    }

    /**
     * @param MoodleQuickForm $form
     * @return mixed
     */
    abstract public function extendForm(MoodleQuickForm $form);
}