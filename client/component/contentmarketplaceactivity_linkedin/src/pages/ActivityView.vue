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
        const contentsTree = [];

        const { learning_object, module } = data;
        const has_course_view_page =
          module.course.course_format.has_course_view_page || false;
        const learningObject = {
          contentsTree: contentsTree,
          course: {
            name: module.course.fullname,
            url: has_course_view_page ? module.course.url : null,
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
