<template>
  <FilterBar v-model="selection" :title="'Filter results'">

    <!-- Left aligned content -->
    <template v-slot:filters-left="{ stacked }">
      <SelectFilter
        v-model="selection.learningtype"
        label="Within category"
        :show-label="true"
        :options="learningtypes"
        :stacked="stacked"
      />
    </template>

    <!-- Right aligned content -->
    <template v-slot:filters-right="{ stacked }">
      <SearchFilter
        v-model="selection.search"
        label="Filter items by search"
        :show-label="false"
        :placeholder="'Search'"
        :stacked="stacked"
      />
    </template>
  </FilterBar>
</template>


<script>
import FilterBar from 'tui/components/filters/FilterBar';
import SearchFilter from 'tui/components/filters/SearchFilter';
import SelectFilter from 'tui/components/filters/SelectFilter';

export default {
  components: {
    FilterBar,
    SearchFilter,
    SelectFilter
  },

  data() {
    return {
      learningtypes: [
        {
          id: '',
          label: 'No filter',
        },
        {
          id: 'unlinked',
          label: 'Not set',
        },
        {
          id: 'course',
          label: 'Courses',
        },
        {
          id: 'program',
          label: 'Programs',
        },
        {
          id: 'certification',
          label: 'Certifications',
        },
      ],
      selection: {
        learningtype: '',
        search: '',
      },
    }
  },
  watch: {
    selection: {
      deep: true,
      handler() {
        this.$emit('filter', this.selection);
      },
    },
  },

  methods: {
    submitSearch() {
      this.$emit('submit-search', this.selection);
    },
  }
}
</script>
