<?php

defined('MOODLE_INTERNAL') || die;

class block_isotope_edit_form extends block_edit_form
{
    /**
     * Enable general settings
     *
     * @return bool
     */
    protected function has_general_settings()
    {
        return true;
    }

    protected function specific_definition($mform)
    {
        global $OUTPUT;
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        // Block title was added as common block setting in Totara 12.
        if (totara_major_version() < 12) {
            $mform->addElement('text', 'config_title', get_string('title', 'block_isotope'));
            $mform->setType('config_title', PARAM_TEXT);
        }

        $providers = $this->getProviders();
        if (empty($providers)) {
            $mform->addElement('html', $OUTPUT->notify_problem(get_string('noproviders', 'block_isotope')));
            return;
        }

        $mform->addElement(
            'select',
            'config_provider',
            get_string('provider', 'block_isotope'),
            array_merge(
                [0 => get_string('choosedots')],
                array_map(
                    function ($provider) {
                        return $provider->getDisplayName();
                    },
                    $providers
                )
            )
        );

        if (!empty($this->block->config) && isset($this->block->config->provider) && isset($providers[$this->block->config->provider])) {
            /** @var \local_isotope\IsotopeProvider $provider */
            $provider = $providers[$this->block->config->provider];
            $mform->addElement('header', 'providerheader', $provider->getDisplayName());
            $provider->setConfig($this->block->getProviderConfig($provider));
            $provider->extendForm($mform);
        }
    }

    private function getProviders()
    {
        $isotope = new local_isotope\Isotope();
        $providers = array_filter(
            $isotope->getProviders(),
            function ($provider) use ($isotope) {
                return $isotope->providerIsEnabled($provider);
            }
        );
        return $providers;
    }
}