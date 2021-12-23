<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace theme_kineo\Settings;

use JsonSerializable;
use lang_string;

class Tab implements JsonSerializable
{
    protected $identifier;

    protected $name;

    protected $headings = [];

    protected $description;

    public function __construct(string $identifier, array $headings)
    {
        $this->identifier = $identifier;

        $this->description = new lang_string("theme_settings:tab:{$this->identifier}:desc", 'theme_kineo');

        $this->name = new lang_string("theme_settings:tab:{$this->identifier}", 'theme_kineo');
        foreach ($headings as $heading) {
            $this->headings[$heading] = new lang_string("theme_settings:heading:{$this->identifier}:{$heading}", 'theme_kineo');
        }
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return format_text($this->description->out(), FORMAT_MARKDOWN);
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name->out();
    }

    /**
     * @return array
     */
    public function getHeadings(): array
    {
        return $this->headings;
    }

    /**
     * @param string $identifier
     * @return $this
     */
    public function addHeading(string $identifier): self
    {
        $this->headings[$identifier] = new lang_string('theme_settings:heading:' . $this->identifier . ':' . $identifier, 'theme_kineo');
        return $this;
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
        $headings = (object) array_map(
            function ($item) {
                return $item->out();
            },
            $this->getHeadings()
        );

        return [
            'identifier' => $this->getIdentifier(),
            'name' => $this->getName(),
            'headings' => $headings,
            'description' => $this->getDescription(),
        ];
    }
}