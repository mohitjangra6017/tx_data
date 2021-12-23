<?php
/**
 * Image Decorator
 *
 * @package   isotopeprovider_required_learning
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2018 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace isotopeprovider_required_learning\Decorator;

use coding_exception;
use context_program;
use core_course\theme\file\course_image;
use file_exception;
use file_storage;
use moodle_url;
use totara_certification\theme\file\certification_image;
use totara_program\theme\file\program_image;

defined('MOODLE_INTERNAL') || die;

class ImageDecorator implements DecoratorInterface
{
    /**
     * @var file_storage
     */
    protected $fileStorage;

    private array $config;

    /**
     * ImageDecorator constructor.
     * @param file_storage $fileStorage
     */
    public function __construct(file_storage $fileStorage, array $config)
    {
        $this->fileStorage = $fileStorage;
        $this->config = $config;
    }

    /**
     * @param object $item
     * @return object
     * @throws coding_exception
     */
    public function decorate(object $item): object
    {
        global $PAGE;

        if (empty($this->config['display_course_image']) || $this->config['display_course_image'] === 'disabled') {
            return $item;
        }

        $programContext = context_program::instance($item->id);
        $fs = get_file_storage();
        $imageFiles = $fs->get_area_files($programContext->id, 'totara_program', 'images', $item->id, "timemodified DESC", false);
        $imageFile = reset($imageFiles);
        if ($imageFile) {
            $item->image = moodle_url::make_pluginfile_url(
                $programContext->id,
                'totara_program',
                'images',
                $item->id,
                '/',
                $imageFile->get_filename(),
                false
            )->out(false);

            return $item;
        }

        if ($item->certifid) {
            $themeImage = new certification_image($PAGE->theme);
        } else {
            $themeImage = new program_image($PAGE->theme);
        }

        $url = $themeImage->get_current_or_default_url();
        $item->image = $url;

        return $item;
    }
}