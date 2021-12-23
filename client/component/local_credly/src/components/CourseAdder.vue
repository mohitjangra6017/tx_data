<!--
  - Copyright City & Guilds Kineo 2021
  - Author: Matthew Haynes <matthew.haynes@kineo.com>
  -->

<template>
  <Adder
    class="courseAdder"
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
      <div class="courseAdder__count">
        {{ itemCount() }}
      </div>
      <Table :data="filteredCourses" :no-items-text="''">
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

import CredlyCourseQuery from 'local_credly/graphql/courses';

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
      filteredCourses: [],
      courses: [],
    }
  },
  watch: {
    open() {
      if (this.open) {
        this.$apollo.queries.courses.refetch().then(() => {
          this.filteredCourses = this.courses
        });

      }
    },
  },

  apollo: {
    courses: {
      query: CredlyCourseQuery,
      update: (data) => {
        return data.courses.items;
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
      this.filteredCourses = this.courses.filter(item => item.name.toLowerCase().includes(searchTerm.toLowerCase()));

    },
    itemCount() {
      return this.$str(
        "course_adder:showing_x_of_y_courses",
        "local_credly",
        {
          count: this.filteredCourses.length,
          total: this.courses.length
        }
      );
    }
  }
}
</script>

<lang-strings>
{
  "local_credly": [
    "course_adder:showing_x_of_y_courses"
  ]
}
</lang-strings>

<style scoped lang="scss">
.course-cell {
  cursor: pointer;
  font-size: var(--base-font-size);
  small {
    font-size: 12px;
    display: inline-block;
    width: 100%;
  }
}
.courseAdder {
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
