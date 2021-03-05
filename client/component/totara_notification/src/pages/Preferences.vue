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

  @author Matthias Bonk <matthias.bonk@totaralearning.com>
  @module totara_notification
-->

<template>
  <Layout
    class="tui-preferences"
    :title="$str('preferences_page_title', 'totara_notification')"
    :loading="$apollo.loading"
  >
    <template v-if="!$apollo.loading" v-slot:content>
      <PreferencesTable
        :notifiable-events="notifiableEvents"
        :context-id="parseInt(contextId)"
        class="tui-preferences__table"
      />
    </template>
  </Layout>
</template>
<script>
import Layout from 'tui/components/layouts/LayoutOneColumn';
import PreferencesTable from 'totara_notification/components/table/PreferencesTable';

// GraphQL queries.
import getNotifiableEvents from 'totara_notification/graphql/notifiable_events';

export default {
  components: {
    Layout,
    PreferencesTable,
  },

  props: {
    currentUserId: {
      type: Number,
      required: true,
    },
    contextId: {
      type: [Number, String],
      required: true,
    },
  },

  apollo: {
    notifiableEvents: {
      query: getNotifiableEvents,
      variables() {
        return {
          context_id: this.contextId,
        };
      },

      update({ notifiable_events }) {
        let result = {};
        notifiable_events.forEach(notifiable_event => {
          const { component, plugin_name } = notifiable_event;
          if (!result[component]) {
            result[component] = {
              component: component,
              plugin_name: plugin_name,
              events: [],
            };
          }

          result[component].events.push(notifiable_event);
        });

        return Object.values(result);
      },
    },
  },

  data() {
    return {
      notifiableEvents: {},
    };
  },
};
</script>

<lang-strings>
{
  "totara_notification": [
    "preferences_page_title"
  ]
}
</lang-strings>
