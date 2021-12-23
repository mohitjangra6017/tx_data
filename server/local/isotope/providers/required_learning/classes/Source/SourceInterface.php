<?php
/**
 * Source Interface
 *
 * @package   isotopeprovider_required_learning
 * @author    Carlos Jurado <carlos.jurado@kineo.com>
 * @copyright 2018 City and Guilds Kineo
 * @license   http://www.kineo.com
 */

namespace isotopeprovider_required_learning\Source;

defined('MOODLE_INTERNAL') || die;

interface SourceInterface
{
    /**
     * Get the data provided by this data source.
     *
     * @return array
     */
    public function getData(): array;
}