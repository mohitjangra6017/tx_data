<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace theme_kineo\Settings;

use InvalidArgumentException;
use JsonSerializable;
use lang_string;

class Setting implements JsonSerializable
{
    public const TYPE_COLOUR = 'colour';
    public const TYPE_DROPDOWN = 'dropdown';
    public const TYPE_FILE = 'file';
    public const TYPE_IMAGE = 'image';
    public const TYPE_TEXT = 'text';
    public const TYPE_TEXTAREA = 'textarea';
    public const TYPE_TOGGLE = 'toggle';
    public const TYPE_URL = 'url';
    public const TYPE_EDITOR = 'editor';

    private const VALID_TYPES = [
        self::TYPE_COLOUR,
        self::TYPE_DROPDOWN,
        self::TYPE_FILE,
        self::TYPE_IMAGE,
        self::TYPE_TEXT,
        self::TYPE_TEXTAREA,
        self::TYPE_TOGGLE,
        self::TYPE_URL,
        self::TYPE_EDITOR,
    ];

    public const DEFAULT_LABEL_KEY = 'default_label';

    public const SCSS_VARIABLE_KEY = 'scss_var';

    public const REQUIRES_TOTARA_FEATURE_KEY = 'requires_totara_feature';

    public const SHOW_DEFAULT_KEY = 'show_default';

    public const SHOW_IDENTIFIER_KEY = 'show_identifier';

    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var lang_string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var mixed
     */
    protected $default;

    /**
     * @var Tab
     */
    protected $tab;

    /**
     * @var string|null
     */
    protected $heading;

    /**
     * @var bool
     */
    protected $tenantConfigurable;

    /**
     * @var array
     */
    protected $options;

    public function __construct(string $identifier, string $type, $default, Tab $tab, ?string $heading, bool $tenantConfigurable, $options)
    {
        $this->identifier = $identifier;
        $this->type = $type;
        $this->default = $default;
        $this->tab = $tab;
        $this->heading = $heading;
        $this->tenantConfigurable = $tenantConfigurable;
        $this->options = (array) $options;

        if (!in_array($type, self::VALID_TYPES)) {
            throw new InvalidArgumentException(
                sprintf("Setting Type '%s' not known. Must be one of: %s", $type, implode(', ', self::VALID_TYPES))
            );
        }

        $key = 'theme_settings:' . $this->tab->getIdentifier() . ':' . $this->identifier;
        $this->name = new lang_string($key, 'theme_kineo');
        $this->description = new lang_string($key . ':desc', 'theme_kineo');

        // Init any subclasses.
        $this->init();

        // Properly coerce these options into booleans and apply defaults if they do not exist.
        $defaults = [
            self::SCSS_VARIABLE_KEY => false,
            self::SHOW_DEFAULT_KEY => true,
            self::SHOW_IDENTIFIER_KEY => true,
        ];

        foreach ($defaults as $key => $value) {
            if (!isset($this->options[$key])) {
                $this->options[$key] = $value;
            }
            $this->options[$key] = (bool)$this->options[$key];
        }
    }

    protected function init()
    {
        // Designed for subclasses to extend the constructor without actually extending it and changing the constructor signature.
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @return lang_string
     */
    public function getName(): lang_string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @return Tab
     */
    public function getTab(): Tab
    {
        return $this->tab;
    }

    /**
     * @return string|null
     */
    public function getHeading(): ?string
    {
        return $this->heading;
    }

    /**
     * @param string|null $heading
     * @return Setting
     */
    public function setHeading(?string $heading): Setting
    {
        $this->heading = $heading;
        return $this;
    }

    /**
     * @return bool
     */
    public function isTenantConfigurable(): bool
    {
        return $this->tenantConfigurable;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return (array) $this->options;
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
            'identifier' => $this->identifier,
            'name' => $this->name->out(),
            'description' => $this->description->out(),
            'type' => $this->type,
            'default' => $this->default,
            'tab' => $this->tab->getIdentifier(),
            'heading' => $this->heading,
            'options' => (object) $this->options,
        ];
    }
}