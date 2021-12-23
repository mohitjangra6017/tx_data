<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace theme_kineo\Settings;


use InvalidArgumentException;

class Editor extends Setting
{
    public const COMPONENT_KEY = 'component';
    public const AREA_KEY = 'area';
    public const TYPE_KEY = 'type';

    protected function init()
    {
        $required = [self::COMPONENT_KEY, self::AREA_KEY, self::TYPE_KEY];

        if ($missing = array_diff($required, array_keys($this->options))) {
            throw new InvalidArgumentException(
                sprintf("Missing required option(s) '%s' for setting '%s'", implode(', ', $missing), $this->identifier)
            );
        }

        if (!in_array($this->options[self::TYPE_KEY], [FORMAT_HTML, FORMAT_PLAIN, FORMAT_JSON_EDITOR])) {
            $this->options[self::TYPE_KEY] = FORMAT_HTML;
        }
    }
}