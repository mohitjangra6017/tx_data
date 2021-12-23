<?php
/**
 * Plugin settings.
 *
 * @package   block_related_courses
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2017 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

defined('MOODLE_INTERNAL') || die;

use block_related_courses\Factory\CourseBasedFactory;
use block_related_courses\Factory\CourseTaggingFactory;
use block_related_courses\Factory\CourseSortFactory;
use block_related_courses\Model\CourseImageModel;

if ($ADMIN->fulltree) {

    $settings->add(
        new admin_setting_configselect(
            'block_related_courses/imagesource',
            get_string('settings:imagesource:label', 'block_related_courses'),
            get_string('settings:imagesource:desc', 'block_related_courses'),
            CourseImageModel::SOURCE_CATALOG,
            CourseImageModel::getSources()
        )
    );

    $settings->add(
        new admin_setting_configstoredfile(
            'block_related_courses/defaultimage',
            get_string('settings:defaultimage:label', 'block_related_courses'),
            get_string('settings:defaultimage:desc', 'block_related_courses'),
            'defaults',
            0,
            ['image']
        )
    );

    $settings->add(
        new admin_setting_configselect(
            'block_related_courses/view',
            get_string('settings:view', 'block_related_courses'),
            '',
            block_related_courses::VIEW_FULL,
            [
                block_related_courses::VIEW_FULL => get_string('settings:view:full', 'block_related_courses'),
                block_related_courses::VIEW_CAROUSEL => get_string('settings:view:carousel', 'block_related_courses'),
            ]
        )
    );

    $settings->add(
        new admin_setting_configtext(
            'block_related_courses/fullviewurl',
            get_string('settings:fullviewurl', 'block_related_courses'),
            '',
            '',
            PARAM_URL
        )
    );

    $settings->add(
        new admin_setting_configselect(
            'block_related_courses/display',
            get_string('settings:display', 'block_related_courses'),
            '',
            'block',
            [
                'block' => get_string('settings:display:block', 'block_related_courses'),
                'list' => get_string('settings:display:list', 'block_related_courses'),
            ]
        )
    );

    $settings->add(
        new admin_setting_configselect(
            'block_related_courses/based',
            get_string('settings:based', 'block_related_courses'),
            '',
            CourseBasedFactory::BASED_COMPLETED,
            [
                CourseBasedFactory::BASED_COMPLETED => get_string('settings:based:completed', 'block_related_courses'),
                CourseBasedFactory::BASED_CURRENT => get_string('settings:based:current', 'block_related_courses'),
            ]
        )
    );

    $settings->add(
        new admin_setting_configselect(
            'block_related_courses/tagging',
            get_string('settings:tagging', 'block_related_courses'),
            '',
            CourseTaggingFactory::TAGGED_TAG,
            [
                CourseTaggingFactory::TAGGED_TAG => get_string('settings:tagging:tag', 'block_related_courses'),
                CourseTaggingFactory::TAGGED_CUSTOMFIELD => get_string(
                    'settings:tagging:customfield',
                    'block_related_courses'
                ),
            ]
        )
    );

    $settings->add(
        new admin_setting_configselect(
            'block_related_courses/sorting',
            get_string('settings:sorting', 'block_related_courses'),
            '',
            CourseSortFactory::SORT_FULLNAME,
            [
                CourseSortFactory::SORT_FULLNAME => get_string('settings:sorting:fullname', 'block_related_courses'),
                CourseSortFactory::SORT_CREATEDTIME => get_string(
                    'settings:sorting:createdtime',
                    'block_related_courses'
                ),
                CourseSortFactory::SORT_POPULAR => get_string('settings:sorting:popular', 'block_related_courses'),
            ]
        )
    );

    $settings->add(
        new admin_setting_configtext(
            'block_related_courses/limit',
            get_string('settings:limit', 'block_related_courses'),
            '',
            '',
            PARAM_INT
        )
    );

}
