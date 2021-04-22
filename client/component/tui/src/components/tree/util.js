/**
 * This file is part of Totara Enterprise Extensions.
 *
 * Copyright (C) 2021 onwards Totara Learning Solutions LTD
 *
 * Totara Enterprise Extensions is provided only to Totara
 * Learning Solutions LTD's customers and partners, pursuant to
 * the terms and conditions of a separate agreement with Totara
 * Learning Solutions LTD or its affiliate.
 *
 * If you do not have an agreement with Totara Learning Solutions
 * LTD, you may not access, use, modify, or distribute this software.
 * Please contact [licensing@totaralearning.com] for more information.
 *
 * @author Kevin Hottinger <kevin.hottinger@totaralearning.com>
 * @module tui
 */

/**
 * Return an array of available branch ID's included in the dataset
 *
 * @param {array} data
 * @returns {array}
 */
export function getAllBranchKeys(data) {
  /**
   * Gets available list of branch ID's
   *
   * @param {Object} branch
   */
  function getKeyList(branch) {
    // Add Id to list if not already included (check for duplicates)
    if (!fullKeyList.includes(branch.id)) {
      fullKeyList.push(branch.id);
    }

    // If there is child data iterate through it
    if (branch.children) {
      branch.children.forEach(subBranch => {
        getKeyList(subBranch);
      });
    }
  }

  let fullKeyList = [];
  data.forEach(tree => {
    getKeyList(tree);
  });
  return fullKeyList;
}
