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
  @author Mark Metcalfe <mark.metcalfe@totaralearning.com>
  @module totara_competency
-->

<template>
  <div class="tui-linkedReviewViewCompetency">
    <h4 class="tui-linkedReviewViewCompetency__title">
      {{ content.progress.competency.display_name }}
    </h4>
    <div
      class="tui-linkedReviewViewCompetency__description"
      v-html="content.progress.competency.description"
    />

    <div class="tui-linkedReviewViewCompetency__bar">
      <div class="tui-linkedReviewViewCompetency__bar-status">
        <ProgressTrackerCircle state="pending" :target="true" />
        <span class="tui-linkedReviewViewCompetency__bar-statusText">
          {{
            content.achievement.proficient
              ? $str('proficient', 'totara_competency')
              : $str('not_proficient', 'totara_competency')
          }}
        </span>
      </div>
    </div>
  </div>
</template>

<script>
import ProgressTrackerCircle from 'tui/components/progresstracker/ProgressTrackerCircle';

// The GraphQL query to use for getting the selected competencies.
import linkedCompetenciesQuery from 'totara_competency/graphql/perform_linked_competencies';
import linkedCompetenciesQueryNoSession from 'totara_competency/graphql/perform_linked_competencies_nosession';
export {
  linkedCompetenciesQuery as query,
  linkedCompetenciesQueryNoSession as query_external,
};

export default {
  components: {
    ProgressTrackerCircle,
  },

  props: {
    content: {
      type: Object,
      required: true,
    },
    settings: Object,
  },
};
</script>

<lang-strings>
{
  "totara_competency": [
    "not_proficient",
    "proficient"
  ]
}
</lang-strings>

<style lang="scss">
.tui-linkedReviewViewCompetency {
  & > * + * {
    margin-top: var(--gap-4);
  }

  &__bar {
    display: flex;
    padding: var(--gap-2);
    background: var(--color-neutral-3);

    &-status {
      display: flex;
      margin-left: auto;

      & > * + * {
        margin-left: var(--gap-2);
      }
    }

    &-status {
      display: flex;
      align-items: center;
      justify-content: flex-end;
    }

    &-statusText {
      margin-left: var(--gap-2);
      @include tui-font-heading-small();

      .dir-rtl & {
        margin: 0 var(--gap-2) 0 0;
      }

      &-complete {
        margin-left: var(--gap-1);

        .dir-rtl & {
          margin: 0 var(--gap-1) 0 0;
        }
      }
    }
  }

  &__ratings {
    & > * + * {
      margin-top: var(--gap-6);
    }
  }
}
</style>
