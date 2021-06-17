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
  <div v-if="goalContentExists" class="tui-linkedReviewViewGoal">
    <h4 class="tui-linkedReviewViewGoal__title">
      {{ content.goal.display_name }}
    </h4>

    <div
      class="tui-linkedReviewViewGoal__description"
      v-html="content.goal.description"
    />

    <div class="tui-linkedReviewViewGoal__overview">
      <div class="tui-linkedReviewViewGoal__bar">
        <Grid :stack-at="600">
          <GridItem :units="3">
            <span class="tui-linkedReviewViewGoal__bar-label">
              {{ $str('goal_status', 'hierarchy_goal') }}
            </span>
            <span class="tui-linkedReviewViewGoal__bar-value">
              {{ content.status }}
            </span>
          </GridItem>

          <GridItem :units="5">
            <div class="tui-linkedReviewViewGoal__bar-wrap">
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

  <div v-else class="tui-linkedReviewViewGoalMissing">
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
    preview: Boolean,
  },

  computed: {
    /**
     * Checks if the goal exists in the content property.
     *
     * @return {Boolean}
     */
    goalContentExists() {
      return this.content && this.content.goal && this.content.status;
    },
  },
};
</script>

<lang-strings>
  {
    "hierarchy_goal": [
      "goal_status",
      "target_date",
      "perform_review_goal_missing"
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

  &__bar {
    display: flex;
    padding: var(--gap-4);
    background: var(--color-neutral-1);
    border: var(--border-width-thin) solid var(--color-neutral-5);

    &-status {
      display: flex;
      align-items: center;
      justify-content: flex-end;

      & > * + * {
        margin-left: var(--gap-2);
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
