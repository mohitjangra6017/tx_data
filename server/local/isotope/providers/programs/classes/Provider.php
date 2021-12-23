<?php
/**
 * @copyright City & Guilds Kineo 2018
 * @author Ben Lobo <ben.lobo@kineo.com>
 */

namespace isotopeprovider_programs;

use coding_exception;
use Exception;
use isotopeprovider_programs\DataDecorators\CourseSet as CourseSetDecorator;
use isotopeprovider_programs\DataDecorators\DefaultImage;
use isotopeprovider_programs\DataSources\CourseSet;
use isotopeprovider_programs\DataSources\Program;
use isotopeprovider_programs\DataSources\Decorator;
use lang_string;
use local_isotope\Form\Checkbox;
use local_isotope\Form\CourseCategory;
use local_isotope\Form\Option;
use local_isotope\Form\Select;
use local_isotope\Form\Text;
use local_isotope\IsotopeProvider;

defined('MOODLE_INTERNAL') || die;


class Provider extends IsotopeProvider
{
    const COMPONENT = 'isotopeprovider_programs';

    protected $programId;

    public function __construct()
    {
        $this->setConfig(
            [
                'filter_courseset' => 1,
                'filter_completion_status' => 1,
                'filter_program' => 'basic',
                'display_course_image' => 'disabled',
                'category-path' => '',
                'category-path_op' => 0,
                'category-path_rec' => CourseCategory::EQUAL_TO,
                'display_completion_requirements' => 1,
                'no_content_text' => 'No courses to display',
            ]
        );

        $this->programId = 0;
    }

    /**
     * Return the human-friendly name of the provider.
     *
     * @return lang_string|string
     * @throws coding_exception
     */
    public function getDisplayName()
    {
        return get_string('title', self::COMPONENT);
    }

    /**
     * Return the short name of the plugin, used in config settings, and as a unique key.
     * @return string
     */
    public function getShortName()
    {
        return 'programs';
    }

    /**
     * @param array $config
     */
    public function setConfig(array $config)
    {
        // Legacy option check. Make sure to convert the old option values (0,1) into the new values.
        if (is_int($config['display_course_image'])) {
            if ($config['display_course_image'] == 1) {
                $config['display_course_image'] = 'foreground';
            } else {
                $config['display_course_image'] = 'disabled';
            }
        }
        parent::setConfig($config);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function load()
    {
        global $DB, $USER;

        $userProgs = (new Program())
            ->setFilter('userid', $USER->id)
            ->setFilter('category-path_op', $this->config['category-path_op'] == CourseCategory::EQUAL_TO)
            ->setFilter('category-path_rec', $this->config['category-path_rec'] == '1')
            ->setFilter('category-path', $this->config['category-path'])
            ->getData();

        // Determine the default program ID if one hasn't been explicitly set.
        if (!$this->programId) {
            if (is_array($userProgs) && !empty($userProgs)) {
                $this->programId = reset($userProgs)->id;
            }
        }

        if (!$this->programId || !isset($userProgs[$this->programId])) {
            return [];
        }

        // We only need to populate the courses in the active program, not all programs,
        // so we do this here and not when we generate the user's programs.

        $courseSetGroups = $userProgs[$this->programId]->coursesetgroups;

        $statuses = [
            'started' => 0,
            'notstarted' => 0,
            'completed' => 0,
        ];

        $total = 0;

        foreach ($courseSetGroups as $courseSetGroup) {
            foreach ($courseSetGroup as $courseSet) {
                $courseSetDecorator = new CourseSetDecorator();
                $courseSetDataSource = new Decorator(new CourseSet($DB, $courseSet->id), $courseSetDecorator);

                $courses = $courseSetDataSource
                    ->setFilter('userid', $USER->id)
                    ->getData();

                $total += count($courses);

                foreach ($courses as &$course) {
                    if (!isset($statuses[$course->status])) {
                        $statuses[$course->status] = 0;
                    }
                    $statuses[$course->status]++;
                }

                $courseSet->courses = $courses;
            }
        }

        if ($total > 0) {
            foreach ($statuses as $status => &$count) {
                $count = round(($count / $total) * 100);
            }
        }

        $activeCourseSetId = 0;
        if ($courseSetGroups) {
            // Find the first incomplete course set and set this as the active one.
            foreach ($courseSetGroups as $courseSetgroup) {
                foreach ($courseSetgroup as $courseSet) {
                    if ($courseSet->status != 'completed') {
                        $activeCourseSetId = $courseSet->id;
                        break 2;
                    }
                }
            }
        }

        return [
            'plugin' => self::COMPONENT,
            'statuses' => $statuses,
            'progs' => $userProgs,
            'coursesetGroups' => $courseSetGroups,
            'activeprogid' => $this->programId,
            'activecourseset' => $activeCourseSetId,
            'config' => $this->config,
        ];
    }

    /**
     * Returns the path to the main template to be loaded.
     * @return string
     */
    public function getTemplateFilename()
    {
        return 'programs.twig';
    }

    /**
     * @return array|Option[]
     * @throws coding_exception
     */
    public function getSettings()
    {
        $options = [];

        $name = 'filter_program';
        $option = new Select(
            $this->getShortName() . '_' . $name,
            get_string('config:filter_program', self::COMPONENT),
            [
                'basic' => get_string('config:radial_filter:basic', self::COMPONENT),
                'radial' => get_string('config:radial_filter:radial', self::COMPONENT),
            ]
        );
        $option->value = $this->config[$name];
        $options[] = $option;

        $name = 'display_course_image';
        $option = new Select(
            $this->getShortName() . '_' . $name,
            get_string('config:display_course_image', self::COMPONENT),
            [
                'disabled' => get_string('config:display_course_image:disabled', self::COMPONENT),
                'foreground' => get_string('config:display_course_image:foreground', self::COMPONENT),
                'background' => get_string('config:display_course_image:background', self::COMPONENT),
            ]
        );
        $options[] = $option;

        $checkboxes = [
            'filter_courseset' => get_string('config:filter_courseset', self::COMPONENT),
            'filter_completion_status' => get_string('config:filter_completion_status', self::COMPONENT),
            'category-path_rec' => get_string('config:display_programs_includesubcategories', self::COMPONENT),
            'display_completion_requirements' => get_string('config:display_completion_requirements', self::COMPONENT),
        ];

        foreach ($checkboxes as $name => $label) {
            $option = new Checkbox($this->getShortName() . '_' . $name, $label);
            $option->value = $this->config[$name];
            $options[] = $option;
        }

        $name = 'category-path';
        $option = new CourseCategory(
            $this->getShortName() . '_' . $name,
            get_string('config:display_programs_within_categories', self::COMPONENT)
        );
        $option->value = $this->config[$name];
        $options[] = $option;

        $name = 'no_content_text';
        $option = new Text($this->getShortName() . '_' . $name, get_string('config:no_content_text', self::COMPONENT), PARAM_TEXT);
        $option->value = $this->config[$name];
        $options[] = $option;

        return $options;
    }

    /**
     * Include and init any required JavaScript.
     */
    public function initJavaScript()
    {
        global $PAGE, $CFG;

        $selector = !empty($this->blockInstanceId) ? '#inst' . $this->blockInstanceId : '';
        $PAGE->requires->jquery();
        $PAGE->requires->js_init_call(
            'M.isotope_provider_programs.init',
            ['selector' => "{$selector} .programs"],
            false,
            [
                'name' => 'isotopeprovider_programs',
                'fullpath' => new \moodle_url(substr(dirname(__DIR__), strlen($CFG->dirroot)) . '/js/base.js'),
            ]
        );
        $PAGE->requires->strings_for_js(['error:unabletoloadprogramdata'], self::COMPONENT);
    }

    /**
     * @param $programId
     */
    public function setProgramId($programId)
    {
        $this->programId = $programId;
    }
}
