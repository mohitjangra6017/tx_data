<?php
/**
 * @file
 */

namespace block_banner\ImageStrategies;

use block_banner\Contracts\ImageStrategyInterface;

defined('MOODLE_INTERNAL') || die;

/**
 * Class random
 */
class Random implements ImageStrategyInterface
{
    /** @var string */
    private $name = 'Random image';

    /**
     * Get the name of the image strategy.
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the image to display in the banner.
     *
     * @param int $context
     * @param string $data
     * @return array
     */
    public function getImage(int $context, string $data): array
    {
        global $CFG;
        $result = $imgSrc = $baseUrl = '';
        $textOnly = '';
        $height = $width = 0;
        if ($data) {
            $file = 'pluginfile.php/';

            preg_match_all("/<img .*?(?=src)src=\"([^\"]+)\"/", $data, $ma, PREG_SET_ORDER);
            preg_match_all("/<img .*?(?=src)src=\"([^\"]+)\".*?\/?>/", $data, $full2, PREG_SET_ORDER);
            preg_match_all("/<img .*?(?=height)height=\"([^\"]+)\"/", $data, $height2, PREG_SET_ORDER);
            preg_match_all("/<img .*?(?=width)width=\"([^\"]+)\"/", $data, $width2, PREG_SET_ORDER);

            $selected = array_rand(array_keys($ma));
            $m = !empty($ma) ? $ma[$selected] : [];
            $full = !empty($full2) ? $full2[$selected] : [];
            $height = !empty($height2) ? $height2[$selected] : [];
            $width = !empty($width2) ? $width2[$selected] : [];

            if (!empty($m)) {
                $baseUrl = $CFG->wwwroot . '/' . $file . $context . '/block_banner/content/';
                $imgSrc = $m[1];
                $imgSrc = str_replace('@@PLUGINFILE@@/', '', $imgSrc);
            }
            if (!empty($full)) {
                foreach ($full2 as $index => $content) {
                    if ($index === $selected) {
                        continue;
                    }
                    $data = str_replace($content[0], '', $data);
                }
                $subdata = str_replace($full[0], '', $data);
                $textOnly = str_replace('@@PLUGINFILE@@/', $baseUrl, $subdata);
            } else {
                $textOnly = $data;
            }
            if (!empty($height)) {
                $height = $height[1];
            }
            if (!empty($width)) {
                $width = $width[1];
            }
            if (preg_match('#^http(s?)://#i', $imgSrc) || preg_match('#^www\.#i', $imgSrc)) {
                $result = $imgSrc;
            } else {
                $result = $baseUrl . $imgSrc;
            }
        }

        return [$result, $textOnly, $height, $width];
    }
}