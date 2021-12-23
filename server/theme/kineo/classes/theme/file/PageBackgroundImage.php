<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace theme_kineo\theme\file;

use core\files\type\file_type;
use core\files\type\web_image;
use core\theme\file\theme_file;
use theme_config;

class PageBackgroundImage extends theme_file
{
    /**
     * theme_file constructor.
     *
     * @param theme_config|null $theme_config
     */
    public function __construct(?theme_config $theme_config = null)
    {
        parent::__construct($theme_config);
        $this->type = new web_image();
    }

    /**
     * Get the ID of the file currently being used by the system
     * that is being overwritten.
     * For example:
     *    For $OUTPUT->image_url('filename', 'component') the ID
     *    would be 'component/filename'.
     *
     * @return string
     */
    static public function get_id(): string
    {
        return 'theme_kineo/pagebackgroundimage';
    }

    /**
     * @return string
     */
    public function get_component(): string
    {
        return 'theme_kineo';
    }

    /**
     * @return string
     */
    public function get_area(): string
    {
        return 'pagebackgroundimage';
    }

    /**
     * Get a unique key to map to theme categories.
     *
     * @return string
     */
    public function get_ui_key(): string
    {
        return 'pagebackgroundimage';
    }

    /**
     * Get the category to which this must be merged.
     *
     * @return string
     */
    public function get_ui_category(): string
    {
        return 'brand';
    }

    /**
     * Get the type of file.
     *
     * @return file_type
     */
    public function get_type(): file_type
    {
        return $this->type;
    }

    public function has_default(): bool
    {
        return false;
    }

}