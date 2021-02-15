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
  <Layout
    class="tui-notificationPage"
    :title="title"
    :loading="$apollo.loading"
  >
    <template v-if="!$apollo.loading" v-slot:content>
      <NotificationTable
        :notifiable-events="notifiableEvents"
        :context-id="parseInt(contextId)"
        class="tui-notificationPage__table"
        @create-custom-notification="handleCreateCustomNotification"
      />
    </template>
    <template v-slot:modals>
      <ModalPresenter :open="modal.open" @request-close="modal.open = false">
        <NotificationPreferenceModal
          :context-id="contextId"
          :event-class-name="targetEventClassName"
          :preference="targetPreference || undefined"
          :parent-value="
            targetPreference ? targetPreference.parent_value : null
          "
          :title="modal.title"
          @form-submit="handleFormSubmit"
        />
      </ModalPresenter>
    </template>
  </Layout>
</template>
<script>
import Layout from 'tui/components/layouts/LayoutOneColumn';
import NotificationTable from 'totara_notification/components/table/NotificationTable';
import ModalPresenter from 'tui/components/modal/ModalPresenter';
import NotificationPreferenceModal from 'totara_notification/components/modal/NotificationPreferenceModal';
import { notify } from 'tui/notifications';

// GraphQL queries.
import getNotifiableEvents from 'totara_notification/graphql/notifiable_events';
import createCustomNotification from 'totara_notification/graphql/create_custom_notification_preference';

const MODAL_STATE_CREATE = 'create';

export default {
  components: {
    Layout,
    NotificationTable,
    ModalPresenter,
    NotificationPreferenceModal,
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
      modal: {
        open: false,
        title: '',
        state: null,
      },
      targetEventClassName: null,
      targetPreference: null,
    };
  },

  watch: {
    modal: {
      deep: true,
      handler({ open }) {
        if (!open) {
          // Reset the target preference everytime the modal is closed.
          this.targetPreference = null;
          this.targetEventClassName = null;
        }
      },
    },
  },

  methods: {
    /**
     * @param {String} eventClassName
     * @param {String} eventName
     */
    handleCreateCustomNotification({ eventClassName, eventName }) {
      this.modal.title = this.$str(
        'create_custom_notification_title',
        'totara_notification',
        eventName
      );
      this.modal.open = true;
      this.modal.state = MODAL_STATE_CREATE;

      this.targetEventClassName = eventClassName;
    },

    /**
     * @param {Object} formValue
     */
    async handleFormSubmit(formValue) {
      try {
        if (this.modal.state === MODAL_STATE_CREATE) {
          await this.createCustomNotification(formValue);
        } else {
          throw new Exception('The modal state is invalid');
        }

        this.modal.open = false;
        this.modal.title = '';
      } catch (e) {
        console.error(e);

        await notify({
          type: 'error',
          message: this.$str(
            'error_cannot_create_custom_notification',
            'totara_notification'
          ),
        });
      }
    },

    /**
     * @param {String} subject
     * @param {String} body
     * @param {String} title
     * @param {Number} body_format
     * @param {String} event_class_name
     */
    async createCustomNotification({
      subject,
      body,
      title,
      body_format,
      event_class_name,
    }) {
      await this.$apollo.mutate({
        mutation: createCustomNotification,
        variables: {
          body,
          subject,
          title,
          body_format,
          event_class_name,
          context_id: this.contextId,
        },
        update: (
          proxy,
          { data: { notification_preference: notificationPreference } }
        ) => {
          const { notifiable_events: notifiableEvents } = proxy.readQuery({
            query: getNotifiableEvents,
            variables: { context_id: this.contextId },
          });

          const {
            component,
            event_class_name: className,
          } = notificationPreference;

          proxy.writeQuery({
            query: getNotifiableEvents,
            variables: { context_id: this.contextId },
            data: {
              notifiable_events: notifiableEvents.map(notifiableEvent => {
                if (
                  notifiableEvent.component === component &&
                  notifiableEvent.class_name === className
                ) {
                  notifiableEvent = Object.assign({}, notifiableEvent);

                  const {
                    notification_preferences: preferences,
                  } = notifiableEvent;
                  notifiableEvent.notification_preferences = [
                    notificationPreference,
                  ].concat(preferences);
                }

                return notifiableEvent;
              }),
            },
          });
        },
      });
    },
  },
};
</script>

<lang-strings>
  {
    "totara_notification": [
      "create_custom_notification_title",
      "error_cannot_create_custom_notification"
    ]
  }
</lang-strings>
