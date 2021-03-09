<!--
  This file is part of Totara Enterprise Extensions.

  Copyright (C) 2021 onwards Totara Learning Solutions LTD

  Totara Enterprise Extensions is provided only to Totara
  Learning Solutions LTD’s customers and partners, pursuant to
  the terms and conditions of a separate agreement with Totara
  Learning Solutions LTD or its affiliate.

  If you do not have an agreement with Totara Learning Solutions
  LTD, you may not access, use, modify, or distribute this software.
  Please contact [licensing@totaralearning.com] for more information.

  @author Kian Nguyen <kian.nguyen@totaralearning.com>
  @module totara_notification
-->
<template>
  <div class="tui-notifiableEventAction">
    <!-- Quick add button icon -->
    <div>
      <!-- This is to fix between block drop down. Which help to align buttons correctly. -->
      <ButtonIcon
        :styleclass="{ small: true, transparentNoPadding: true }"
        :aria-label="$str('create_notification', 'totara_notification')"
        @click="$emit('create-custom-notification')"
      >
        <AddIcon :size="300" />
      </ButtonIcon>
    </div>
    <!-- Drop down button -->
    <Dropdown>
      <template v-slot:trigger="{ toggle, isOpen }">
        <ButtonIcon
          :styleclass="{ small: true, transparentNoPadding: true }"
          :aria-expanded="isOpen ? 'true' : 'false'"
          :aria-label="$str('more', 'core')"
          @click="toggle"
        >
          <MoreIcon :size="300" />
        </ButtonIcon>
      </template>

      <DropdownItem
        :aria-label="
          $str(
            'create_notification_for_event',
            'totara_notification',
            resolverName
          )
        "
        @click="$emit('create-custom-notification')"
      >
        {{ $str('create_notification', 'totara_notification') }}
      </DropdownItem>
    </Dropdown>
  </div>
</template>

<script>
import Dropdown from 'tui/components/dropdown/Dropdown';
import DropdownItem from 'tui/components/dropdown/DropdownItem';
import ButtonIcon from 'tui/components/buttons/ButtonIcon';
import MoreIcon from 'tui/components/buttons/MoreIcon';
import AddIcon from 'tui/components/icons/Add';

export default {
  components: {
    DropdownItem,
    Dropdown,
    ButtonIcon,
    MoreIcon,
    AddIcon,
  },

  props: {
    resolverName: {
      type: String,
      required: true,
    },
  },
};
</script>

<lang-strings>
  {
    "core": [
      "more"
    ],
    "totara_notification": [
      "create_notification",
      "create_notification_for_event"
    ]
  }
</lang-strings>

<style lang="scss">
.tui-notifiableEventAction {
  display: flex;
  align-items: center;
}
</style>
