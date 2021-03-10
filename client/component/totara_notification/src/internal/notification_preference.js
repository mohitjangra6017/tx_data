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
 * @author Kian Nguyen <kian.nguyen@totaralearning.com>
 * @module totara_notification
 */
import { Format } from 'tui/editor';

export const NOTIFICATION_PREFERENCE_KEYS = [
  'subject',
  'body',
  'body_format',
  'title',
  'schedule_type',
  'schedule_offset',
  'subject_format',
  'recipient',
];

export const SCHEDULE_TYPES = {
  ON_EVENT: 'ON_EVENT',
  BEFORE_EVENT: 'BEFORE_EVENT',
  AFTER_EVENT: 'AFTER_EVENT',
};

/**
 * Validator function for the notification preference props.
 *
 * @param {Array} extraKeys
 * @return {Function}
 */
export function validatePreferenceProp(extraKeys = []) {
  const keys = extraKeys.concat(NOTIFICATION_PREFERENCE_KEYS);

  return prop => {
    const result = keys.filter(key => {
      return !(key in prop);
    });

    return 0 === result.length;
  };
}

/**
 * Validator function for the notification recipients props.
 *
 * @return {Function}
 */
export function validateAvailableRecipientsProp() {
  return prop => {
    return prop.every(recipient => {
      return 'class_name' in recipient && 'name' in recipient;
    });
  };
}

/**
 *
 * @param {Object} extraAttributes
 * @return {Object}
 */
export function getDefaultNotificationPreference(extraAttributes = {}) {
  // We are default the body format to JSON_EDITOR for the fallback.
  // Ideally it will be defined by the server-side.
  const defaultAttributes = {
    subject: '',
    body: '',
    body_format: Format.JSON_EDITOR,
    subject_format: Format.JSON_EDITOR,
    title: null,
    schedule_type: SCHEDULE_TYPES.ON_EVENT,
    schedule_offset: null,
    recipient: null,
    extended_context: {
      component: '',
      area: '',
      item_id: 0,
      context_id: 0,
    },
  };

  return () => {
    return Object.assign({}, defaultAttributes, extraAttributes);
  };
}
