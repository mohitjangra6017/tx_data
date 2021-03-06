<?php
/**
 * This file is part of Totara Core
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
 * @author  Kian Nguyen <kian.nguyen@totaralearning.com>
 * @package contentmarketplace_linkedin
 */
namespace contentmarketplace_linkedin\dto\xapi;

class progress {
    /**
     * The maximum progress, in percentage unit.
     * @var int
     */
    public const PROGRESS_MAXIMUM = 100;

    /**
     * @var bool
     */
    private $completed;

    /**
     * The value of progress, in percentage. When it is 100%, then
     * it indicates that user had completed the course.
     *
     * @var int
     */
    private $progress;

    /**
     * @param bool $completed
     * @param int $progress
     */
    public function __construct(bool $completed, int $progress) {
        $this->completed = $completed;
        $this->progress = $progress;
    }

    /**
     * @return bool
     */
    public function is_completed(): bool {
        return $this->completed;
    }

    /**
     * @return int
     */
    public function get_progress(): int {
        return $this->progress;
    }
}