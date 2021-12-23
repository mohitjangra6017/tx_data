<?php
/**
 * @file
 */

namespace block_banner\Contracts;

defined('MOODLE_INTERNAL') || die;

/**
 * Class first
 *
 * This strategy returns the first image that it finds in the banner block.
 */
interface ImageStrategyInterface
{
    /**
     * Get the image to display in the banner.
     *
     * @param int $context The ID of the context
     * @param string $data
     * @return string[]
     */
    public function getImage(int $context, string $data): array;

    /**
     * Get the name of the image strategy.
     *
     * @return string
     */
    public function getName(): string;
}
