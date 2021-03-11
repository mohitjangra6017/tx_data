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
        @edit-notification="handleEditNotification"
        @delete-notification="confirmDeleteNotification"
      />
    </template>
    <template v-slot:modals>
      <ModalPresenter
        :open="modal.open"
        @request-close="modal.open = false"
        @close-complete="resetState"
      >
        <NotificationPreferenceModal
          :context-id="contextId"
          :event-class-name="targetEventClassName"
          :preference="targetPreference || undefined"
          :parent-value="
            targetPreference ? targetPreference.parent_value : null
          "
          :title="modal.title"
          :valid-schedule-types="targetScheduleTypes"
          :available-recipients="targetAvailableRecipients"
          @form-submit="handleFormSubmit"
        />
      </ModalPresenter>

      <DeleteConfirmationModal
        :open="deleteModal.open"
        :title="deleteNotificationTitle"
        :confirm-button-text="$str('delete', 'core')"
        :loading="deleting"
        @confirm="deleteNotification"
        @cancel="deleteModal.open = false"
      >
        <template>
          <p>{{ $str('delete_confirm_message', 'totara_notification') }}</p>
        </template>
      </DeleteConfirmationModal>
    </template>
  </Layout>
</template>
<script>
import Layout from 'tui/components/layouts/LayoutOneColumn';
import NotificationTable from 'totara_notification/components/table/NotificationTable';
import ModalPresenter from 'tui/components/modal/ModalPresenter';
import NotificationPreferenceModal from 'totara_notification/components/modal/NotificationPreferenceModal';
import DeleteConfirmationModal from 'tui/components/modal/ConfirmationModal';
import { notify } from 'tui/notifications';

// GraphQL queries.
import getNotifiableEvents from 'totara_notification/graphql/notifiable_events';
import createCustomNotification from 'totara_notification/graphql/create_custom_notification_preference';
import overrideNotification from 'totara_notification/graphql/override_notification_preference';
import updateNotification from 'totara_notification/graphql/update_notification_preference';
import deleteNotification from 'totara_notification/graphql/delete_notification_preference';

const MODAL_STATE_CREATE = 'create';
const MODAL_STATE_UPDATE = 'update';

export default {
  components: {
    Layout,
    NotificationTable,
    ModalPresenter,
    NotificationPreferenceModal,
    DeleteConfirmationModal,
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
          const { component, plugin_name, recipients } = notifiable_event;
          if (!result[component]) {
            result[component] = {
              component: component,
              plugin_name: plugin_name,
              events: [],
              recipients: recipients,
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
      deleteModal: {
        open: false,
      },
      deleting: false,
      targetEventClassName: null,
      targetDeletePreference: null,
      targetPreference: null,
      targetScheduleTypes: [],
      targetAvailableRecipients: [],
    };
  },

  computed: {
    deleteNotificationTitle() {
      let title = '';
      if (this.targetDeletePreference) {
        title = this.$str(
          'delete_confirm_title',
          'totara_notification',
          this.targetDeletePreference.title
        );
      }
      return title;
    },
  },

  methods: {
    /**
     * A method to call when we are closing down the modal.
     * As this function will try to reset the state of several variables,
     * when the modal is closed.
     */
    resetState() {
      // Reset the target event class name.
      this.targetEventClassName = null;

      // Then the target preference.
      this.targetPreference = null;

      // Reset the modal title
      this.modal.title = '';

      // Reset the target schedule types
      this.targetScheduleTypes = [];
    },

    /**
     * @param {String} eventClassName
     * @param {Array} scheduleTypes
     * @param {Array} recipients
     */
    handleCreateCustomNotification({
      eventClassName,
      scheduleTypes,
      recipients,
    }) {
      this.modal.title = this.$str(
        'create_custom_notification_title',
        'totara_notification'
      );
      this.modal.open = true;
      this.modal.state = MODAL_STATE_CREATE;

      this.targetEventClassName = eventClassName;
      this.targetScheduleTypes = scheduleTypes;
      this.targetAvailableRecipients = recipients;
    },

    /**
     * @param {Object} oldPreference
     * @param scheduleTypes
     * @param {Array} recipients
     */
    async handleEditNotification(oldPreference, scheduleTypes, recipients) {
      this.targetPreference = await this.getOverriddenPreference(oldPreference);
      this.targetEventClassName = this.targetPreference.event_class_name;
      this.targetScheduleTypes = scheduleTypes;
      this.targetAvailableRecipients = recipients;

      this.modal.title = this.$str('edit_notification', 'totara_notification');
      this.modal.open = true;
      this.modal.state = MODAL_STATE_UPDATE;
    },

    /**
     * Delete notifications
     */
    async deleteNotification() {
      try {
        await this.handleDeleteNotification(this.targetDeletePreference);
        this.deleteModal.open = false;
        notify({
          type: 'success',
          message: this.$str('delete_success', 'totara_notification'),
        });
      } catch (e) {
        await notify({
          type: 'error',
          message: this.$str(
            'error_cannot_delete_custom_notification',
            'totara_notification'
          ),
        });
      }
    },

    /**
     * @param {Object} notificationPreference
     */
    async handleDeleteNotification(notificationPreference) {
      await this.$apollo.mutate({
        mutation: deleteNotification,
        variables: {
          id: notificationPreference.id,
        },
        update: proxy => {
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

                  notifiableEvent.notification_preferences = preferences.filter(
                    preference => preference.id !== notificationPreference.id
                  );
                }
                return notifiableEvent;
              }),
            },
          });
        },
      });
    },

    /**
     * @param {Object} deletePreference
     */
    confirmDeleteNotification(notificationPreference) {
      this.targetDeletePreference = notificationPreference;
      this.deleteModal.open = true;
    },

    /**
     * @param {Object} formValue
     */
    async handleFormSubmit(formValue) {
      try {
        if (this.modal.state === MODAL_STATE_CREATE) {
          await this.createCustomNotification(formValue);
        } else if (this.modal.state === MODAL_STATE_UPDATE) {
          await this.updateNotification(formValue);
        } else {
          throw new Error('The modal state is invalid');
        }

        this.modal.open = false;
      } catch (e) {
        console.error(e);

        await notify({
          type: 'error',
          message:
            this.modal.state === MODAL_STATE_CREATE
              ? this.$str(
                  'error_cannot_create_custom_notification',
                  'totara_notification'
                )
              : this.$str(
                  'error_cannot_update_notification',
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
     * @param {String} schedule_type
     * @param {Number} schedule_offset
     * @param {Number} subject_format
     * @param {String} recipient
     * @param {Object} extended_context
     */
    async createCustomNotification({
      subject,
      body,
      title,
      body_format,
      event_class_name,
      schedule_type,
      schedule_offset,
      subject_format,
      recipient,
      extended_context,
    }) {
      const { item_id, area, component, context_id } = extended_context;
      let variables = {
        body,
        subject,
        title,
        body_format,
        event_class_name,
        subject_format,
        context_id: context_id ? context_id : this.contextId,
        schedule_type,
        schedule_offset,
        recipient,
      };

      // When area, component and item id are all set together, we pass them to mutation.
      if (!!area && !!component && !!item_id) {
        variables.area = area;
        variables.component = component;
        variables.item_id = item_id;
      }

      await this.$apollo.mutate({
        mutation: createCustomNotification,
        variables,
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

    /**
     * @param {Object} oldPreference
     * @return {Object}
     */
    async getOverriddenPreference(oldPreference) {
      if (oldPreference.extended_context.context_id == this.contextId) {
        return oldPreference;
      }

      // The preference is in different context, hence we are going to
      // create a blank overridden notification preference record and use it.
      const {
        data: { notification_preference },
      } = await this.$apollo.mutate({
        mutation: overrideNotification,
        variables: {
          context_id: this.contextId,
          event_class_name: oldPreference.event_class_name,
          ancestor_id: oldPreference.ancestor_id
            ? oldPreference.ancestor_id
            : oldPreference.id,
        },
        update: (
          proxy,
          { data: { notification_preference: overriddenPreference } }
        ) => {
          const { notifiable_events: notifiableEvents } = proxy.readQuery({
            query: getNotifiableEvents,
            variables: { context_id: this.contextId },
          });

          const {
            component,
            event_class_name: className,
            parent_id: parentId,
          } = overriddenPreference;

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

                  notifiableEvent.notification_preferences = preferences.map(
                    oldPreference => {
                      return oldPreference.id == parentId
                        ? overriddenPreference
                        : oldPreference;
                    }
                  );
                }

                return notifiableEvent;
              }),
            },
          });
        },
      });

      return notification_preference;
    },

    /**
     * @param {String} subject
     * @param {String} title
     * @param {String} body
     * @param {Number} body_format
     * @param {String} schedule_type
     * @param {Number} schedule_offset
     * @param {Number} subject_format
     * @param {String} recipient
     */
    async updateNotification({
      subject,
      title,
      body,
      body_format,
      schedule_type,
      schedule_offset,
      subject_format,
      recipient,
    }) {
      if (!this.targetPreference) {
        throw new Error('Cannot run update while target preference is empty');
      }

      await this.$apollo.mutate({
        mutation: updateNotification,
        variables: {
          id: this.targetPreference.id,
          subject,
          body,
          body_format,
          subject_format,
          // Note that we don't want NULL here, but undefined, because we would want the graphql
          // to exclude the field title when updating a custom notification at a very specific context.
          title:
            this.targetPreference.is_custom && !this.targetPreference.parent_id
              ? title
              : undefined,
          schedule_type,
          schedule_offset,
          recipient,
        },
        update: (
          proxy,
          { data: { notification_preference: updatedPreference } }
        ) => {
          const { notifiable_events: notifiableEvents } = proxy.readQuery({
            query: getNotifiableEvents,
            variables: { context_id: this.contextId },
          });

          const { component, event_class_name: className } = updatedPreference;

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
                  notifiableEvent.notification_preferences = preferences.map(
                    oldPreference => {
                      return oldPreference.id == updatedPreference.id
                        ? updatedPreference
                        : oldPreference;
                    }
                  );
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
    "core": [
      "delete"
    ],
    "totara_notification": [
      "create_custom_notification_title",
      "delete_confirm_title",
      "delete_confirm_message",
      "delete_success",
      "error_cannot_create_custom_notification",
      "error_cannot_delete_custom_notification",
      "edit_notification",
      "error_cannot_update_notification"
    ]
  }
</lang-strings>
