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

  @author Murali Nair <murali.nair@totaralearning.com>
  @package totara_hierarchy
-->

<template>
  <Adder
    :open="open"
    :title="$str('personal_goal_adder_title', 'totara_hierarchy')"
    :existing-items="existingItems"
    :loading="$apollo.loading"
    :show-load-more="nextPage"
    :show-loading-btn="showLoadingBtn"
    @added="closeWithData($event)"
    @cancel="cancelAdder()"
    @load-more="loadMoreItems()"
    @selected-tab-active="updateSelectedItems($event)"
  >
    <template v-slot:browse-filters>
      <FilterBar
        :has-top-bar="false"
        :title="$str('personal_goal_adder_filter_title', 'totara_hierarchy')"
      >
        <template v-slot:filters-left="{ stacked }">
          <SelectFilter
            v-model="selectedAssignmentType"
            :label="$str('personal_goal_adder_filter_type', 'totara_hierarchy')"
            :show-label="true"
            :options="assignmentTypeFilterOptions"
            :stacked="stacked"
          />
        </template>
        <template v-slot:filters-right="{ stacked }">
          <SearchFilter
            v-model="searchDebounce"
            :label="$str('personal_goal_adder_filter_name', 'totara_hierarchy')"
            :show-label="false"
            :placeholder="
              $str('personal_goal_adder_filter_name', 'totara_hierarchy')
            "
            :stacked="stacked"
          />
        </template>
      </FilterBar>
    </template>

    <template v-slot:browse-list="{ disabledItems, selectedItems, update }">
      <SelectTable
        :large-check-box="true"
        :no-label-offset="true"
        :value="selectedItems"
        :data="goals && goals.items ? goals.items : []"
        :disabled-ids="disabledItems"
        checkbox-v-align="center"
        :select-all-enabled="true"
        :border-bottom-hidden="true"
        @input="update($event)"
      >
        <template v-slot:header-row>
          <HeaderCell size="8" valign="center">
            {{ $str('personal_goal_adder_label_name', 'totara_hierarchy') }}
          </HeaderCell>
          <HeaderCell size="8" valign="center">
            {{
              $str(
                'personal_goal_adder_label_assignment_type',
                'totara_hierarchy'
              )
            }}
          </HeaderCell>
        </template>

        <template v-slot:row="{ row }">
          <Cell
            size="8"
            :column-header="
              $str('personal_goal_adder_label_name', 'totara_hierarchy')
            "
            valign="center"
          >
            {{ row.name }}
          </Cell>

          <Cell
            size="8"
            :column-header="
              $str(
                'personal_goal_adder_label_assignment_type',
                'totara_hierarchy'
              )
            "
            valign="center"
          >
            {{ row.assignment_type.description }}
          </Cell>
        </template>
      </SelectTable>
    </template>

    <template v-slot:basket-list="{ disabledItems, selectedItems, update }">
      <SelectTable
        :large-check-box="true"
        :no-label-offset="true"
        :value="selectedItems"
        :data="goalSelectedItems"
        :disabled-ids="disabledItems"
        checkbox-v-align="center"
        :border-bottom-hidden="true"
        :select-all-enabled="true"
        @input="update($event)"
      >
        <template v-slot:header-row>
          <HeaderCell size="8" valign="center">
            {{ $str('personal_goal_adder_label_name', 'totara_hierarchy') }}
          </HeaderCell>
          <HeaderCell size="8" valign="center">
            {{
              $str(
                'personal_goal_adder_label_assignment_type',
                'totara_hierarchy'
              )
            }}
          </HeaderCell>
        </template>

        <template v-slot:row="{ row }">
          <Cell
            size="8"
            :column-header="
              $str('personal_goal_adder_label_name', 'totara_hierarchy')
            "
            valign="center"
          >
            {{ row.name }}
          </Cell>

          <Cell
            size="8"
            :column-header="
              $str(
                'personal_goal_adder_label_assignment_type',
                'totara_hierarchy'
              )
            "
            valign="center"
          >
            {{ row.assignment_type.description }}
          </Cell>
        </template>
      </SelectTable>
    </template>
  </Adder>
</template>

<script>
// Components
import Adder from 'tui/components/adder/Adder';
import Cell from 'tui/components/datatable/Cell';
import FilterBar from 'tui/components/filters/FilterBar';
import HeaderCell from 'tui/components/datatable/HeaderCell';
import SearchFilter from 'tui/components/filters/SearchFilter';
import SelectFilter from 'tui/components/filters/SelectFilter';
import SelectTable from 'tui/components/datatable/SelectTable';
import { debounce } from 'tui/util';

//Queries
import personal_goals from 'totara_hierarchy/graphql/personal_goals';
import assignment_types from 'totara_hierarchy/graphql/goal_assignment_types';

export default {
  components: {
    Adder,
    Cell,
    FilterBar,
    HeaderCell,
    SearchFilter,
    SelectFilter,
    SelectTable,
  },

  props: {
    existingItems: {
      type: Array,
      default: () => [],
    },
    open: Boolean,
    customQuery: Object,
    customQueryKey: String,
    showLoadingBtn: Boolean,
    targetUserId: {
      required: false,
      type: String,
      default: null,
    },
  },

  data() {
    return {
      goals: null,
      goalSelectedItems: [],
      assignmentTypes: [],
      selectedAssignmentType: '',
      filters: {
        search: '',
        assignmentType: '',
      },
      nextPage: false,
      skipQueries: true,
      stacked: false,
      searchDebounce: '',
    };
  },

  computed: {
    assignmentTypeFilterOptions() {
      var options = this.assignmentTypes.map(type => {
        return {
          id: type.name,
          label: type.label,
        };
      });

      options.unshift({
        id: '',
        label: this.$str('all', 'core'),
      });

      return options;
    },
  },

  watch: {
    open() {
      if (this.open) {
        this.searchDebounce = '';
        this.skipQueries = false;
      } else {
        this.skipQueries = true;
      }
    },

    searchDebounce(newValue) {
      this.updateFilterDebounced(newValue);
    },

    selectedAssignmentType(newValue) {
      this.filters.assignmentType = newValue;
    },
  },

  created() {
    this.$apollo.addSmartQuery('goals', {
      query: this.customQuery ? this.customQuery : personal_goals,
      skip() {
        return this.skipQueries;
      },
      variables() {
        return {
          input: {
            filters: {
              name: this.filters.search,
              assignment_type: this.filters.assignmentType,
              user_id: this.targetUserId,
            },
          },
        };
      },
      update({
        [this.customQueryKey
          ? this.customQueryKey
          : 'totara_hierarchy_personal_goals']: goals,
      }) {
        this.nextPage = goals.next_cursor ? goals.next_cursor : false;
        return goals;
      },
    });

    this.$apollo.addSmartQuery('selectedGoals', {
      query: this.customQuery ? this.customQuery : personal_goals,
      skip() {
        return this.skipQueries;
      },
      variables() {
        return {
          input: {
            filters: {
              ids: [],
            },
          },
        };
      },
      update({
        [this.customQueryKey
          ? this.customQueryKey
          : 'totara_hierarchy_personal_goals']: selectedGoals,
      }) {
        this.goalSelectedItems = selectedGoals.items;
        return selectedGoals;
      },
    });

    this.$apollo.addSmartQuery('assignment_types', {
      query: assignment_types,
      skip() {
        return this.skipQueries;
      },
      variables() {
        return {
          input: {
            scope: 'PERSONAL',
          },
        };
      },
      update({ ['totara_hierarchy_goal_assignment_types']: types }) {
        this.assignmentTypes = types;
        return types;
      },
    });
  },

  methods: {
    async loadMoreItems() {
      if (!this.nextPage) {
        return;
      }
      try {
        this.$apollo.queries.goals.fetchMore({
          variables: {
            input: {
              cursor: this.nextPage,
              filters: {
                name: this.filters.search,
                assignment_type: this.filters.assignmentType,
                user_id: this.targetUserId,
              },
            },
          },

          updateQuery: (previousResult, { fetchMoreResult }) => {
            const oldData = previousResult.totara_hierarchy_personal_goals;
            const newData = fetchMoreResult.totara_hierarchy_personal_goals;
            const newList = oldData.items.concat(newData.items);

            return {
              [this.customQueryKey
                ? this.customQueryKey
                : 'totara_hierarchy_personal_goals']: {
                items: newList,
                next_cursor: newData.next_cursor,
              },
            };
          },
        });
      } catch (error) {
        console.error(error);
      }
    },

    async updateSelectedItems(selection) {
      const numberOfItems = selection.length;

      try {
        await this.$apollo.queries.selectedGoals.refetch({
          input: {
            filters: {
              ids: selection,
            },
            result_size: numberOfItems,
          },
        });
      } catch (error) {
        console.error(error);
      }

      return this.goalSelectedItems;
    },

    async closeWithData(selection) {
      let data;

      this.$emit('add-button-clicked');

      try {
        data = await this.updateSelectedItems(selection);
        this.selectedAssignmentType = '';
      } catch (error) {
        console.error(error);
        return;
      }
      this.$emit('added', { ids: selection, data: data });
    },

    cancelAdder() {
      this.selectedAssignmentType = '';
      this.$emit('cancel');
    },

    updateFilterDebounced: debounce(function(input) {
      this.filters.search = input;
    }, 500),
  },
};
</script>

<lang-strings>
  {
    "core": [
      "all"
    ],
    "totara_hierarchy": [
      "personal_goal_adder_filter_title",
      "personal_goal_adder_filter_name",
      "personal_goal_adder_filter_type",
      "personal_goal_adder_label_assignment_type",
      "personal_goal_adder_label_name",
      "personal_goal_adder_title"
    ]
  }
</lang-strings>
