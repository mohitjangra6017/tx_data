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
  @package contentmarketplaceactivity_linkedin
-->
<template>
  <Layout
    v-if="activity"
    class="tui-linkedinActivity"
    :banner-image-url="activity.image"
    :loading-full-page="$apollo.loading"
    :title="activity.name"
  >
    <template v-if="activity.course.url" v-slot:content-nav>
      <PageBackLink :link="activity.course.url" :text="activity.course.name" />
    </template>

    <!-- Side panel area -->
    <template v-slot:side-panel="{ stacked }">
      <!-- If side panel has been stacked in layout-->

      <Collapsible
        v-if="stacked"
        :label="$str('activity_contents', 'mod_contentmarketplace')"
      >
        <div class="tui-linkedinActivity__collapsibleContent">
          <ContentTree
            v-model="openContents"
            :tree-data="activity.contentsTree"
          />
        </div>
      </Collapsible>

      <ContentTree
        v-else
        v-model="openContents"
        :tree-data="activity.contentsTree"
      />
    </template>

    <!-- Banner image content area -->
    <template v-slot:banner-content="{ stacked }">
      <div class="tui-linkedinActivity__admin">
        <AdminMenu :stacked-layout="stacked" />
      </div>
    </template>

    <!-- Notification banner (Guest/enrolment message) -->
    <template v-slot:feedback-banner>
      <NotificationBanner v-if="guest && canEnrol" type="info">
        <template v-slot:body>
          <ActionCard :no-border="true">
            <template v-slot:card-body>
              {{
                $str('viewing_as_enrollable_guest', 'mod_contentmarketplace')
              }}
            </template>
            <template v-slot:card-action>
              <Button
                :styleclass="{ primary: 'true' }"
                :text="$str('enrol', 'mod_contentmarketplace')"
              />
            </template>
          </ActionCard>
        </template>
      </NotificationBanner>

      <NotificationBanner
        v-else-if="guest"
        :message="$str('viewing_as_guest', 'mod_contentmarketplace')"
        type="info"
      />
    </template>

    <template v-slot:main-content>
      <div class="tui-linkedinActivity__body">
        <Button
          :disabled="guest"
          :styleclass="{ primary: 'true' }"
          :text="$str('launch_in_new_window', 'mod_contentmarketplace')"
        />

        <!-- Current status and self completion -->
        <div class="tui-linkedinActivity__status">
          <div class="tui-linkedinActivity__status-completion">
            <Lozenge :text="activity.status" />
          </div>

          <ToggleSwitch
            v-model="setCompletion"
            class="tui-linkedinActivity__status-toggle"
            :text="
              $str('activity_set_self_completion', 'mod_contentmarketplace')
            "
            :toggle-first="true"
            @input="setCompletionHandler"
          />
        </div>
        <div class="tui-linkedinActivity__details">
          <h3 class="tui-linkedinActivity__details-header">
            {{ $str('course_details', 'mod_contentmarketplace') }}
          </h3>
          <div class="tui-linkedinActivity__details-content">
            <div class="tui-linkedinActivity__details-bar">
              <!-- Course completion time -->
              <div>
                <span class="sr-only">
                  {{
                    $str(
                      'a11y_activity_time_to_complete',
                      'mod_contentmarketplace'
                    )
                  }}
                </span>
                {{ activity.timeToComplete }}
              </div>
              <!-- Course level (Beginner, intermediate, advanced) -->
              <div>
                <span class="sr-only">
                  {{
                    $str('a11y_activity_difficulty', 'mod_contentmarketplace')
                  }}
                </span>
                {{ activity.levelString }}
              </div>
              <!-- Last updated  -->
              <div>
                {{
                  $str('updated_at', 'mod_contentmarketplace', activity.updated)
                }}
              </div>
            </div>

            <div
              class="tui-linkedinActivity__details-desc"
              v-html="activity.description"
            />
          </div>
        </div>
      </div>
    </template>
  </Layout>
</template>

<script>
import ActionCard from 'tui/components/card/ActionCard';
import AdminMenu from 'mod_contentmarketplace/components/administration/AdminMenu';
import Button from 'tui/components/buttons/Button';
import Collapsible from 'tui/components/collapsible/Collapsible';
import ContentTree from 'mod_contentmarketplace/components/side_panel/LinkedInActivityContentsTree';
import Layout from 'mod_contentmarketplace/components/layouts/LayoutBannerSidepanelTwoColumn';
import Lozenge from 'tui/components/lozenge/Lozenge';
import NotificationBanner from 'tui/components/notifications/NotificationBanner';
import PageBackLink from 'tui/components/layouts/PageBackLink';
import ToggleSwitch from 'tui/components/toggle/ToggleSwitch';
// Utils
import { getAllNodeKeys } from 'tui/components/tree/util';
import { notify } from 'tui/notifications';

// GraphQL
import LinkedinActivityQuery from 'contentmarketplaceactivity_linkedin/graphql/linkedin_activity';
import toggleSelfCompletionMutation from 'mod_contentmarketplace/graphql/set_self_completion';

export default {
  components: {
    ActionCard,
    AdminMenu,
    Button,
    Collapsible,
    ContentTree,
    Layout,
    Lozenge,
    NotificationBanner,
    PageBackLink,
    ToggleSwitch,
  },

  props: {
    /**
     * The course's module id, not the content marketplace id.
     */
    cmId: {
      type: Number,
      required: true,
    },
  },

  data() {
    return {
      adminTree: [
        {
          id: 'marketplaceModuleAdministration',
          label: 'Content marketplace module administration',
          children: [
            {
              id: 'editSettings',
              label: 'Edit settings',
              linkUrl: '#editSettings',
            },
            {
              id: 'locallyAssignedRoles',
              label: 'Locally assigned roles',
              linkUrl: '#locallyAssignedRoles',
            },
            {
              id: 'Permissions',
              label: 'Permissions',
              linkUrl: '#Permissions',
            },
            {
              id: 'checkPermissions',
              label: 'Check permissions',
              linkUrl: '#checkPermissions',
            },
            {
              id: 'Filters',
              label: 'Filters',
              linkUrl: '#Filters',
            },
            {
              id: 'Logs',
              label: 'Logs',
              linkUrl: '#Logs',
            },
          ],
        },
        {
          id: 'courseAdministration',
          label: 'Course administration',
          children: [
            {
              id: 'editSettings',
              label: 'Edit settings',
              linkUrl: '#editSettings',
            },
            {
              id: 'turnEditingOn',
              label: 'Turn editing on',
              linkUrl: '#turnEditingOn',
            },
            {
              id: 'courseCompletion',
              label: 'Course completion',
              linkUrl: '#courseCompletion',
            },
            {
              id: 'completionsArchive',
              label: 'Completions archive',
              linkUrl: '#completionsArchive',
            },
            {
              id: 'completionEditor',
              label: 'Completion editor',
              linkUrl: '#completionEditor',
            },
            {
              id: 'reminders',
              label: 'Reminders',
              linkUrl: '#reminders',
            },
            {
              id: 'courseAdministrationUsers',
              label: 'Users',
              children: [
                {
                  id: 'EnrolledUsers',
                  label: 'Enrolled users',
                  linkUrl: '#EnrolledUsers',
                },
                {
                  id: 'courseAdministrationUserEnrolmentMethods',
                  label: 'Enrolment methods',
                  children: [
                    {
                      id: 'ManualEnrolments',
                      label: 'Manual enrolments',
                      linkUrl: '#ManualEnrolments',
                    },
                    {
                      id: 'GuestAccess',
                      label: 'Guest access',
                      linkUrl: '#GuestAccess',
                    },
                    {
                      id: 'SelfEnrolmentLearner',
                      label: 'Self enrolment (Learner)',
                      linkUrl: '#SelfEnrolmentLearner',
                    },
                  ],
                },
                {
                  id: 'Groups',
                  label: 'Groups',
                  linkUrl: '#Groups',
                },
                {
                  id: 'courseAdministrationUserPermissions',
                  label: 'Permissions',
                  linkUrl: '#Permissions',
                  children: [
                    {
                      id: 'CheckPermissions',
                      label: 'Check permissions',
                      linkUrl: '#CheckPermissions',
                    },
                  ],
                },
                {
                  id: 'OtherUsers',
                  label: 'Other users',
                  linkUrl: '#OtherUsers',
                },
              ],
            },
            {
              id: 'enrolmentOptions',
              label: 'Enrolment options',
              linkUrl: '#enrolmentOptions',
            },
            {
              id: 'filters',
              label: 'Filters',
              linkUrl: '#Filters',
            },
            {
              id: 'courseAdministrationReports',
              label: 'Reports',
              children: [
                {
                  id: 'Logs',
                  label: 'Logs',
                  linkUrl: '#Logs',
                },
                {
                  id: 'LiveLogs',
                  label: 'Live logs',
                  linkUrl: '#LiveLogs',
                },
                {
                  id: 'ActivityReport',
                  label: 'Activity report',
                  linkUrl: '#ActivityReport',
                },
                {
                  id: 'CourseParticipation',
                  label: 'Course participation',
                  linkUrl: '#CourseParticipation',
                },
                {
                  id: 'ActivityCompletion',
                  label: 'Activity completion',
                  linkUrl: '#ActivityCompletion',
                },
              ],
            },
            {
              id: 'grades',
              label: 'Grades',
              linkUrl: '#Grades',
            },
            {
              id: 'GradebookSetup',
              label: 'Gradebook setup',
              linkUrl: '#GradebookSetup',
            },
            {
              id: 'courseAdministrationBadges',
              label: 'Badges',
              children: [
                {
                  id: 'ManageBadges',
                  label: 'Manage badges',
                  linkUrl: '#ManageBadges',
                },
                {
                  id: 'AddANewBadge',
                  label: 'Add a new badge',
                  linkUrl: '#AddANewBadge',
                },
              ],
            },
            {
              id: 'Backup',
              label: 'Backup',
              linkUrl: '#Backup',
            },
            {
              id: 'Restore',
              label: 'Restore',
              linkUrl: '#Restore',
            },
            {
              id: 'Import',
              label: 'Import',
              linkUrl: '#Import',
            },
            {
              id: 'Reset',
              label: 'Reset',
              linkUrl: '#Reset',
            },
            {
              id: 'courseAdministrationQuestionBank',
              label: 'Question bank',
              linkUrl: '#questionBank',
              children: [
                {
                  id: 'Questions',
                  label: 'Questions',
                  linkUrl: '#Questions',
                },
                {
                  id: 'Categories',
                  label: 'Categories',
                  linkUrl: '#Categories',
                },
                {
                  id: 'Import',
                  label: 'Import',
                  linkUrl: '#Import',
                },
                {
                  id: 'Export',
                  label: 'Export',
                  linkUrl: '#Export',
                },
              ],
            },
            {
              id: 'courseAdministrationSwitchRoles',
              label: 'Switch role to...',
              linkUrl: '#roles',
              children: [
                {
                  id: 'siteManager',
                  label: 'Site Manager',
                  linkUrl: '#siteManager',
                },
                {
                  id: 'courseCreator',
                  label: 'Course creator',
                  linkUrl: '#courseCreator',
                },
                {
                  id: 'Trainer',
                  label: 'Trainer',
                  linkUrl: '#Trainer',
                },
                {
                  id: 'Learner',
                  label: 'Learner',
                  linkUrl: '#Learner',
                },
                {
                  id: 'Guest',
                  label: 'Guest',
                  linkUrl: '#Guest',
                },
              ],
            },
          ],
        },
      ],
      // Can self enrol in activity
      canEnrol: true,
      // Viewing activity as guest (not enrolled in activity)
      guest: true,
      setCompletion: false,
      // Open nodes of contents tree
      openContents: [],
    };
  },

  apollo: {
    activity: {
      query: LinkedinActivityQuery,
      variables() {
        return {
          cm_id: this.cmId,
        };
      },
      update({ instance: data }) {
        const contentsTree = [
          {
            id: 'introduction',
            label: 'Introduction',
            content: {
              items: ['Your video conference presence'],
            },
          },
          {
            id: 'proTips',
            label: '1. Video conferencing pro tips',
            content: {
              items: [
                'Expectations and preparations',
                'Introducing yourself and speaking up',
                'Keeping it engaging',
                'Using visuals to support your content',
                'Enhancing your conversation',
                'Chapter quiz',
              ],
            },
          },
          {
            id: 'bodyLanguage',
            label: '2. Video body language',
            content: {
              items: [
                'Perfecting eye contact',
                'Hand gestures on video',
                'Using confident body posture',
                'Wardrobe choices',
                'Chapter quiz',
                '3 questions',
              ],
            },
          },
          {
            id: 'technicals',
            label: '3. The technicals: Preparing your studio',
            content: {
              items: [
                'Positioning your camera',
                'Lighting is the key',
                'Crisp and clear audio',
                'Backdrops and backgrounds',
                'Tips to help you stand out',
                'Chapter quiz',
                '2 questions',
              ],
            },
          },
          {
            id: 'conclusion',
            label: 'Conclusion',
            content: {
              items: ['Practice run'],
            },
          },
        ];

        const { learning_object, module } = data;
        const learningObject = {
          contentsTree: contentsTree,
          course: {
            name: 'Super cool course',
            url: '#',
          },
          description: module.course.summary,
          image: module.course.image,
          levelString: learning_object.level,
          name: module.course.fullname,
          status: this.$str(
            'activity_status_not_started',
            'mod_contentmarketplace'
          ),
          timeToComplete: learning_object.time_to_complete,
          updated: learning_object.last_updated_at,
        };

        this.setCompletion = module.completion_condition || false;
        this.openContents = getAllNodeKeys(contentsTree);

        return learningObject;
      },
    },
  },
  methods: {
    async setCompletionHandler() {
      try {
        let {
          data: { result },
        } = await this.$apollo.mutate({
          mutation: toggleSelfCompletionMutation,
          refetchAll: false,
          variables: {
            cm_id: this.cmId,
            status: this.setCompletion,
          },
        });

        this.setCompletion = result;
      } catch (e) {
        notify({
          message: this.$str(
            this.setCompletion ? 'toggle_on_error' : 'toggle_off_error',
            'mod_contentmarketplace'
          ),
          type: 'error',
        });
      }
    },
  },
};
</script>

<lang-strings>
  {
    "mod_contentmarketplace": [
      "a11y_activity_difficulty",
      "a11y_activity_time_to_complete",
      "activity_contents",
      "activity_set_self_completion",
      "activity_status_completed",
      "activity_status_in_progress",
      "activity_status_not_started",
      "course_details",
      "enrol",
      "launch_in_new_window",
      "toggle_off_error",
      "toggle_on_error",
      "updated_at",
      "viewing_as_guest",
      "viewing_as_enrollable_guest"
    ]
  }
</lang-strings>

<style lang="scss">
.tui-linkedinActivity {
  &__admin {
    margin-left: auto;
  }

  &__body {
    & > * + * {
      margin-top: var(--gap-5);
    }
  }

  &__collapsibleContent {
    padding: 0 var(--gap-10);
  }

  &__status {
    display: flex;
    flex-wrap: wrap;
    align-items: center;

    & > * {
      padding-bottom: var(--gap-1);
    }

    &-completion {
      margin-right: var(--gap-4);
    }

    &-toggle {
      flex-wrap: wrap;
    }
  }

  &__details {
    margin-top: var(--gap-9);

    & > * + * {
      margin-top: var(--gap-2);
    }

    &-header {
      margin: 0;
      @include tui-font-heading-small();
    }

    &-bar {
      @include tui-separator-dot();
      color: var(--color-neutral-6);
      @include tui-font-heading-label();
    }

    &-content {
      & > * + * {
        margin-top: var(--gap-4);
      }
    }
  }
}
</style>
