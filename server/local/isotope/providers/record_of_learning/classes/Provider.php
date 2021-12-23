<?php
/**
 * @copyright City & Guilds Kineo 2018
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace isotopeprovider_record_of_learning;

use coding_exception;
use isotopeprovider_record_of_learning\DataDecorators\Course as CourseDecorator;
use isotopeprovider_record_of_learning\DataDecorators\Program as ProgramDecorator;
use isotopeprovider_record_of_learning\DataSources\Certification;
use isotopeprovider_record_of_learning\DataSources\Course;
use isotopeprovider_record_of_learning\DataSources\Program;
use isotopeprovider_record_of_learning\Repositories\ProgramCompletion;
use lang_string;
use local_isotope\Data\CompositeSource;
use local_isotope\Data\DecoratorSource;
use local_isotope\Form\Checkbox;
use local_isotope\Form\CourseCategory;
use local_isotope\Form\Option;
use local_isotope\Form\Select;
use local_isotope\Form\Tag;
use local_isotope\Form\Text;
use local_isotope\IsotopeProvider;
use moodle_exception;
use moodle_url;
use stdClass;

defined('MOODLE_INTERNAL') || die;

global $CFG;

require_once($CFG->dirroot . '/totara/certification/lib.php');

class Provider extends IsotopeProvider
{
    const COMPONENT = 'isotopeprovider_record_of_learning';

    public function __construct()
    {
        $this->setConfig(
            [
                'hide_required' => 0,
                'link_single_prog_courses' => 0,
                'hide_prog_courses' => 0,
                'filter_required' => 1,
                'filter_type' => 1,
                'filter_completion_status' => 'basic',
                'display_course_image' => 'disabled',
                'use_course_summary_image' => 0,
                'display_learning_type' => 1,
                'category-path' => '',
                'category-path_op' => 0,
                'category-path_rec' => CourseCategory::EQUAL_TO,
                'display_status_colours_with_image' => 0,
                'hide_disabled_enrolments' => 0,
                'default_filter_type' => 'all',
                'default_filter_required' => 'all',
                'display_statewindowopen_status' => 0,
                'tags' => [],
                'display_type' => 'tile',
                'gradebook-role' => false,
                'show_only_prog_cert' => 0,
            ]
        );
    }

    /**
     * Return the human-friendly name of the provider.
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
    public function getShortName(): string
    {
        return 'record_of_learning';
    }

    /**
     * @param array $config
     */
    public function setConfig(array $config): void
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
     * @throws coding_exception
     */
    public function load(): array
    {
        global $DB, $USER, $CERTIFCOMPLETIONSTATE, $CFG;
        $programDecorator = new ProgramDecorator(new ProgramCompletion($DB));
        $programDecorator->setConfig(
            [
                'link_single_prog_courses' => $this->config['link_single_prog_courses'] == '1',
                'display_statewindowopen_status' => $this->config['display_statewindowopen_status'] == '1',
                'display_status_colours_with_image' => $this->config['display_status_colours_with_image'] == '1',
            ]
        );

        $courseDecorator = new CourseDecorator();
        $courseDecorator->setConfig(
            [
                'use_course_summary_image' => $this->config['use_course_summary_image'] == '1',
                'display_status_colours_with_image' => $this->config['display_status_colours_with_image'] == '1',
                'display_course_image' => $this->config['display_course_image']
            ]
        );
        $courseSource = (new Course())
            ->setFilter('hide_prog_courses', $this->config['hide_prog_courses'] == '1')
            ->setFilter('hide_disabled_enrolments', $this->config['hide_disabled_enrolments']);

        if (empty($this->config['show_only_prog_cert'])) {
            $dataSource = new CompositeSource(
                [
                    new DecoratorSource(new Program(), $programDecorator),
                    new DecoratorSource(new Certification(), $programDecorator),
                    new DecoratorSource($courseSource, $courseDecorator),
                ]
            );
        } else {
            $dataSource = new CompositeSource(
                [
                    new DecoratorSource(new Program(), $programDecorator),
                    new DecoratorSource(new Certification(), $programDecorator),
                ]
            );
        }

        $dataSource
            ->setFilter('userid', $USER->id)
            ->setFilter('category-path_op', $this->config['category-path_op'] == CourseCategory::EQUAL_TO)
            ->setFilter('category-path_rec', $this->config['category-path_rec'] == '1')
            ->setFilter('category-path', $this->config['category-path'])
            ->setFilter('tags', isset($this->config['tags']) ? $this->config['tags'] : '')
            ->setFilter('gradebook-role', !empty($this->config['gradebook-role']) ? $CFG->gradebookroles : '');

        $this->hook(
            'RecordOfLearning',
            'DataSource',
            $dataSource,
            function ($newSource) use (&$dataSource) {
                $dataSource = $newSource;
            }
        );

        $items = $dataSource->getData();

        foreach ($items as $item) {
            $item->required = $item->type == 'course' ? 'notrequired' : 'required';
        }

        // Apply default filters when filters are not displayed in the page.
        foreach (['type', 'required'] as $filter) {
            if (empty($this->config["filter_{$filter}"]) && $this->config["default_filter_{$filter}"] !== 'all') {
                $items = array_filter(
                    $items,
                    function ($item) use ($filter) {
                        return $item->{$filter} == $this->config["default_filter_{$filter}"];
                    }
                );
            }
        }

        $statuses = [
            'completed' => 0,
            'overdue' => 0,
            'started' => 0,
            'notstarted' => 0,
        ];

        if ($this->config['display_statewindowopen_status']) {
            $statuses[$CERTIFCOMPLETIONSTATE[CERTIFCOMPLETIONSTATE_WINDOWOPEN]] = 0;
        }

        $types = [
            'course' => 0,
            'program' => 0,
            'certification' => 0,
        ];

        $required = [
            'required' => 0,
            'notrequired' => 0,
        ];

        if (!empty($this->config['hide_completed_records'])) {
            unset($statuses['completed']);
            foreach ($items as $key => &$item) {
                if ($item->status == 'completed') {
                    unset($items[$key]);
                }
            }
        }

        if (!empty($this->config['limit_records'])) {
            $items = array_slice($items, 0, $this->config['limit_records']);
        }

        $total = count($items);

        foreach ($items as &$item) {
            if (!isset($statuses[$item->status])) {
                $statuses[$item->status] = 0;
            }
            $statuses[$item->status]++;
            $types[$item->type]++;
            $required[$item->required]++;
        }

        if ($total > 0) {
            foreach ($statuses as $status => &$count) {
                $count = round(($count / $total) * 100);
            }
        }

        $tableHead = new stdClass();
        $tableHead->title = get_string('record_of_learning_th_title', self::COMPONENT);
        $tableHead->status = get_string('record_of_learning_th_status', self::COMPONENT);
        $tableHead->required = get_string('record_of_learning_th_required', self::COMPONENT);
        $tableHead->date = get_string('record_of_learning_th_date', self::COMPONENT);

        return [
            'items' => $items,
            'plugin' => self::COMPONENT,
            'statuses' => $statuses,
            'types' => $types,
            'requires' => $required,
            'config' => $this->config,
            'table_head' => $tableHead,
        ];
    }

    /**
     * Returns the path to the main template to be loaded.
     * @return string
     */
    public function getTemplateFilename(): string
    {
        return 'record_of_learning.twig';
    }

    /**
     * @return Option[]
     * @throws coding_exception
     */
    public function getSettings(): array
    {
        $options = [];

        $name = 'filter_completion_status';
        $option = new Select(
            $this->getShortName() . '_' . $name,
            get_string('config:filter_status', self::COMPONENT),
            [
                '0' => get_string('config:disable', self::COMPONENT),
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
            'hide_required' => get_string('config:hide_required', self::COMPONENT),
            'filter_required' => get_string('config:filter_required', self::COMPONENT),
            'filter_type' => get_string('config:filter_type', self::COMPONENT),
            'hide_prog_courses' => get_string('config:hide_prog_courses', self::COMPONENT),
            'link_single_prog_courses' => get_string('config:link_single_prog_courses', self::COMPONENT),
            'use_course_summary_image' => get_string('config:use_course_summary_image', self::COMPONENT),
            'display_learning_type' => get_string('config:display_learning_type', self::COMPONENT),
            'category-path_rec' => get_string('config:display_learning_includesubcategories', self::COMPONENT),
            'display_status_colours_with_image' => get_string(
                'config:record_of_learning:display_status_colours_with_image',
                self::COMPONENT
            ),
            'hide_disabled_enrolments' => get_string('config:hide_disabled_enrolments', self::COMPONENT),
            'display_statewindowopen_status' => get_string('config:display_statewindowopen_status', self::COMPONENT),
        ];

        foreach ($checkboxes as $name => $label) {
            $option = new Checkbox($this->getShortName() . '_' . $name, $label);
            $option->value = $this->config[$name];
            $options[] = $option;
        }

        $name = 'category-path';
        $option = new CourseCategory(
            $this->getShortName() . '_' . $name,
            get_string('config:display_learning_within_categories', self::COMPONENT)
        );
        $option->value = $this->config[$name];
        $options[] = $option;

        $options[] = new Select(
            "{$this->getShortName()}_default_filter_status",
            get_string('config:default_filter_status', self::COMPONENT),
            [
                'all' => get_string('filter_navigation:all', self::COMPONENT),
                'completed' => get_string('filter_navigation:status:completed', self::COMPONENT),
                'overdue' => get_string('filter_navigation:status:overdue', self::COMPONENT),
                'started' => get_string('filter_navigation:status:started', self::COMPONENT),
                'notstarted' => get_string('filter_navigation:status:notstarted', self::COMPONENT),
                'incompleted' => get_string('filter_navigation:status:incompleted', self::COMPONENT),
            ]
        );

        $options[] = new Select(
            "{$this->getShortName()}_default_filter_type",
            get_string('config:default_filter_type', self::COMPONENT),
            [
                'all' => get_string('filter_navigation:all', self::COMPONENT),
                'course' => get_string('filter_navigation:type:course', self::COMPONENT),
                'program' => get_string('filter_navigation:type:program', self::COMPONENT),
                'certification' => get_string('filter_navigation:type:certification', self::COMPONENT),
            ]
        );

        $options[] = new Select(
            "{$this->getShortName()}_default_filter_required",
            get_string('config:default_filter_required', self::COMPONENT),
            [
                'all' => get_string('filter_navigation:all', self::COMPONENT),
                'required' => get_string('filter_navigation:required:required', self::COMPONENT),
                'notrequired' => get_string('filter_navigation:required:notrequired', self::COMPONENT),
            ]
        );

        $options[] = new Tag(
            "{$this->getShortName()}_tags",
            get_string('config:filter_prog_tags', self::COMPONENT),
            [
                'itemtype' => 'prog',
                'component' => 'totara_program',
            ]
        );

        $options[] = new Select(
            "{$this->getShortName()}_display_type",
            get_string('config:display_type', self::COMPONENT),
            [
                'tile' => get_string('config:display_type:tile', self::COMPONENT),
                'table' => get_string('config:display_type:table', self::COMPONENT),
            ]
        );

        $options[] = new Checkbox(
            "{$this->getShortName()}_hide_completed_records",
            get_string('config:display_hide_completed_records', self::COMPONENT)
        );

        $options[] = new Text(
            "{$this->getShortName()}_limit_records",
            get_string('config:limit_records', self::COMPONENT),
            PARAM_INT
        );

        $options[] = new Checkbox(
            "{$this->getShortName()}_gradebook-role",
            get_string('config:exclude_non_gradebookrole', self::COMPONENT)
        );

        $options[] = new Checkbox(
            "{$this->getShortName()}_show_only_prog_cert",
            get_string('config:show_only_prog_cert', self::COMPONENT)
        );

        return $options;
    }

    /**
     * Include and init any required JavaScript.
     * @throws moodle_exception
     */
    public function initJavaScript(): void
    {
        global $PAGE, $CFG;

        $selector = !empty($this->blockInstanceId) ? '#inst' . $this->blockInstanceId : '';
        $PAGE->requires->jquery();
        $PAGE->requires->js_init_call(
            'M.isotope_provider_record_of_learning.init',
            ['selector' => "{$selector} .learning"],
            false,
            [
                'name' => 'isotope_provider_record_of_learning',
                'fullpath' => new moodle_url(substr(dirname(__DIR__), strlen($CFG->dirroot)) . '/js/base.js'),
            ]
        );
    }
}
