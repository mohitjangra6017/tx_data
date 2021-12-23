<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace theme_kineo\Settings;

use InvalidArgumentException;

class Dropdown extends Setting
{
    public const OPTIONS_KEY = 'dropdown_options';

    protected function init()
    {
        if (empty($this->options[self::OPTIONS_KEY])) {
            throw new InvalidArgumentException(
                sprintf("Required option '%s' is invalid for setting '%s'", self::OPTIONS_KEY, $this->identifier)
            );
        }

        // Keep track of where the default is in the array, so we can load the label later.
        $defaultIndex = array_search($this->default, $this->options[self::OPTIONS_KEY]);

        $this->options[self::OPTIONS_KEY] = array_map(
            function ($option) {
                return (object) [
                    'id' => $option,
                    'label' => get_string(
                        $this->getName()->get_identifier() . ':options:' . $option,
                        $this->getName()->get_component()
                    ),
                ];
            },
            $this->options[self::OPTIONS_KEY]
        );

        // Now we've loaded the strings, get the string for our default and set that as our label.
        $this->options[self::DEFAULT_LABEL_KEY] = $this->options[self::OPTIONS_KEY][$defaultIndex]->label;
    }
}