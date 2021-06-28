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
      <a
        v-if="!preview"
        :href="goalUrl"
        :aria-label="
          $str('selected_goal', 'hierarchy_goal', content.goal.display_name)
        "
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
      <div v-if="!preview && content.status && content.target_date">
        {{ createdAt }}
      </div>
      <div class="tui-linkedReviewViewGoal__bar">
        <Grid :stack-at="600">
          <GridItem :units="3">
            <template v-if="content.status">
              <span class="tui-linkedReviewViewGoal__bar-label">
                {{ $str('goal_status', 'hierarchy_goal') }}
              </span>
              <span class="tui-linkedReviewViewGoal__bar-value">
                {{ content.status.name }}
              </span>
            </template>
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

  <div v-else class="tui-linkedReviewViewGoalMissing">
    {{ $str('perform_review_goal_missing', 'hierarchy_goal') }}
  </div>
</template>

<script>
import Grid from 'tui/components/grid/Grid';
import GridItem from 'tui/components/grid/GridItem';
import { COMPANY_GOAL } from '../../js/constants';

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
    createdAt: String,
  },

  computed: {
    /**
     * Provide url for goal
     * @return {String}
     */
    goalUrl() {
      if (this.content.content_type === COMPANY_GOAL) {
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

      if (this.content.content_type === COMPANY_GOAL) {
        return this.content.goal && this.content.status;
      } else {
        return this.content.goal ? true : false;
      }
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
      padding-left: var(--gap-4);
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
