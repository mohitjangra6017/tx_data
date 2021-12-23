<?php
/**
 * @copyright City & Guilds Kineo 2018
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace isotopeprovider_required_learning;

use coding_exception;
use dml_exception;
use isotopeprovider_required_learning\Decorator\CertificationStatusDecorator;
use isotopeprovider_required_learning\Decorator\CompletionDecorator;
use isotopeprovider_required_learning\Decorator\CompositeDecorator;
use isotopeprovider_required_learning\Decorator\DateDecorator;
use isotopeprovider_required_learning\Decorator\ImageDecorator;
use isotopeprovider_required_learning\Decorator\ProgramStatusDecorator;
use isotopeprovider_required_learning\Decorator\StatusDecorator;
use isotopeprovider_required_learning\Decorator\UrlDecorator;
use isotopeprovider_required_learning\Source\CompositeSource;
use isotopeprovider_required_learning\Source\DecoratorSource;
use isotopeprovider_required_learning\Source\RequiredCertificationSource;
use isotopeprovider_required_learning\Source\RequiredProgramSource;
use lang_string;
use local_isotope\Form\Select;
use local_isotope\IsotopeProvider;

class Provider extends IsotopeProvider
{
    public const COMPONENT = 'isotopeprovider_required_learning';
    private const DATE_FORMAT = '%e %b %Y';

    // Status used in the isotope class.
    public const STATUS_UNSET = 'unset';
    public const STATUS_NOTSTARTED = 'notstarted';
    public const STATUS_STARTED = 'started';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_UNKNOWN = 'unknown';
    public const STATUS_EXPIRED = 'expired';

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
        return 'required_learning';
    }

    /**
     * @return array
     * @throws coding_exception
     */
    public function getSettings(): array
    {
        $options = [];

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

        return $options;
    }

    /**
     * Return all the items that will be displayed in the current block.
     * @return array
     * @throws dml_exception
     */
    public function load()
    {
        global $USER, $CFG, $DB;

        // Order of decorators is important.
        $decorator = new CompositeDecorator(
            [
                new CompletionDecorator($DB, $USER->id),
                new StatusDecorator(new ProgramStatusDecorator(), new CertificationStatusDecorator()),
                new DateDecorator($CFG, self::DATE_FORMAT),
                new ImageDecorator(get_file_storage(), $this->config),
                new UrlDecorator(),
            ]
        );

        $source = new DecoratorSource(
            $decorator, new CompositeSource(
                          [
                              new RequiredProgramSource($USER->id),
                              new RequiredCertificationSource($USER->id),
                          ]
                      )
        );

        return [
            'items' => $source->getData(),
            'config' => $this->getConfig(),
            'plugin' => self::COMPONENT,
        ];
    }

    /**
     * Returns the path to the main template to be loaded.
     * @return string
     */
    public function getTemplateFilename(): string
    {
        return 'learning.twig';
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
            'M.isotope_provider_required_learning.init',
            ['selector' => "{$selector} .learning"],
            false,
            [
                'name' => 'isotope_provider_required_learning',
                'fullpath' => new \moodle_url(substr(dirname(__DIR__), strlen($CFG->dirroot)) . '/js/base.js'),
            ]
        );
    }
}