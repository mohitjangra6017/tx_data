<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace theme_kineo\Settings;


class Colour extends Setting
{
    public const IS_CORE_KEY = 'is_core';

    protected function init()
    {
        // Properly coerce this option into a boolean. Default it false.
        if (empty($this->options[self::IS_CORE_KEY])) {
            $this->options[self::IS_CORE_KEY] = false;
        }
    }
}