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

  @author Riana Rossouw <riana.rossouw@totaralearning.com>
  @module hierarchy_goal
-->

<template>
  <div
    v-if="goalContentExists"
    class="tui-linkedReviewViewGoal"
    :class="{ 'tui-linkedReviewViewGoal--print': fromPrint }"
  >
    <h4 class="tui-linkedReviewViewGoal__title">
      <a
        v-if="
          !fromPrint &&
            !preview &&
            !isExternalParticipant &&
            content.can_view_goal_details
        "
        :aria-label="
          $str('selected_goal', 'hierarchy_goal', content.goal.display_name)
        "
        :href="goalUrl"
      >
        {{ content.goal.display_name }}
      </a>
      <template v-else>
        {{ content.goal.display_name }}
      </template>
    </h4>

    <div
      class="tui-linkedReviewViewGoal__description"
      v-html="content.goal.description"
    />

    <div class="tui-linkedReviewViewGoal__overview">
      <div
        v-if="!preview && content.status && content.target_date"
        class="tui-linkedReviewViewGoal__timestamp"
      >
        {{ createdAt }}
      </div>
      <div v-if="goalBarVisible" class="tui-linkedReviewViewGoal__bar">
        <Grid :stack-at="600">
          <GridItem :units="3">
            <div
              v-if="content.status"
              class="tui-linkedReviewViewGoal__bar-status"
            >
              <span class="tui-linkedReviewViewGoal__bar-statusLabel">
                {{ $str('goal_status', 'hierarchy_goal') }}
              </span>
              <span class="tui-linkedReviewViewGoal__bar-statusValue">
                {{ content.status.name }}
              </span>
            </div>
          </GridItem>
          <GridItem :units="5">
            <div
              v-if="content.target_date"
              class="tui-linkedReviewViewGoal__bar-wrap"
            >
              <span class="tui-linkedReviewViewGoal__bar-label">
                {{ $str('target_date', 'hierarchy_goal') }}
              </span>
              <span class="tui-linkedReviewViewGoal__bar-value">
                {{ content.target_date }}
              </span>
            </div>
          </GridItem>
        </Grid>
      </div>
    </div>
  </div>

  <div v-else class="tui-linkedReviewViewGoal__missing">
    {{ $str('perform_review_goal_missing', 'hierarchy_goal') }}
  </div>
</template>

<script>
import Grid from 'tui/components/grid/Grid';
import GridItem from 'tui/components/grid/GridItem';

export default {
  components: {
    Grid,
    GridItem,
  },

  props: {
    content: {
      type: Object,
    },
    createdAt: String,
    fromPrint: Boolean,
    isExternalParticipant: Boolean,
    preview: Boolean,
  },

  computed: {
    /**
     * Provide url for goal
     * @return {String}
     */
    goalUrl() {
      if (!this.content) {
        return '';
      }

      if (this.content.goal.goal_scope === 'COMPANY') {
        return this.$url('/totara/hierarchy/item/view.php', {
          id: this.content.goal.id,
          prefix: 'goal',
        });
      } else {
        return this.$url('/totara/hierarchy/prefix/goal/item/view.php', {
          id: this.content.goal.id,
        });
      }
    },

    /**
     * Checks if the goal exists in the content property.
     *
     * @return {Boolean}
     */
    goalContentExists() {
      if (!this.content) {
        return false;
      }

      if (this.content.goal.goal_scope === 'COMPANY') {
        return this.content.goal && this.content.status;
      } else {
        return !!this.content.goal;
      }
    },

    /**
     * Check if status or target date exists
     *
     * @return {Boolean}
     */
    goalBarVisible() {
      return this.content.status || this.content.target_date;
    },
  },
};
</script>

<lang-strings>
  {
    "hierarchy_goal": [
      "goal_status",
      "target_date",
      "perform_review_goal_missing",
      "selected_goal"
    ]
  }
</lang-strings>

<style lang="scss">
.tui-linkedReviewViewGoal {
  & > * + * {
    margin-top: var(--gap-4);
  }

  &__title {
    @include tui-font-heading-x-small();
    margin: 0;
  }

  &__overview {
    & > * + * {
      margin-top: var(--gap-1);
    }
  }

  &__timestamp {
    @include tui-font-body-small();
  }

  &__bar {
    display: flex;
    padding: var(--gap-4);
    background: var(--color-neutral-1);
    border: var(--border-width-thin) solid var(--color-neutral-5);

    &-statusValue {
      padding-left: var(--gap-2);
      @include tui-font-heavy();

      .tui-linkedReviewViewGoal--print & {
        display: block;
        padding: 0;
      }
    }

    &-label {
      margin: 0;
      @include tui-font-body();
    }

    &-value {
      padding-left: var(--gap-2);
      @include tui-font-heavy();
    }

    &-wrap {
      padding-left: var(--gap-2);
      border-color: var(--color-neutral-6);
      border-style: solid;
      border-width: 0 0 0 var(--border-width-thick);

      .dir-rtl & {
        padding-right: var(--gap-2);
        border-width: 0 var(--border-width-thick) 0 0;
      }
    }
  }
}
</style>
