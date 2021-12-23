<?php
namespace theme_kineo\output;

class theme_kineo_renderer extends \theme_legacy_renderer
{
    /**
     * Get the HTML for blocks in the given region.
     *
     * @param string $region The region to get HTML for.
     * @return string HTML.
     */
    public function blocks($region): string {
        $html = parent::blocks($region);
        if (!empty($html)) {
            // These blocks are in a core region.
            return $html;
        }
        if (!$this->page->blocks->is_known_region($region)) {
            return $html;
        }

        // These blocks are in a custom region.
        $classes=[$region];
        if ($this->page->user_is_editing()) {
            $classes[] = 'editing-region-border';
        }
        $html = $this->output->blocks($region, $classes);
        return $html;
    }

    /**
     * Render help link in the header
     *
     * @param $data
     * @return string HTML fragment
     */
    public function render_header_help_link($data): string
    {
        return $this->render_from_template('theme_kineo/nav_help_link', $data);
    }

}