<?php
/**
 * Background Options
 *
 * @package   block_banner
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2019 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace block_banner;

use coding_exception;

defined('MOODLE_INTERNAL') || die;

class BackgroundOptions
{
    /**
     * @return array
     * @throws coding_exception
     */
    public function repeat(): array
    {
        return [
            'repeat' => get_string('repeat', 'block_banner'),
            'repeat-x' => get_string('repeat-x', 'block_banner'),
            'repeat-y' => get_string('repeat-y', 'block_banner'),
            'no-repeat' => get_string('no-repeat', 'block_banner'),
        ];
    }

    /**
     * @return array
     * @throws coding_exception
     */
    public function position(): array
    {
        return [
            'top' => get_string('top', 'block_banner'),
            'bottom' => get_string('bottom', 'block_banner'),
            'left' => get_string('left', 'block_banner'),
            'right' => get_string('right', 'block_banner'),
            'center' => get_string('center', 'block_banner'),
            'custom' => get_string('custom', 'block_banner'),
        ];
    }

    /**
     * @return array
     * @throws coding_exception
     */
    public function size(): array
    {
        return [
            'auto' => get_string('auto', 'block_banner'),
            'cover' => get_string('cover', 'block_banner'),
            'contain' => get_string('contain', 'block_banner'),
            'custom' => get_string('custom', 'block_banner'),
        ];
    }

    /**
     * @return array
     * @throws coding_exception
     */
    public function attachment(): array
    {
        return [
            'scroll' => get_string('scroll', 'block_banner'),
            'fixed' => get_string('fixed', 'block_banner'),
        ];
    }
}