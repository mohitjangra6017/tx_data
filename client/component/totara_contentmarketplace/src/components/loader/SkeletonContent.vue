<!--
  This file is part of Totara Enterprise Extensions.

  Copyright (C) 2021 onwards Totara Learning Solutions LTD

  Totara Enterprise Extensions is provided only to Totara
  Learning Solutions LTD's customers and partners, pursuant to
  the terms and conditions of a separate agreement with Totara
  Learning Solutions LTD or its affiliate.

  If you do not have an agreement with Totara Learning Solutions
  LTD, you may not access, use, modify, or distribute this software.
  Please contact [licensing@totaralearning.com] for more information.

  @author Kevin Hottinger <kevin.hottinger@totaralearning.com>
  @package totara_contentmarketplace
-->

<template>
  <div
    class="tui-contentMarketplaceSkeleton"
    :class="[
      charLength && 'tui-contentMarketplaceSkeleton--charLength-' + charLength,
      hasOverlay && 'tui-contentMarketplaceSkeleton--hasOverlay',
    ]"
  >
    &nbsp;
  </div>
</template>

<script>
import { charLengthProp } from 'tui/components/form/form_common';

export default {
  props: {
    charLength: charLengthProp,
    hasOverlay: Boolean,
  },
};
</script>

<style lang="scss">
.tui-contentMarketplaceSkeleton {
  position: relative;
  display: flex;
  width: 100%;
  height: 100%;
  overflow: hidden;
  background: var(--color-neutral-3);

  @include tui-char-length-classes();

  &--hasOverlay {
    background: var(--color-neutral-5);
  }
}
</style>
