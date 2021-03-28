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
    class="tui-userPreferences"
    :title="$str('user_preferences_page_title', 'totara_notification')"
    :loading="$apollo.loading"
  >
    <template v-if="!$apollo.loading" v-slot:content>
      <UserPreferencesTable
        :event-resolvers="groupedPreferences"
        class="tui-userPreferences__table"
        @toggle-enabled="saveUserPreference"
      />
    </template>
  </Layout>
</template>
<script>
import Layout from 'tui/components/layouts/LayoutOneColumn';
import UserPreferencesTable from 'totara_notification/components/table/UserPreferencesTable';
import { notify } from 'tui/notifications';

// GraphQL
import updateUserPreferenceMutation from 'totara_notification/graphql/update_notifiable_event_user_preference';

export default {
  components: {
    Layout,
    UserPreferencesTable,
  },

  props: {
    resolverPreferences: {
      type: Array,
      required: true,
    },
    extendedContext: {
      type: Object,
      default() {
        // Just return empty object by default.
        return {};
      },
      validate(prop) {
        if (
          !('component' in prop) ||
          !('area' in prop) ||
          !('itemId' in prop)
        ) {
          return false;
        }

        if (prop.component !== '' || prop.area !== '' || prop.itemId != 0) {
          // We only accept all the fields to have value. Not either of the fields.
          return prop.component !== '' && prop.area !== '' && prop.itemId != 0;
        }

        return true;
      },
    },
  },

  data() {
    return {
      userPreferencesList: this.resolverPreferences,
    };
  },

  computed: {
    /**
     * Group the resolvers per component
     * @return {Array}
     */
    groupedPreferences() {
      let groupedResolvers = [];
      this.userPreferencesList.forEach(resolver => {
        const { component, plugin_name } = resolver;
        if (!groupedResolvers[component]) {
          groupedResolvers[component] = {
            component: component,
            plugin_name: plugin_name,
            resolvers: [],
          };
        }

        groupedResolvers[component].resolvers.push(resolver);
      });

      return Object.values(groupedResolvers);
    },
  },

  methods: {
    /**
     * @param {Number} userId
     * @param {String} resolverClassname
     * @param {Boolean} isEnabled
     * @param {Number} userPreferenceId
     */
    async saveUserPreference({
      userId,
      resolverClassname,
      isEnabled,
      userPreferenceId,
    }) {
      try {
        const { data: result } = await this.$apollo.mutate({
          mutation: updateUserPreferenceMutation,
          variables: {
            user_id: userId,
            resolver_class_name: resolverClassname,
            extended_context: this.extendedContext,
            is_enabled: isEnabled,
            user_preference_id: userPreferenceId,
          },
        });
        this.updateResolverPreferenceList(
          result.notifiable_event_user_preference
        );
      } catch (e) {
        await notify({
          type: 'error',
          message: this.$str(
            'error_user_preference_permission',
            'totara_notification'
          ),
        });
      }
    },

    /**
     * @param {Object} updatedUserPreference
     */
    updateResolverPreferenceList(updatedUserPreference) {
      this.userPreferencesList = this.userPreferencesList.map(resolver => {
        if (
          resolver.resolver_class_name ===
            updatedUserPreference.resolver_class_name &&
          resolver.component === updatedUserPreference.component &&
          resolver.name === updatedUserPreference.name &&
          resolver.plugin_name === updatedUserPreference.plugin_name
        ) {
          return updatedUserPreference;
        } else {
          return resolver;
        }
      });
    },
  },
};
</script>

<lang-strings>
{
  "totara_notification": [
    "error_user_preference_permission",
    "user_preferences_page_title"
  ]
}
</lang-strings>
