<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Array based data iterator.
 *
 * @package    core_phpunit
 * @copyright  2012 Petr Skoda {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace core_phpunit;

use Iterator, ArrayAccess, Countable, ArrayIterator;
use Exception;

/**
 * Data set table class
 */
final class DataSet_Table implements Iterator, ArrayAccess, Countable {
    /** @var array */
    protected $rows;

    /** @var ArrayIterator */
    protected $iterator;

    public function __construct(array $rows) {
        $this->rows = $rows;
        $this->iterator = new ArrayIterator($this->rows);
    }

    public function getRowCount(): int {
        return count($this->rows);
    }

    public function getRow(int $rowno): array {
        return $this->rows[$rowno];
    }

    public function getValue(int $row, int $column) {
        $row = $this->getRow($row);
        $columname = array_keys($row)[$column];
        return $row[$columname];
    }

    public function count() {
        return count($this->rows);
    }

    public function current() {
        return $this->iterator->current();
    }

    public function next() {
        $this->iterator->next();
    }

    public function key() {
        return $this->iterator->key();
    }

    public function valid() {
        return $this->iterator->valid();
    }

    public function rewind() {
        $this->iterator->rewind();
    }

    public function offsetExists($offset) {
        return array_key_exists($offset, $this->rows);
    }

    public function offsetGet($offset) {
        return $this->rows[$offset];
    }

    public function offsetSet($offset, $value) {
        throw new Exception('Cannot modify dataset tables');
    }

    public function offsetUnset($offset) {
        throw new Exception('Cannot modify dataset tables');
    }
}
