<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Simon Adams <simon.adams@kineo.com>
 */

namespace local_isotope\Form;


use MoodleQuickForm;

class Tag extends Option
{
    public $type = 'tags';

    public function __construct(
        $name,
        $label,
        $options = [],
        $attributes = null
    ) {
        parent::__construct($name, $label, $options, $attributes);
    }

    /**
     * @param MoodleQuickForm $form
     * @return mixed|void
     */
    public function extendForm(MoodleQuickForm $form)
    {
        $form->addElement($this->type, $this->name, $this->label, $this->options);
    }
}