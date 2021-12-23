<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace theme_kineo\Settings;

use JsonSerializable;

class Collection implements JsonSerializable
{
    protected $settings = [];

    protected $tabs = [];

    protected $tabHeadings = [];

    public function __construct(array $settings = [], array $tabs = [])
    {
        $this->settings = array_values($settings);
        $this->tabs = $tabs;
    }

    /**
     * @return Setting[]
     */
    public function getSettings(): array
    {
        return $this->settings;
    }

    /**
     * @param string $tab
     * @return Setting[]
     */
    public function getSettingsFromTab(string $tab): array
    {
        return array_filter($this->getSettings(), fn($setting) => $setting->getTab()->getIdentifier() === $tab);
    }

    /**
     * @return Tab[]
     */
    public function getTabs(): array
    {
        return $this->tabs;
    }

    public function addSetting(Setting $setting): void
    {
        if ($setting->getHeading() === null || empty($this->tabs[$setting->getTab()->getIdentifier()])) {
            $setting->setHeading(null);
        }
        $this->settings[] = $setting;
    }

    public function addTab(Tab $tab): void
    {
        $this->tabs[$tab->getIdentifier()] = $tab;
    }

    /**
     * @param string $identifier
     * @return Setting|null
     */
    public function getSetting(string $identifier): ?Setting
    {
        $filtered = array_filter(
            $this->settings,
            function ($item) use ($identifier) {
                return $item->getIdentifier() === $identifier;
            }
        );
        return count($filtered) === 1 ? reset($filtered) : null;
    }

    /**
     * @param string $type
     * @return Setting[]
     */
    public function getSettingsOfType(string $type): array
    {
        return array_filter(
            $this->settings,
            function ($item) use ($type) {
                return $item->getType() === $type;
            }
        );
    }

    /**
     * @param string $identifier
     * @return Tab|null
     */
    public function getTab(string $identifier): ?Tab
    {
        $filtered = array_filter(
            $this->tabs,
            function ($item) use ($identifier) {
                return $item->getIdentifier() === $identifier;
            }
        );
        return count($filtered) === 1 ? reset($filtered) : null;
    }


    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4
     */
    public function jsonSerialize()
    {
        return [
            'settings' => array_map(
                function ($item) {
                    return $item->jsonSerialize();
                },
                $this->getSettings()
            ),
            'tabs' => array_values(
                array_map(
                    function ($item) {
                        return $item->jsonSerialize();
                    },
                    $this->getTabs()
                )
            ),
        ];
    }
}