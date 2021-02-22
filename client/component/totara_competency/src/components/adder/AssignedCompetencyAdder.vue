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
  <Adder
    :open="open"
    :title="$str('select_competencies', 'totara_competency')"
    :existing-items="existingItems"
    :loading="$apollo.loading"
    :show-load-more="nextPage"
    :show-loading-btn="showLoadingBtn"
    @added="closeWithData($event)"
    @cancel="$emit('cancel')"
    @load-more="loadMoreItems()"
    @selected-tab-active="updateSelectedItems($event)"
  >
    <template v-slot:browse-filters>
      <FilterBar
        :has-top-bar="false"
        :title="$str('filter_competencies', 'totara_competency')"
      >
        <template v-slot:filters-left="{ stacked }">
          <SearchFilter
            v-model="searchDebounce"
            :label="
              $str('filter_competencies_search_label', 'totara_competency')
            "
            :show-label="false"
            :placeholder="$str('search', 'totara_core')"
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
        :data="competencies && competencies.items ? competencies.items : []"
        :disabled-ids="disabledItems"
        checkbox-v-align="center"
        :select-all-enabled="true"
        :border-bottom-hidden="true"
        @input="update($event)"
      >
        <template v-slot:header-row>
          <HeaderCell size="9" valign="center">
            {{ $str('header_competency', 'totara_competency') }}
          </HeaderCell>
          <HeaderCell size="3" align="center" valign="center">
            {{ $str('proficient', 'totara_competency') }}
          </HeaderCell>
          <HeaderCell size="4" valign="center">
            {{ $str('achievement_level', 'totara_competency') }}
          </HeaderCell>
        </template>

        <template v-slot:row="{ row }">
          <Cell
            size="9"
            :column-header="$str('header_competency', 'totara_competency')"
            valign="center"
          >
            {{ row.competency.display_name }}
          </Cell>

          <Cell
            size="3"
            :column-header="$str('proficient', 'totara_competency')"
            align="center"
            valign="center"
          >
            <CheckIcon
              v-if="row.my_value.proficient"
              :alt="$str('yes', 'core')"
            />
            <span v-else>
              <span :aria-hidden="true">-</span>
              <span class="sr-only">{{ $str('no', 'core') }}</span>
            </span>
          </Cell>

          <Cell
            size="4"
            :column-header="$str('achievement_level', 'totara_competency')"
            valign="center"
          >
            {{ row.my_value.name }}
          </Cell>
        </template>
      </SelectTable>
    </template>

    <template v-slot:basket-list="{ disabledItems, selectedItems, update }">
      <SelectTable
        :large-check-box="true"
        :no-label-offset="true"
        :value="selectedItems"
        :data="competencySelectedItems"
        :disabled-ids="disabledItems"
        checkbox-v-align="center"
        :border-bottom-hidden="true"
        :select-all-enabled="true"
        @input="update($event)"
      >
        <template v-slot:header-row>
          <HeaderCell size="9" valign="center">
            {{ $str('header_competency', 'totara_competency') }}
          </HeaderCell>
          <HeaderCell size="3" align="center" valign="center">
            {{ $str('proficient', 'totara_competency') }}
          </HeaderCell>
          <HeaderCell size="4" valign="center">
            {{ $str('achievement_level', 'totara_competency') }}
          </HeaderCell>
        </template>

        <template v-slot:row="{ row }">
          <Cell
            size="9"
            :column-header="$str('header_competency', 'totara_competency')"
            valign="center"
          >
            {{ row.competency.display_name }}
          </Cell>

          <Cell
            size="3"
            :column-header="$str('proficient', 'totara_competency')"
            align="center"
            valign="center"
          >
            <CheckIcon
              v-if="row.my_value.proficient"
              :alt="$str('yes', 'core')"
            />
            <span v-else>
              <span :aria-hidden="true">-</span>
              <span class="sr-only">{{ $str('no', 'core') }}</span>
            </span>
          </Cell>

          <Cell
            size="4"
            :column-header="$str('achievement_level', 'totara_competency')"
            valign="center"
          >
            {{ row.my_value.name }}
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
import CheckIcon from 'tui/components/icons/CheckSuccess';
import FilterBar from 'tui/components/filters/FilterBar';
import HeaderCell from 'tui/components/datatable/HeaderCell';
import SearchFilter from 'tui/components/filters/SearchFilter';
import SelectTable from 'tui/components/datatable/SelectTable';
// Queries
import competenciesQuery from 'totara_competency/graphql/user_assignments';
import { debounce } from 'tui/util';

export default {
  components: {
    Adder,
    Cell,
    CheckIcon,
    FilterBar,
    HeaderCell,
    SearchFilter,
    SelectTable,
  },

  props: {
    existingItems: {
      type: Array,
      default: () => [],
    },
    open: Boolean,
    customQuery: Object,
    /**
     * custom query key needs to be passed
     * if customQuery is passed
     */
    customQueryKey: String,
    /**
     * Pass the context id along if you want to show competencies
     * from a specific context or higher. If omitted it will
     * load all system competencies (if the current user has the capability
     * to see them)
     */
    contextId: {
      type: Number,
    },
    // Display loading spinner on Add button
    showLoadingBtn: Boolean,
  },

  data() {
    return {
      competencies: null,
      competencySelectedItems: [],
      filters: {
        search: '',
      },
      nextPage: false,
      skipQueries: true,
      searchDebounce: '',
    };
  },

  watch: {
    /**
     * On opening of adder, unblock query
     *
     */
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
  },

  /**
   * Apollo queries have been registered here to provide support for custom queries
   */
  created() {
    /**
     * All assigned competencies query
     *
     */
    this.$apollo.addSmartQuery('competencies', {
      query: this.customQuery ? this.customQuery : competenciesQuery,
      skip() {
        return this.skipQueries;
      },
      variables() {
        return {
          query: {
            filters: {
              search: this.filters.search,
            },
          },
        };
      },
      update({
        [this.customQueryKey
          ? this.customQueryKey
          : 'totara_competency_user_assignments']: competencies,
      }) {
        this.nextPage = competencies.next_cursor
          ? competencies.next_cursor
          : false;
        return competencies;
      },
    });

    /**
     * Selected competencies query
     *
     */
    this.$apollo.addSmartQuery('selectedCompetencies', {
      query: this.customQuery ? this.customQuery : competenciesQuery,
      skip() {
        return this.skipQueries;
      },
      variables() {
        return {
          query: {
            filters: {
              ids: [],
            },
          },
        };
      },
      update({
        [this.customQueryKey
          ? this.customQueryKey
          : 'totara_competency_user_assignments']: selectedCompetencies,
      }) {
        this.competencySelectedItems = selectedCompetencies.items;
        return selectedCompetencies;
      },
    });
  },

  methods: {
    /**
     * Load addition items and append to list
     *
     */
    async loadMoreItems() {
      if (!this.nextPage) {
        return;
      }

      this.$apollo.queries.competencies.fetchMore({
        variables: {
          query: {
            cursor: this.nextPage,
            filters: {
              search: this.filters.search,
            },
          },
        },

        updateQuery: (previousResult, { fetchMoreResult }) => {
          const oldData = previousResult.totara_competency_user_assignments;
          const newData = fetchMoreResult.totara_competency_user_assignments;
          const newList = oldData.items.concat(newData.items);

          return {
            [this.customQueryKey
              ? this.customQueryKey
              : 'totara_competency_user_assignments']: {
              items: newList,
              next_cursor: newData.next_cursor,
            },
          };
        },
      });
    },

    /**
     * Close the adder, returning the selected items data
     *
     * @param {Array} selection
     */
    async closeWithData(selection) {
      let data;

      this.$emit('add-button-clicked');

      try {
        data = await this.updateSelectedItems(selection);
      } catch (error) {
        console.error(error);
        return;
      }
      this.$emit('added', { ids: selection, data: data });
    },

    /**
     * Update the selected items data
     *
     * @param {Array} selection
     */
    async updateSelectedItems(selection) {
      const numberOfItems = selection.length;

      try {
        await this.$apollo.queries.selectedCompetencies.refetch({
          query: {
            filters: {
              ids: selection,
            },
            result_size: numberOfItems,
          },
        });
      } catch (error) {
        console.error(error);
      }
      return this.competencySelectedItems;
    },

    /**
     * Update the search filter (which re-triggers the query) if the user stopped typing >500 milliseconds ago.
     *
     * @param {String} input Value from search filter input
     */
    updateFilterDebounced: debounce(function(input) {
      this.filters.search = input;
    }, 500),
  },
};
</script>

<lang-strings>
{
  "core": [
    "no",
    "yes"
  ],
  "totara_core": [
    "search"
  ],
  "totara_competency": [
    "achievement_level",
    "filter_competencies",
    "filter_competencies_search_label",
    "header_competency",
    "proficient",
    "select_competencies"
  ]
}
</lang-strings>
