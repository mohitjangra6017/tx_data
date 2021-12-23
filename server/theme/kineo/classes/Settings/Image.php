<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace theme_kineo\Settings;

class Image extends Setting
{
    public const IN_CSS_KEY = 'in_css';

    public const IS_PUBLIC_KEY = 'is_public';

    protected function init()
    {
        // Properly coerce these options into booleans and apply defaults if they do not exist.
        $defaults = [
            self::IS_PUBLIC_KEY => false,
            self::IN_CSS_KEY => false,
            self::SHOW_DEFAULT_KEY => false,
            self::SHOW_IDENTIFIER_KEY => false,
        ];

        foreach ($defaults as $key => $value) {
            if (!isset($this->options[$key])) {
                $this->options[$key] = $value;
            }
            $this->options[$key] = (bool)$this->options[$key];
        }
        $this->identifier = str_replace('-', '_', $this->identifier);
    }
}