<?php
/**
 * @copyright City & Guilds Kineo 2020
 * @author Michael Geering <michael.geering@kineo.com>
 */

namespace local_core\Hook;

use theme_config;
use totara_core\hook\base;

class ThemeCssVariables extends base
{
    /** @var theme_config */
    private $theme;

    /** @var int */
    private $tenantId;

    /** @var string */
    protected $css;

    /** @var array */
    protected $categories;

    /** @var bool */
    protected $shouldSkipCore = false;

    /** @var array */
    protected $themeFiles;

    public function __construct(theme_config $theme, array $themeFiles, int $tenantId, string $css, array $categories)
    {
        $this->css = $css;
        $this->categories = $categories;
        $this->theme = $theme;
        $this->tenantId = $tenantId;
        $this->themeFiles = $themeFiles;
    }

    /**
     * @return array
     */
    public function getThemeFiles(): array
    {
        return $this->themeFiles;
    }

    /**
     * @return string
     */
    public function getCss(): string
    {
        return $this->css;
    }

    /**
     * @param string $css
     * @return ThemeCssVariables
     */
    public function setCss(string $css): ThemeCssVariables
    {
        $this->css = $css;
        return $this;
    }

    /**
     * @return array
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @param array $categories
     * @return ThemeCssVariables
     */
    public function setCategories(array $categories): ThemeCssVariables
    {
        $this->categories = $categories;
        return $this;
    }

    /**
     * @return theme_config
     */
    public function getTheme(): theme_config
    {
        return $this->theme;
    }

    /**
     * @return int
     */
    public function getTenantId(): int
    {
        return $this->tenantId;
    }

    /**
     * @return bool
     */
    public function shouldSkipCore(): bool
    {
        return $this->shouldSkipCore;
    }

    /**
     * @param bool $shouldSkipCore
     * @return ThemeCssVariables
     */
    public function setShouldSkipCore(bool $shouldSkipCore): ThemeCssVariables
    {
        $this->shouldSkipCore = $shouldSkipCore;
        return $this;
    }
}