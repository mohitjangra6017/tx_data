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
namespace contentmarketplace_linkedin\totara_xapi;

use contentmarketplace_linkedin\dto\xapi\progress;
use contentmarketplace_linkedin\exception\json_validation_exception;
use core\entity\user;
use core\json\validation_adapter;
use core\orm\query\builder;
use totara_xapi\entity\xapi_statement;
use coding_exception;
use contentmarketplace_linkedin\core_json\structure\xapi_statement as xapi_statement_structure;

/**
 * A wrapper class around xapi statement from linkedin learning.
 */
class statement {
    /**
     * The result extension keyword, that can help us identify the progress percentage.
     * @var string
     */
    private const RESULT_EXTENSION = "https://w3id.org/xapi/cmi5/result/extensions/progress";

    /**
     * @var xapi_statement
     */
    private $xapi_statement;

    /**
     * A lazy loading value for user's id that is extracted from the xapi statement.
     *
     * @var int|null
     */
    private $user_id;

    /**
     * @param xapi_statement $xapi_statement
     */
    private function __construct(xapi_statement $xapi_statement) {
        $this->xapi_statement = $xapi_statement;
        $this->user_id = null;
    }

    /**
     * @param xapi_statement $xapi_statement
     * @return statement
     */
    public static function create_with_validation(xapi_statement $xapi_statement): statement {
        if (!$xapi_statement->exists()) {
            // We need the record to be existing, in order to store the map.
            throw new coding_exception("The xapi statement does not exist in the system");
        }

        $validator = validation_adapter::create_default();
        $json_content = $xapi_statement->statement;

        $result = $validator->validate_by_structure_class_name(
            $json_content,
            xapi_statement_structure::class
        );

        if (!$result->is_valid()) {
            throw new json_validation_exception(
                $result->get_error_message()
            );
        }

        // Json validator here.
        return new self($xapi_statement);
    }

    /**
     * Returns the entity's id of {@see xapi_statement}
     * @return int
     */
    public function get_xapi_statement_id(): int {
        return $this->xapi_statement->id;
    }

    /**
     * A helper functionality that can get the user's id from the xapi statement.
     * Note that this function will only be able to fetch the user that is not yet
     * deleted within the system.
     *
     * @return int
     */
    public function get_user_id(): int {
        if (null === $this->user_id) {
            $statement_json = $this->xapi_statement->get_statement_as_json_array();

            // Note that this functionality will change the behaviour when the SSO is enabled
            // in between totara and the linkedin learning.
            if (!isset($statement_json["actor"])) {
                throw new coding_exception("Invalid xAPI statement, cannot find attribute 'actor'");
            }

            $actor = $statement_json["actor"];
            $db = builder::get_db();

            if (array_key_exists("mbox", $actor)) {
                $email = $actor["mbox"];

                // Remove the mailto within the statement content.
                $email = str_replace("mailto:", "", $email);

                // Note: we use MUST_EXIST here because we do not want non-existing user with
                //       the email from linkedin learning's end. Hence, exception.
                $this->user_id = $db->get_field(
                    user::TABLE,
                    "id",
                    [
                        "email" => $email,
                        "deleted" => 0
                    ],
                    MUST_EXIST
                );
            } else {
                // This is where we are going to check for SSO - but it is not yet implemented.
                throw new coding_exception("Unsupported feature to identify user");
            }
        }

        return $this->user_id;
    }

    /**
     * @return progress
     */
    public function get_progress(): progress {
        $json = $this->xapi_statement->get_statement_as_json_array();
        $completed = $json["result"]["completion"];

        // Finding out the progress percentage.
        $progress = 0;
        if (isset($json["result"]["extensions"][self::RESULT_EXTENSION])) {
            $progress = $json["result"]["extensions"][self::RESULT_EXTENSION];
        }

        return new progress($completed, $progress);
    }

    /**
     * @return string
     */
    public function get_learning_object_urn(): string {
        $json = $this->xapi_statement->get_statement_as_json_array();
        if (!isset($json["object"]["id"])) {
            throw new coding_exception("Invalid xAPI statement, cannot find attribute 'object > id'");
        }

        return $json["object"]["id"];
    }
}