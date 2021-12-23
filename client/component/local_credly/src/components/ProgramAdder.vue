<!--
  - Copyright City & Guilds Kineo 2021
  - Author: Michael Geering <michael.geering@kineo.com>
  -->

<template>
  <Adder
    class="programAdder"
    :open="open && !$apollo.loading"
    :title="title"
    :existing-items="existingItems"
    :show-load-more="false"
    :show-loading-btn="false"
    @cancel="$emit('cancel')"
  >
    <template v-slot:browse-list="{ disabledItems, selectedItems, update }">
      <AdderFilter
        @submit-search="updateFilter"
        @filter="updateFilter"
        @clear="updateFilter"
      />
      <div class="programAdder__count">
        {{ itemCount() }}
      </div>
      <Table :data="filteredPrograms" :no-items-text="''">
        <template v-slot:header-row>
          <HeaderCell size="11" valign="center">{{ tableHeaderName }}</HeaderCell>
        </template>

        <template v-slot:row="{ row }">
          <Cell
            size="11"
            class="program-cell"
            :column-header="tableHeaderName"
            valign="center"
            @click.native="closeWithData([row.id, row])"
          >
            {{ row.name }}
            <small>( {{ row.category }} )</small>
          </Cell>
        </template>
      </Table>
    </template>

  </Adder>
</template>
<script>
import Adder from "tui/components/adder/Adder";
import Table from 'tui/components/datatable/Table';
import HeaderCell from "tui/components/datatable/HeaderCell";
import Cell from "tui/components/datatable/Cell";
import AdderFilter from "../components/AdderFilter";

import CredlyProgramsQuery from 'local_credly/graphql/programs';

export default {
  components: {Cell, HeaderCell, Table, Adder, AdderFilter},

  props: {
    existingItems: {
      type: Array,
      default: () => [],
    },
    open: Boolean,
    title: {
      type: String,
      required: true,
    },
    tableHeaderName: {
      type: String,
      required: true,
    },
  },

  data() {
    return {
      filteredPrograms: [],
      programs: [],
    }
  },

  watch: {
    open() {
      if (this.open) {
        this.$apollo.queries.programs.refetch().then(() => {
          this.filteredPrograms = this.programs
        });

      }
    },
  },

  apollo: {
    programs: {
      query: CredlyProgramsQuery,
      update: (data) => {
        return data.programs.items;
      },
    }
  },

  methods: {
    /**
     * Close the adder, returning the selected items data
     *
     * @param {Array} selection
     */
    async closeWithData(selection) {
      this.$emit('add-button-clicked');
      this.$emit('added', {ids: [selection[0]], data: [selection]});
    },
    updateFilter({ searchTerm }) {
      this.filteredPrograms = this.programs.filter(item => item.name.toLowerCase().includes(searchTerm.toLowerCase()));

    },
    itemCount() {
      return this.$str(
        "program_adder:showing_x_of_y_programs",
        "local_credly",
        {
          count: this.filteredPrograms.length,
          total: this.programs.length
        }
      );
    }
  }
}
</script>

<lang-strings>
{
  "local_credly": [
    "program_adder:showing_x_of_y_programs"
  ]
}
</lang-strings>

<style scoped lang="scss">
.program-cell {
  cursor: pointer;
  font-size: var(--base-font-size);
  small {
    font-size: 12px;
    display: inline-block;
    width: 100%;
  }
}
.programAdder {
  .tui-tabs__tabs,
  .tui-adder__summary,
  button[disabled] {
    display: none;
  }
  &__count {
    margin-top: var(--gap-5);
    font-weight: bold;
  }
}
</style>