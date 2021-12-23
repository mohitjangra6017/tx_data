<?php
/**
 * Block instance configuration
 *
 * @package   block_related_courses
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2016 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

use block_related_courses\Factory\CourseBasedFactory;
use block_related_courses\Factory\CourseSortFactory;
use block_related_courses\Factory\CourseTaggingFactory;

defined('MOODLE_INTERNAL') || die;

class block_related_courses_edit_form extends block_edit_form
{
    /**
     * @param MoodleQuickForm $mform
     * @throws coding_exception
     */
    protected function specific_definition($mform)
    {
        // Fields for editing HTML block title and contents.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $mform->addElement(
            'select',
            'config_view',
            get_string('settings:view', 'block_related_courses'),
            [
                block_related_courses::VIEW_FULL => get_string('settings:view:full', 'block_related_courses'),
                block_related_courses::VIEW_CAROUSEL => get_string('settings:view:carousel', 'block_related_courses'),
            ]
        );
        if ($default = get_config('block_related_courses', 'view')) {
            $mform->setDefault('config_view', $default);
        }

        $mform->addElement('text', 'config_fullviewurl', get_string('settings:fullviewurl', 'block_related_courses'));
        $mform->disabledIf('config_fullviewurl', 'config_view', 'eq', block_related_courses::VIEW_FULL);
        $mform->setType('config_fullviewurl', PARAM_URL);
        if ($default = get_config('block_related_courses', 'fullviewurl')) {
            $mform->setDefault('config_fullviewurl', $default);
        }

        $mform->addElement(
            'select',
            'config_display',
            get_string('settings:display', 'block_related_courses'),
            [
                'block' => get_string('settings:display:block', 'block_related_courses'),
                'list' => get_string('settings:display:list', 'block_related_courses'),
            ]
        );
        if ($default = get_config('block_related_courses', 'display')) {
            $mform->setDefault('config_display', $default);
        }

        $mform->addElement(
            'select',
            'config_based',
            get_string('settings:based', 'block_related_courses'),
            [
                CourseBasedFactory::BASED_COMPLETED => get_string('settings:based:completed', 'block_related_courses'),
                CourseBasedFactory::BASED_CURRENT => get_string('settings:based:current', 'block_related_courses'),
            ]
        );
        if ($default = get_config('block_related_courses', 'based')) {
            $mform->setDefault('config_based', $default);
        }

        $mform->addElement(
            'select',
            'config_tagging',
            get_string('settings:tagging', 'block_related_courses'),
            [
                CourseTaggingFactory::TAGGED_TAG => get_string('settings:tagging:tag', 'block_related_courses'),
                CourseTaggingFactory::TAGGED_CUSTOMFIELD => get_string(
                    'settings:tagging:customfield',
                    'block_related_courses'
                ),
            ]
        );
        if ($default = get_config('block_related_courses', 'tagging')) {
            $mform->setDefault('config_tagging', $default);
        }

        $mform->addElement(
            'select',
            'config_sorting',
            get_string('settings:sorting', 'block_related_courses'),
            [
                CourseSortFactory::SORT_FULLNAME => get_string('settings:sorting:fullname', 'block_related_courses'),
                CourseSortFactory::SORT_CREATEDTIME => get_string(
                    'settings:sorting:createdtime',
                    'block_related_courses'
                ),
                CourseSortFactory::SORT_POPULAR => get_string('settings:sorting:popular', 'block_related_courses'),
            ]
        );
        if ($default = get_config('block_related_courses', 'sorting')) {
            $mform->setDefault('config_sorting', $default);
        }

        $mform->addElement('text', 'config_limit', get_string('settings:limit', 'block_related_courses'));
        if ($default = get_config('block_related_courses', 'limit')) {
            $mform->setDefault('config_limit', $default);
        }
        $mform->setType('config_limit', PARAM_TEXT);
    }

    /**
     * @param array $data
     * @param array $files
     * @return array|void
     * @throws coding_exception
     */
    public function validation($data, $files)
    {
        $errors = parent::validation($data, $files);

        if (!empty($data['config_limit']) && !is_number($data['config_limit'])) {
            $errors['config_limit'] = get_string('error:limit', 'block_related_courses');
        }

        return $errors;
    }
}