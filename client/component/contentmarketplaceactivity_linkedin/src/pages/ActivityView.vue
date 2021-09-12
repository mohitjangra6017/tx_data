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
      <NotificationBanner v-if="canEnrolActivity" type="info">
        <template v-slot:body>
          <ActionCard :no-border="true">
            <template v-slot:card-body>
              {{ displayBannerInfo }}
            </template>
            <template v-slot:card-action>
              <!--
                Using title for button, to allow Selenium finding this button.
                This is happening for admin user, because admin user can see more
                than one enrol button.
              -->
              <Button
                v-if="displayEnrolButton"
                :styleclass="{ primary: 'true' }"
                :title="
                  $str(
                    'enrol_to_course',
                    'mod_contentmarketplace',
                    activity.course.name
                  )
                "
                :text="$str('enrol', 'core_enrol')"
                @click="requestSelfEnrol"
              />
            </template>
          </ActionCard>
        </template>
      </NotificationBanner>

      <NotificationBanner
        v-else-if="interactor.isSiteGuest"
        :message="$str('viewing_as_guest', 'mod_contentmarketplace')"
        type="info"
      />
    </template>

    <template v-slot:main-content>
      <div class="tui-linkedinActivity__body">
        <Button
          :disabled="launchInNewWindowDisabled"
          :styleclass="{ primary: 'true' }"
          :text="$str('launch_in_new_window', 'mod_contentmarketplace')"
          @click="launchNewWindow"
        />

        <!-- Current status and self completion -->
        <div
          v-if="activity.completionEnabled && interactor.isEnrolled"
          class="tui-linkedinActivity__status"
        >
          <div class="tui-linkedinActivity__status-completion">
            <Lozenge :text="activity.status" />
          </div>

          <!-- Display the completion toggle when there is self completion enabled. -->
          <ToggleSwitch
            v-if="activity.selfCompletion"
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
import setSelfCompletionMutation from 'mod_contentmarketplace/graphql/set_self_completion';
import requestSelfEnrol from 'mod_contentmarketplace/graphql/request_self_enrol';

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
      interactor: {
        // Can self enrol in activity
        canEnrol: false,
        // Viewing activity as guest (not enrolled in activity)
        canLaunch: false,
        isAdmin: false,
        isEnrolled: false,
        isSiteGuest: false,
      },
      setCompletion: false,
      // Open nodes of contents tree
      openContents: [],
      selfEnrolEnabled: false,
      guestEnrolEnabled: false,
      selfEnrolEnabledWithRequiredKey: {
        redirectUrl: '',
        enabled: false,
      },
      webLaunchUrl: null,
      ssoLaunchUrl: null,
    };
  },

  computed: {
    canEnrolActivity() {
      const { canEnrol, isSiteGuest } = this.interactor;
      return canEnrol && !isSiteGuest;
    },

    launchInNewWindowDisabled() {
      const { canEnrol, isSiteGuest, canLaunch } = this.interactor;
      if (!canLaunch) {
        return true;
      }

      if (isSiteGuest) {
        return true;
      }

      return canEnrol;
    },

    displayBannerInfo() {
      const { isAdmin, isSiteGuest } = this.interactor;

      if (isAdmin) {
        return this.selfEnrolEnabled
          ? this.$str('viewing_as_enrollable_admin', 'mod_contentmarketplace')
          : this.$str(
              'viewing_as_enrollable_admin_self_enrol_disabled',
              'mod_contentmarketplace'
            );
      }

      return this.guestEnrolEnabled && this.selfEnrolEnabled && !isSiteGuest
        ? this.$str('viewing_as_enrollable_guest', 'mod_contentmarketplace')
        : this.$str('viewing_as_guest', 'mod_contentmarketplace');
    },

    displayEnrolButton() {
      const { canEnrol, isSiteGuest } = this.interactor;
      if (!this.selfEnrolEnabled || isSiteGuest) {
        return false;
      }

      return canEnrol;
    },
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
          // Default to completion not started
          status: this.$str(
            'activity_status_not_started',
            'mod_contentmarketplace'
          ),
          completionEnabled: module.completion_enabled,
          timeToComplete: learning_object.time_to_complete,
          updated: learning_object.last_updated_at,
          selfCompletion: module.self_completion,
        };

        this.webLaunchUrl = learning_object.web_launch_url;
        this.ssoLaunchUrl = learning_object.sso_launch_url;

        // When the field completion_status is null, meaning that user is not yet started,
        // hence setCompletion should be False.
        this.setCompletion = module.completion_status || false;
        this.openContents = getAllNodeKeys(contentsTree);

        const { interactor } = module;
        this.interactor.canEnrol = interactor.can_enrol;
        this.interactor.isAdmin = interactor.is_admin;
        this.interactor.isSiteGuest = interactor.is_site_guest;
        this.interactor.canLaunch = interactor.can_launch;
        this.interactor.isEnrolled = interactor.is_enrolled;

        this.selfEnrolEnabled = module.self_enrol_enabled;
        this.guestEnrolEnabled = module.guest_enrol_enabled;
        this.selfEnrolEnabledWithRequiredKey.redirectUrl =
          module.self_enrol_enabled_with_required_key.redirect_url || '';
        this.selfEnrolEnabledWithRequiredKey.enabled =
          module.self_enrol_enabled_with_required_key.enabled;

        if (module.completion_status !== null) {
          // The completion of this activity had been started and it is either completed or in progress
          // base upon the value of this field "completion_status".
          if (module.completion_status) {
            learningObject.status = this.$str(
              'activity_status_completed',
              'mod_contentmarketplace'
            );
          } else {
            learningObject.status = this.$str(
              'activity_status_in_progress',
              'mod_contentmarketplace'
            );
          }
        }

        return learningObject;
      },
    },
  },

  methods: {
    launchNewWindow() {
      // This window name is used for behat.
      const windowName = 'linkedIn_course_window';
      if (this.ssoLaunchUrl) {
        window.open(this.ssoLaunchUrl, windowName);
        return;
      }

      // If ssoLaunchUrl is null, it will fallback to webLaunchUrl.
      window.open(this.webLaunchUrl, windowName);
    },

    async setCompletionHandler() {
      try {
        let {
          data: { result },
        } = await this.$apollo.mutate({
          mutation: setSelfCompletionMutation,
          refetchAll: false,
          variables: {
            cm_id: this.cmId,
            status: this.setCompletion,
          },
          update: (store, { data: { result: completionResult } }) => {
            // Update the completion result to apollo cache.
            let { instance } = store.readQuery({
              query: LinkedinActivityQuery,
              variables: {
                cm_id: this.cmId,
              },
            });

            instance = Object.assign({}, instance);
            instance.module = Object.assign({}, instance.module, {
              completion_status: completionResult,
            });

            store.writeQuery({
              query: LinkedinActivityQuery,
              variables: {
                cm_id: this.cmId,
              },
              data: { instance },
            });
          },
        });

        this.setCompletion = result;
      } catch (e) {
        await notify({
          message: this.$str(
            this.setCompletion ? 'toggle_on_error' : 'toggle_off_error',
            'mod_contentmarketplace'
          ),
          type: 'error',
        });
      }
    },

    async requestSelfEnrol() {
      if (this.selfEnrolEnabledWithRequiredKey.enabled) {
        window.location.href = this.selfEnrolEnabledWithRequiredKey.redirectUrl;
        return;
      }

      try {
        const variables = { cm_id: this.cmId };

        let {
          data: { result },
        } = await this.$apollo.mutate({
          mutation: requestSelfEnrol,
          variables,
          refetchAll: true,
        });

        if (result) {
          await notify({
            message: this.$str(
              'enrol_success_message',
              'mod_contentmarketplace'
            ),
            type: 'success',
          });
        }
      } catch (e) {
        await notify({
          message: this.$str('internal_error', 'mod_contentmarketplace'),
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
      "enrol_to_course",
      "enrol_success_message",
      "internal_error",
      "launch_in_new_window",
      "toggle_off_error",
      "toggle_on_error",
      "updated_at",
      "viewing_as_enrollable_admin",
      "viewing_as_enrollable_admin_self_enrol_disabled",
      "viewing_as_enrollable_guest",
      "viewing_as_guest"
    ],
    "core_enrol": [
      "enrol"
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

    &-desc {
      img {
        max-width: 100%;
      }
    }
  }
}
</style>
