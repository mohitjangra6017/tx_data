<?php
/**
 * @file
 */

namespace block_banner\ImageStrategies;

use block_banner\Contracts\ImageStrategyInterface;

defined('MOODLE_INTERNAL') || die;

/**
 * Class first
 *
 * This strategy returns the first image that it finds in the banner block.
 */
class First implements ImageStrategyInterface
{
    /** @var string */
    private $name = 'First image';

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

            preg_match("/<img .*?(?=src)src=\"([^\"]+)\"/", $data, $m);
            preg_match("/<img .*?(?=src)src=\"([^\"]+)\".*?\/?>/", $data, $full);
            preg_match("/<img .*?(?=height)height=\"([^\"]+)\"/", $data, $height);
            preg_match("/<img .*?(?=width)width=\"([^\"]+)\"/", $data, $width);

            if (!empty($m)) {
                $baseUrl = $CFG->wwwroot . '/' . $file . $context . '/block_banner/content/';
                $imgSrc = $m[1];
                $imgSrc = str_replace('@@PLUGINFILE@@/', '', $imgSrc);
            }
            if (!empty($full)) {
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
