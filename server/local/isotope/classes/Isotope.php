<?php

namespace local_isotope;

class Isotope
{
    /** @var IsotopeProvider[] */
    private $providers = [];

    public function __construct()
    {
        $this->providers = $this->getProviders();
    }

    /**
     * Find all providers from sub-plugins.
     * @return IsotopeProvider[]
     */
    public function getProviders()
    {
        if (count($this->providers) > 0) {
            return $this->providers;
        }

        $providers = [];

        $path = \core_component::get_plugin_types()['isotopeprovider'];
        $iterator = new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS);
        foreach ($iterator as $pluginDirectory => $fileInfo) {
            $namespace = "\\isotopeprovider_" . basename($pluginDirectory) . "\\";
            $class = $namespace . 'Provider';
            if (class_exists($class) && in_array(IsotopeProvider::class, class_parents($class))) {
                /** @var IsotopeProvider $provider */
                $provider = new $class();
                $providers[$provider->getShortName()] = $provider;
            }
        }

        return $providers;
    }

    /**
     * @param $provider
     * @return bool
     */
    public function providerIsValid($provider)
    {
        return isset($this->providers[$provider->getShortName()]);
    }

    /**
     * @param $provider
     * @return false|mixed|object
     */
    public function providerIsEnabled($provider)
    {
        return get_config('local_isotope', 'provider_' . $provider->getShortName());
    }
}