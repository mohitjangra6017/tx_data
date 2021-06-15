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
namespace contentmarketplace_linkedin\model;

use contentmarketplace_linkedin\entity\classification as classification_entity;
use contentmarketplace_linkedin\entity\classification_relationship;
use core\orm\entity\model;
use core\orm\query\builder;

/**
 * Model class for learning object classification.
 *
 * @property-read int $id
 * @property-read string $urn
 * @property-read string $locale_language
 * @property-read string $locale_country
 * @property-read string $name
 * @property-read string $type
 *
 * Computed properties:
 *
 * @property-read int|null $parent_classification_id
 */
class classification extends model {
    /**
     * @var array
     */
    protected $entity_attribute_whitelist = [
        'id',
        'urn',
        'locale_language',
        'locale_country',
        'name',
        'type'
    ];

    protected $model_accessor_whitelist = [
        'parent_classification_id'
    ];

    /**
     * @var int|null
     */
    private $internal_parent_classification_id;

    /**
     * A flag to tell us whether the class should fetch the parent classification id again.
     * + TRUE to NOT fetch the parent id.
     * + FALSE to fetch the parent id.
     *
     * @var bool
     */
    private $fetched_parent_classification_id;

    /**
     * classification constructor.
     * @param classification_entity $entity
     * @param int|null $parent_classification_id
     */
    public function __construct(classification_entity $entity, ?int $parent_classification_id = null) {
        parent::__construct($entity);
        $this->internal_parent_classification_id = $parent_classification_id;

        // As long as parent_classification_id is provided, then it does not have to be fetched again.
        $this->fetched_parent_classification_id = !empty($parent_classification_id);
    }

    /**
     * @return int|null
     */
    public function get_parent_classification_id(): ?int {
        $this->load_parent_classification_id();
        return $this->internal_parent_classification_id;
    }

    /**
     * @return void
     */
    private function load_parent_classification_id(): void {
        if (!$this->fetched_parent_classification_id) {
            // If the flag says to fetch. We are going to ignore the
            // current value of parent classification id.
            $db = builder::get_db();
            $this->internal_parent_classification_id = $db->get_field(
                classification_relationship::TABLE,
                'parent_classification_id',
                ['child_classification_id' => $this->entity->id],
                IGNORE_MISSING
            );

            // Update flag
            $this->fetched_parent_classification_id = true;
        }
    }

    /**
     * @param bool $fetched
     * @return void
     */
    public function set_fetched_parent_classification_id(bool $fetched): void {
        $this->fetched_parent_classification_id = $fetched;
    }

    /**
     * @return string
     */
    protected static function get_entity_class(): string {
        return classification_entity::class;
    }

    /**
     * @return bool
     */
    public function has_parent_classification(): bool {
        $parent_id = $this->get_parent_classification_id();
        return !empty($parent_id);
    }
}