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

  @author Steve Barnett <steve.barnett@totaralearning.com>
  @module totara_notification
-->

<template>
  <div class="tui-notificationPage">
    <NotificationHeader :title="title" />
    <Layout class="tui-notificationPage">
      <!--filter-->
      <template v-slot:left />
      <template v-if="!$apollo.loading" v-slot:right>
        <NotificationTable
          :notifiable-events="notifiableEvents"
          :context-id="parseInt(contextId)"
          class="tui-notificationPage__table"
          @created-custom-notification="createdCustomNotification"
        />
      </template>
    </Layout>
  </div>
</template>
<script>
import Layout from 'tui/components/layouts/LayoutTwoColumn';
import NotificationTable from 'totara_notification/components/table/NotificationTable';
import NotificationHeader from 'totara_notification/components/header/NotificationHeader';
import apolloClient from 'tui/apollo_client';

// GraphQL queries.
import getNotifiableEvents from 'totara_notification/graphql/notifiable_events';

export default {
  components: {
    Layout,
    NotificationTable,
    NotificationHeader,
  },

  props: {
    title: {
      type: String,
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

  methods: {
    /**
     * @return {Object[]}
     */
    $_getCachedNotifiableEvents() {
      const { notifiable_events: notifiableEvents } = apolloClient.readQuery({
        query: getNotifiableEvents,
        variables: { context_id: this.contextId },
      });

      return notifiableEvents;
    },
    /**
     *
     * @param {Object} notificationPreference
     */
    createdCustomNotification(notificationPreference) {
      const { component, event_class_name: className } = notificationPreference,
        notifiableEvents = this.$_getCachedNotifiableEvents();

      apolloClient.writeQuery({
        query: getNotifiableEvents,
        variables: {
          context_id: this.contextId,
        },
        data: {
          notifiable_events: notifiableEvents.map(notifiableEvent => {
            if (
              notifiableEvent.component === component &&
              notifiableEvent.class_name === className
            ) {
              notifiableEvent = Object.assign({}, notifiableEvent);

              const { notification_preferences: preferences } = notifiableEvent;
              notifiableEvent.notification_preferences = [
                notificationPreference,
              ].concat(preferences);
            }

            return notifiableEvent;
          }),
        },
      });
    },
  },
};
</script>
