<?php

namespace local_isotope;

use core_component;
use local_isotope\Form\Option;
use Twig_Environment;

abstract class IsotopeProvider {

    protected $config = [];

    /**
     * @var int
     */
    protected $blockInstanceId;

    /**
     * Return the human-friendly name of the provider.
     * @return string
     */
    abstract public function getDisplayName();

    /**
     * Return the short name of the plugin, used in config settings, and as a unique key.
     * @return string
     */
    abstract public function getShortName();

    /**
     * Return all the items that will be displayed in the current block.
     * @return array
     */
    abstract public function load();

    /**
     * Include and init any required JavaScript.
     * @return void
     */
    abstract public function initJavaScript();

    /**
     * Returns the path to the main template to be loaded.
     * @return string
     */
    abstract public function getTemplateFilename();

    /**
     * Returns the path to the templates directory.
     * @return string
     */
    public function getTemplateDirectory()
    {
        return $this->getDirectory() . '/templates';
    }

    /**
     * Options that allow this Provider to be configured.
     * Use this in conjunction with {@see IsotopeProvider::extendForm()} to change the behaviour of the form object.
     * @return Option[]
     */
    public function getSettings()
    {
        return [];
    }

    /**
     * @param \MoodleQuickForm $form
     */
    public function extendForm(\MoodleQuickForm $form)
    {
        foreach ($this->getSettings() as $setting) {
            $setting->extendForm($form);
        }
    }

    /**
     * Sets this providers config settings, if any are defined.
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->config = array_merge($this->config, $config);
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Allows providers to include their own twig Extensions.
     * @param Twig_Environment $twig
     */
    public function twigExtensions(Twig_Environment $twig) {
        return;
    }

    /**
     * @param int $instanceId
     * @return self
     */
    public function setBlockInstanceId($instanceId)
    {
        $this->blockInstanceId = (int) $instanceId;
        return $this;
    }

    /**
     * Get all classes that hook the IsotopeProvider.
     * Hooks are classes defined as \local_my_plugin\IsotopeProvider\{$namespace}\{$hookName}Hook
     * They can be stored in any plugin that has autoloaded classes.
     * Hooks must inherit the AbstractHook class.
     *
     * @param string $namespace The name of the Provider, such as RecordOfLearning
     * @param string $hookName The name of Hook, such as DataSource
     * @param mixed $data Something to pass to the hook
     * @param callable $returnFn Callback function when the hook returns
     */
    protected function hook($namespace, $hookName, $data, callable $returnFn)
    {
        $hooks = core_component::get_namespace_classes(
            "IsotopeProvider\\{$namespace}",
            AbstractHook::class,
            null,
            false
        );

        // Remove any Hooks that do not match $hookName, and instantiate the rest.
        $hooks = array_map(
            function ($hookClass) {
                return new $hookClass();
            },
            array_filter(
                $hooks,
                function ($hookClass) use ($hookName) {
                    $subClass = substr($hookClass, strrpos($hookClass, "\\") + 1);
                    return $subClass === "{$hookName}Hook";
                }
            )
        );

        // Sort the Hooks in order of priority from highest to lowest.
        usort(
            $hooks,
            function ($a, $b) {
                if ($a::$priority == $b::$priority) {
                    return 0;
                } else {
                    return $a::$priority > $b::$priority ? 1 : -1;
                }
            }
        );

        // Now run the hooks in order.
        foreach ($hooks as $hook) {
            /** @var AbstractHook $hook */
            $return = $hook->run($data, $returnFn);
            if ($hook::$hasReturn) {
                $data = $return;
            }
            if (!$hook::$canContinue) {
                return;
            }
        }
    }

    /**
     * @return string The base directory of the plugin
     */
    final public function getDirectory()
    {
        return core_component::get_plugin_directory('isotopeprovider', $this->getShortName());
    }
}