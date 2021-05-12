<?php
/**
 * This file is part of Totara Learn
 *
 * Copyright (C) 2021 onwards Totara Learning Solutions LTD
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author  Mark Metcalfe <mark.metcalfe@totaralearning.com>
 * @package contentmarketplace_linkedin
 */

namespace contentmarketplace_linkedin\dto;

use coding_exception;

final class timespan {

    /**
     * The units that can be specified, with how many seconds are in each unit.
     */
    private const UNIT_VALUES = [
        'HOUR' => HOURSECS,
        'MINUTE' => MINSECS,
        'SECOND' => 1,
    ];

    /**
     * @var int
     */
    private $duration;

    /**
     * @var string
     */
    private $unit;

    /**
     * timespan constructor.
     * @param int $duration
     * @param string $unit
     */
    public function __construct(int $duration, string $unit) {
        if (!array_key_exists($unit, self::UNIT_VALUES)) {
            throw new coding_exception("Invalid unit specified for timespan: $unit");
        }
        $this->duration = $duration;
        $this->unit = $unit;
    }

    /**
     * Get the amount of time in seconds.
     *
     * @return int
     */
    public function get(): int {
        return $this->duration * self::UNIT_VALUES[$this->unit];
    }

    /**
     * Get the raw duration of this time.
     *
     * @return int
     */
    public function get_raw_duration(): int {
        return $this->duration;
    }

    /**
     * Get the unit that this time is in.
     *
     * @return string
     */
    public function get_raw_unit(): string {
        return $this->unit;
    }

}
