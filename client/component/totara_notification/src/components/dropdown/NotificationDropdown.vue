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

  @author Qingyang.liu <qingyang.liu@totaralearning.com>
  @module totara_notification
-->
<template>
  <div class="tui-notificationDropdown">
    <div v-if="hasIconButton" class="tui-notificationDropdown__button">
      <slot name="icon" />
    </div>
    <Dropdown :position="position" :open="open">
      <template v-slot:trigger="{ toggle, isOpen }">
        <slot name="trigger" :toggle="toggle">
          <ButtonIcon
            :aria-expanded="isOpen ? 'true' : 'false'"
            :aria-label="$str('more', 'core')"
            :styleclass="{ small: true, transparentNoPadding: true }"
            @click="toggle"
          >
            <MoreIcon size="300" />
          </ButtonIcon>
        </slot>
      </template>
      <DropdownItem
        v-for="({ action, label }, i) in actions"
        :key="i"
        @click="action(title)"
      >
        {{ label }}
      </DropdownItem>
    </Dropdown>
  </div>
</template>

<script>
import Dropdown from 'tui/components/dropdown/Dropdown';
import DropdownItem from 'tui/components/dropdown/DropdownItem';
import MoreIcon from 'tui/components/icons/More';
import ButtonIcon from 'tui/components/buttons/ButtonIcon';

export default {
  components: {
    Dropdown,
    DropdownItem,
    MoreIcon,
    ButtonIcon,
  },

  props: {
    position: String,
    open: Boolean,
    actions: {
      type: Array,
      validator(prop) {
        let items = Array.prototype.filter.call(prop, item => {
          return !('label' in item) || !('action' in item);
        });

        return items.length === 0;
      },
    },
    title: String,
    hasIconButton: {
      type: Boolean,
      default: true,
    },
  },
};
</script>

<lang-strings>
{
  "core": [
    "more"
  ]
}
</lang-strings>
<style lang="scss">
.tui-notificationDropdown {
  display: flex;
  align-items: center;
}
</style>
