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

  @author Jaron Steenson <jaron.steenson@totaralearning.com>
  @module mod_perform
-->

<template>
  <div class="tui-performUserActivities">
    <PageHeading :title="$str('user_activities_page_title', 'mod_perform')">
      <template v-if="canPotentiallyManageParticipants" v-slot:buttons>
        <Button
          :text="$str('manage_participation', 'mod_perform')"
          @click="openParticipationModal = true"
        />
      </template>
    </PageHeading>

    <ManualParticipantsSelectionBanner
      v-if="requireManualParticipantsNotification"
    />

    <div class="tui-performUserActivities__content">
      <Tabs :selected="initiallyOpenTab">
        <Tab
          v-for="tab in activityRoleTabs.tabs"
          :id="tab.id"
          :key="tab.id"
          :name="tab.name"
        >
          <UserActivityList
            :about-role="tab.id"
            :current-user-id="currentUserId"
            :filter-options="filterOptions"
            :is-about-others="tab.about_others"
            :print-url="printActivityUrl"
            :sort-by-options="sortByOptions"
            :view-url="viewActivityUrl"
          />
        </Tab>

        <Tab
          v-if="isHistoricActivitiesEnabled"
          :id="$id('your-historic-activities-tab')"
          :name="$str('user_activities_historic_activities', 'mod_perform')"
        >
          <UserHistoricActivityList :current-user-id="currentUserId" />
        </Tab>
      </Tabs>
    </div>

    <!--
      The v-if is important here because the query performed by the SelectActivityModal can be quite expensive
      so we only want to render/perform the component if we really need to
    -->
    <SelectActivityModal
      v-if="openParticipationModal"
      v-model="openParticipationModal"
    />
  </div>
</template>

<script>
import Button from 'tui/components/buttons/Button';
import ManualParticipantsSelectionBanner from 'mod_perform/components/user_activities/ManualParticipantsSelectionBanner';
import PageHeading from 'tui/components/layouts/PageHeading';
import SelectActivityModal from 'mod_perform/components/manage_activity/participation/SelectActivityModal';
import Tab from 'tui/components/tabs/Tab';
import Tabs from 'tui/components/tabs/Tabs';
import UserActivityList from 'mod_perform/components/user_activities/list/Activities';
import UserHistoricActivityList from 'mod_perform/components/user_activities/list/HistoricActivities';
import { notify } from 'tui/notifications';

export default {
  components: {
    Button,
    ManualParticipantsSelectionBanner,
    PageHeading,
    SelectActivityModal,
    Tab,
    Tabs,
    UserActivityList,
    UserHistoricActivityList,
  },
  props: {
    // Tabs for all roles with user visible activities
    activityRoleTabs: {
      required: true,
      type: Object,
    },
    canPotentiallyManageParticipants: {
      required: true,
      type: Boolean,
    },
    closedOnCompletion: {
      type: Boolean,
      default: false,
    },
    completionSaveSuccess: {
      required: true,
      type: Boolean,
    },
    // The id of the logged in user.
    currentUserId: {
      required: true,
      type: Number,
    },
    filterOptions: Object,
    // The id of the tab to be initially open
    initiallyOpenTab: Number,
    isHistoricActivitiesEnabled: {
      required: true,
      type: Boolean,
    },
    printActivityUrl: {
      required: true,
      type: String,
    },
    requireManualParticipantsNotification: {
      type: Boolean,
      default: false,
    },
    sortOptions: Object,
    viewActivityUrl: {
      required: true,
      type: String,
    },
  },

  data() {
    return {
      openParticipationModal: false,
    };
  },

  computed: {
    sortByOptions() {
      if (!this.sortOptions) {
        return null;
      }
      return this.sortOptions.options;
    },
  },

  mounted() {
    // Show the save notification if we have been redirected back here after saving.
    if (this.completionSaveSuccess) {
      let message = this.closedOnCompletion
        ? 'toast_success_save_close_on_completion_response'
        : 'toast_success_save_response';
      this.showSuccessNotification(message, 'mod_perform');
    }
  },

  methods: {
    /**
     * Shows a success toast.
     * @param {String} message
     * @param {String} component
     */
    showSuccessNotification(message, component) {
      notify({
        message: this.$str(message, component),
        type: 'success',
      });
    },
  },
};
</script>

<lang-strings>
  {
    "mod_perform": [
      "manage_participation",
      "toast_success_save_close_on_completion_response",
      "toast_success_save_response",
      "user_activities_historic_activities",
      "user_activities_page_title"
    ]
  }
</lang-strings>

<style lang="scss">
.tui-performUserActivities {
  @include tui-font-body();

  & > * + * {
    margin-top: var(--gap-4);
  }
}
</style>
