/**
 * This file is part of Totara Enterprise Extensions.
 *
 * Copyright (C) 2020 onwards Totara Learning Solutions LTD
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
 * @author Simon Chester <simon.chester@totaralearning.com>
 * @module tui
 */

import { Format } from 'tui/editor';
import EditorTextarea from 'tui/components/editor/EditorTextarea';

// see also EditorInterface in tui/editor
export default {
  /**
   * Get component to render editor with.
   */
  getComponent() {
    return EditorTextarea;
  },

  /**
   * Get props to pass to editor component.
   *
   * @param {object} opts
   * @param {?number} opts.contextId
   * @param {object} opts.config Config returned by server-side. No standard shape, unique to each editor.
   * @param {number} opts.format Active format
   * @param {?number} opts.fileItemId Draft ID
   * @returns {object} Props
   */
  getProps(opts) {
    return {
      disabled: opts.disabled,
    };
  },

  /**
   * Convert raw (serialzed) value to something the component can understand.
   *
   * @param {string} content
   * @returns {*}
   */
  rawToValue(content) {
    return { content };
  },

  /**
   * Convert editor-specific value to serialized string.
   *
   * @param {*} value
   * @returns {string}
   */
  valueToRaw(value) {
    return value.content;
  },

  /**
   * Check if editor-specific content is empty.
   *
   * @param {*} value
   * @returns {boolean}
   */
  isContentEmpty(value) {
    return !value || !value.content;
  },

  /**
   * If this editor is picked and we don't have a specified format to use, use
   * this format.
   *
   * The default format that we used for this editor. However if the format is FORMAT_MOODLE or
   * FORMAT_HTML then this editor is still able to support it.
   *
   * @returns {Format}
   */
  getPreferredFormat() {
    return Format.PLAIN;
  },

  /**
   * Checks if the given format is supported by this editor.
   *
   * @param {Number} format
   * @return {Boolean}
   */
  supportsFormat(format) {
    return (
      format == Format.MOODLE || format == Format.PLAIN || format == Format.HTML
    );
  },
};
