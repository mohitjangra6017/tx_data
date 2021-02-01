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
  <div class="tui-notificationHeader">
    <PageHeading :title="title" class="tui-notificationHeader__heading" />
    <ToggleSwitch
      :text="$str('disable_all', 'totara_notification')"
      :toggle-first="true"
    >
      <template v-slot:icon>
        <InfoIconButton
          :aria-label="
            $str(
              'helpfor',
              'totara_notification',
              $str('disable_all', 'totara_notification')
            )
          "
        >
          {{ $str('events_and_notifications', 'totara_notification') }}
        </InfoIconButton>
      </template>
    </ToggleSwitch>
  </div>
</template>

<script>
import ToggleSwitch from 'tui/components/toggle/ToggleSwitch';
import InfoIconButton from 'tui/components/buttons/InfoIconButton';
import PageHeading from 'tui/components/layouts/PageHeading';

export default {
  components: {
    ToggleSwitch,
    InfoIconButton,
    PageHeading,
  },

  props: {
    title: {
      type: String,
      required: true,
    },
  },
};
</script>
<lang-strings>
  {
    "totara_notification": [
      "disable_all",
      "events_and_notifications",
      "helpfor"
    ]
  }
</lang-strings>
<style lang="scss">
.tui-notificationHeader {
  &__heading {
    margin-bottom: var(--gap-4);
  }
}
</style>
