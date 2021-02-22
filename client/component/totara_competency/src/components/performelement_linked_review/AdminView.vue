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
  @module totara_competency
-->

<template>
  <div class="tui-linkedReviewAdminViewCompetency">
    <h4 class="tui-linkedReviewAdminViewCompetency__title">
      Competency Title
    </h4>
    <div class="tui-linkedReviewAdminViewCompetency__description">
      Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean vitae
      accumsan sem. Nam vulputate lectus at ante maximus laoreet. Pellentesque
      habitant morbi tristique senectus et netus et malesuada fames ac turpis
      egestas. Pellentesque habitant morbi tristique senectus et netus et
      malesuada fames ac turpis egestas. Aenean dignissim, odio nec pulvinar
      pellentesque, metus mauris viverra ante, vitae mattis enim odio ac tellus.
      Aenean finibus diam eu quam consequat fringilla. Vivamus tristique
      scelerisque nisl dignissim cursus. Cras iaculis egestas ipsum, at
      consectetur libero tempus sit amet. Integer suscipit tellus non augue
      porttitor, quis porttitor urna laoreet. Nam ut lectus gravida, volutpat
      tortor et, varius nunc. Donec ac nisi turpis.
    </div>

    <div class="tui-linkedReviewAdminViewCompetency__bar">
      <div class="tui-linkedReviewAdminViewCompetency__bar-status">
        <ProgressTrackerCircle state="pending" :target="true" />
        <span class="tui-linkedReviewAdminViewCompetency__bar-statusText">
          {{ $str('not_proficient', 'totara_competency') }}
        </span>
      </div>
    </div>

    <div class="tui-linkedReviewAdminViewCompetency__ratings">
      <div class="tui-linkedReviewAdminViewCompetency__rating">
        <h5 class="tui-linkedReviewAdminViewCompetency__rating-heading">
          Self-assessment
        </h5>
        <Table
          :data="[
            {
              assessor: 'Leo learner',
              rating: 'Rating A',
              ratingDate: '7 January 2021',
            },
          ]"
        >
          <!-- Rows -->
          <template v-slot:row="{ row }">
            <Cell size="2" column-header="Assessor" valign="center">
              {{ row.assessor }}
            </Cell>
            <Cell size="2" column-header="Rating" valign="center">
              {{ row.rating }}
            </Cell>
            <Cell size="8" column-header="Rating date" valign="center">
              {{ row.ratingDate }}
            </Cell>
          </template>
        </Table>
      </div>

      <div class="tui-linkedReviewAdminViewCompetency__rating">
        <h5 class="tui-linkedReviewAdminViewCompetency__rating-heading">
          Rating from an assessor
        </h5>
        <Table
          :data="[
            {
              assessor: 'Assessor A',
              rating: 'No rating given',
            },
            {
              assessor: 'Assessor B',
              rating: 'No rating given',
            },
          ]"
        >
          <!-- Rows -->
          <template v-slot:row="{ row }">
            <Cell size="2" column-header="Assessor" valign="center">
              {{ row.assessor }}
            </Cell>
            <Cell size="2" column-header="Rating" valign="center">
              {{ row.rating }}
            </Cell>
            <Cell size="8" column-header="Rating date" valign="center">
              {{ row.ratingDate }}
            </Cell>
          </template>
        </Table>
      </div>
    </div>
  </div>
</template>

<script>
import Cell from 'tui/components/datatable/Cell';
import ProgressTrackerCircle from 'tui/components/progresstracker/ProgressTrackerCircle';
import Table from 'tui/components/datatable/Table';

export default {
  components: {
    Cell,
    ProgressTrackerCircle,
    Table,
  },
};
</script>

<lang-strings>
  {
    "totara_competency": [
      "not_proficient"
    ]
  }
</lang-strings>

<style lang="scss">
.tui-linkedReviewAdminViewCompetency {
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
