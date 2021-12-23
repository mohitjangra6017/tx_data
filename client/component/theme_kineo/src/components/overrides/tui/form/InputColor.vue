<!--
  This file is part of Totara Enterprise Extensions.

  Copyright (C) 2020 onwards Totara Learning Solutions LTD

  Totara Enterprise Extensions is provided only to Totara
  Learning Solutions LTD's customers and partners, pursuant to
  the terms and conditions of a separate agreement with Totara
  Learning Solutions LTD or its affiliate.

  If you do not have an agreement with Totara Learning Solutions
  LTD, you may not access, use, modify, or distribute this software.
  Please contact [licensing@totaralearning.com] for more information.

  @author Dave Wallace <dave.wallace@totaralearning.com>
  @module tui
-->

<template>
  <div
    class="tui-inputColor"
    :class="[
      charLength ? 'tui-inputColor--charLength-' + charLength : null,
      charLength ? 'tui-input--customSize' : null,
    ]"
  >
    <!-- this colour block acts as a view for the last input valid hex value as
          we're going to hide the initial colour picker control for modern
          browsers so we have full control over styling. IE11 relies on this
          to display the result of the manual text Input because it doesn't
          get a native color Input at all -->
    <div
      class="tui-inputColor__colorBlock"
      :class="[disabled ? 'tui-inputColor__colorBlock--disabled' : '']"
      :style="{
        backgroundColor: resolvedHexValue,
      }"
    />

    <!-- for modern browsers, we can use the native HTML5 color picker. IE11
        will only be provided with the manual hex Input control -->
    <Input
      v-if="!isIE"
      :value="resolvedHexValue"
      type="color"
      class="tui-inputColor__picker"
      tabindex="-1"
      :disabled="disabled"
      :readonly="readonly"
      aria-hidden="true"
      v-on="$listeners"
      @input="handlePickerInput"
    />

    <!-- all browsers will show a manual hex Input control -->
    <Input
      class="tui-inputColor__input"
      v-bind="$props"
      char-length="full"
      type="text"
      v-on="$listeners"
      @blur="handleTextChange"
    />
  </div>
</template>

<script>
import Input from 'tui/components/form/Input';

export default {
  components: {
    Input,
  },
  inheritAttrs: false,

  /* eslint-disable vue/require-prop-types */
  props: [
    'ariaDescribedby',
    'ariaInvalid',
    'ariaLabel',
    'ariaLabelledby',
    'autofocus',
    'disabled',
    'id',
    'name',
    'readonly',
    'required',
    'styleclass',
    'value',
    'charLength'
  ],

  data() {
    return {
      isIE: document.body.classList.contains('ie'),
      resolvedHexValue: null,
      keywordColours: {}
    };
  },

  mounted() {
    this.resolvedHexValue = this.resolveColour(this.value);
  },

  created() {
    this.keywordColours = this.custom_validation.getKeywordColours();
  },

  inject: ['custom_validation'],

  methods: {

    resolveColour(value) {

      if (value === "") {
        return;
      }

      value = this.custom_validation.resolveSetting(value);

      if (value === null) {
        return;
      }

      let result = null;

      if (this.custom_validation.isValidHexColour(value)) {
        result = value;
      } else if (this.custom_validation.isValidRgbColour(value)) {
        result = this.convertRgbToHex(value);
      } else if (this.custom_validation.isValidRgbaColour(value)) {
        result = this.convertRgbaToHex(value);
      } else if (this.custom_validation.isValidKeywordColour(value)) {
         result = this.convertKeywordToHex(value);
      }

      return result;
    },

    /**
     * If a valid hex value is manually entered into the text Input, cache that
     * value so we have a reliable value for when an invalid value is entered.
     */
    handleTextChange(e) {
      this.resolvedHexValue = this.resolveColour(e.target.value);
      this.$emit('input', e.target.value);
      return;
    },

    /**
     * A valid hex value is always received from the color Input element, so
     * if the element is not readonly, update the cached reliable value with the
     * value set by the picker.
     **/
    handlePickerInput(e) {
      // the readonly attribute does not apply to the color input element as per
      // spec, however we may still need to handle this condition. because the
      // the attribute isn't supported, the OS color picker may still launch,
      // but our approach to this means we can do nothing if a new colour is
      // selected
      if (!this.readonly) {
        this.resolvedHexValue = this.resolveColour(e);
        this.$emit('input', e);
      }
      return;
    },

    /**
     * Manual validation check applied to the text Input so that we're not
     * updating the color Input with invalid values on input events
     *
     * Note that 3-digit hex values are not valid as per spec, a minor loss
     * in productivity for those who remember and enjoy using shorthand codes
     **/
    isValidHexCode: val => /^#[0-9A-F]{6}$/i.test(val),

    /**
     * Convert an RGB value to a 6 character hexadecimal value.
     *
     * @param rgb
     * @returns {string}
     */
    convertRgbToHex(rgb) {
      rgb = this.custom_validation.getRgbRegEx().exec(rgb);
      return "#" + ((1 << 24) + (parseInt(rgb.groups['r'], 10) << 16) + (parseInt(rgb.groups['g'], 10) << 8) + parseInt(rgb.groups['b'], 10)).toString(16).slice(1).toUpperCase();
    },

    /**
     * Convert an RGBA value to a 6 character hexadecimal value.
     *
     * @param rgba
     * @returns {string}
     */
    convertRgbaToHex(rgba) {
      rgba = this.custom_validation.getRgbaRegEx().exec(rgba);
      return "#" + ((1 << 24) + (parseInt(rgba.groups['r'], 10) << 16) + (parseInt(rgba.groups['g'], 10) << 8) + parseInt(rgba.groups['b'], 10)).toString(16).slice(1).toUpperCase();
    },

    convertKeywordToHex(keyword) {
      let colourList = this.keywordColours;
      let colour;
      if (typeof colourList[keyword] !== 'undefined') {
        colour = colourList[keyword];
      }
      return colour;
    }

  },
};
</script>

<style lang="scss">
.tui-inputColor {
  position: relative;

  @include tui-char-length-classes();

  // visually hide the colour Input control
  & &__picker[type='color'] {
    position: absolute;
    width: calc(var(--gap-8) + var(--gap-2));
    opacity: 0;

    &[disabled='disabled'] {
      cursor: not-allowed;
    }
  }

  // quite acute box model and positioning values to make sure they scale with
  // associated text Input values
  &__colorBlock {
    position: absolute;
    // prettier-ignore
    top: calc( var(--form-input-v-padding) / 2 + var(--form-input-border-size) );
    left: calc(var(--gap-2) / 2);
    width: var(--gap-8);
    // prettier-ignore
    height: calc(100% - var(--form-input-v-padding) - var(--form-input-border-size) * 2);
    border-radius: var(--border-radius-small);

    &--disabled {
      cursor: not-allowed;
    }
  }

  & &__input[type='text'] {
    padding-left: calc(var(--gap-8) + var(--gap-2));
  }
}
</style>
