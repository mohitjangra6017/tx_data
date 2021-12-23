<?php
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/totara/core/js/lib/setup.php');
require_once($CFG->dirroot . '/totara/program/lib.php');

class block_isotope extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_isotope');
    }

    public function instance_allow_multiple() {
        return true;
    }

    public function instance_allow_config() {
        return true;
    }

    public function has_config() {
        return true;
    }

    public function specialization() {
        // We need to keep this line for backwards compatibility as before Totara 12, the plugin stored the title in config variable.
        if (isset($this->config->title)) {
            $this->title = format_string($this->config->title);
        }
    }

    public function applicable_formats() {
        return array(
            'all' => true,
            'mod' => true,
            'tag' => false
        );
    }

    public function get_content() {
        if ($this->content !== null) {
            return $this->content;
        }

        $isotope = new \local_isotope\Isotope();

        if (empty($this->config->provider)) {
            return;
        }
        $providers = $isotope->getProviders();
        $provider = $providers[$this->config->provider];
        if (!$isotope->providerIsValid($provider) || !$isotope->providerIsEnabled($provider)) {
            return;
        }

        // Backward compatibility: This method does not exist in all versions of the Skeleton.
        if (method_exists($provider, 'setBlockInstanceId')) {
            $provider->setBlockInstanceId($this->instance->id);
        }

        $this->initJavaScript();
        $provider->initJavaScript();

        $provider->setConfig($this->getProviderConfig($provider));

        $loader = new Twig_Loader_Filesystem([$provider->getTemplateDirectory()]);
        $twig = new Twig_Environment($loader, ['debug' => true]);
        $this->twigExtensions($twig, $provider);

        // Get TWIG template.
        $template = $twig->load($provider->getTemplateFilename());

        $this->content = new stdClass();
        $this->content->text = $template->render($provider->load());

        return $this->content;
    }

    public function instance_config_save($data, $nolongerused = false)
    {
        $isotope = new \local_isotope\Isotope();
        $providers = $isotope->getProviders();
        if (empty($data->provider)) {
            parent::instance_config_save($data, $nolongerused);
            return;
        }

        // In the case where we configure a data source for the first time, set the defaults to the block config.
        // If the block currently has config for that provider, or we just submitted it, nothing will change.
        $provider = $providers[$data->provider];
        foreach ($provider->getConfig() as $name => $value) {
            $key = $provider->getShortName() . '_' . $name;
            if (isset($data->{$key}) || isset($this->config->{$name})) {
                // Never override submitted data, or current data.
                continue;
            }
            // Set the default value correctly.
            $data->{$key} = $value;
        }

        parent::instance_config_save($data, $nolongerused);
    }

    public function getProviderConfig($provider)
    {
        $options = [];
        foreach ($this->config as $key => $value) {
            if (!empty($provider->getShortName()) && strpos($key, $provider->getShortName()) !== false) {
                $options[substr($key, strlen($provider->getShortName()) + 1)] = $value;
            }
        }
        return $options;
    }

    private function initJavaScript() {
        global $PAGE;

        $totaraVersion = (int) get_config(null, 'totara_version');
        if ($totaraVersion < 9) {
            local_js();
        }
        $PAGE->requires->js('/blocks/isotope/plugins/isotope/isotope.js');
    }

    private function twigExtensions(Twig_Environment $twig, \local_isotope\IsotopeProvider $provider)
    {
        $twig->addFunction(new Twig_SimpleFunction(
            'get_string',
            function ($name, $plugin = 'core', $a = null) {
                return format_text(get_string($name, $plugin, $a), FORMAT_HTML);
            },
            ['is_safe' => ['all']]
        ));

        $twig->addFunction(new Twig_SimpleFunction('render_pix_icon', function ($name, $alt, $component = 'core') {
            global $OUTPUT;
            return $OUTPUT->render(new pix_icon($name, $alt, $component));
        }));

        $twig->addFilter(new Twig_SimpleFilter(
            'format_html',
            function ($text) {
                return format_text($text, FORMAT_HTML);
            },
            ['is_safe' => ['all']]
        ));

        $provider->twigExtensions($twig);
    }
}