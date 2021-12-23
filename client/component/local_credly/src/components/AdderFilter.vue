<template>
  <div class="tui-workspaceFilter">
    <template v-if="!$apollo.loading">
      <FilterBar
        v-model="selection"
        :title="$str('adder:search', 'local_credly')"
        class="tui-workspaceFilter__filter"
      >
        <template v-slot:filters-left="{ stacked }">
          <SearchFilter
            v-model="selection.searchTerm"
            :label="$str('adder:search', 'local_credly')"
            drop-label
            :placeholder="$str('adder:search', 'local_credly')"
            :stacked="stacked"
          />
        </template>
      </FilterBar>
    </template>
  </div>
</template>

<script>
import FilterBar from 'tui/components/filters/FilterBar';
import SearchFilter from 'tui/components/filters/SearchFilter';
export default {
  components: {FilterBar, SearchFilter},
  props: {
    searchTerm: {
      type: String,
      default: '',
    },
  },
  data() {
    return {
      courses: [],
      selection: {
        searchTerm: this.searchTerm,
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
<lang-strings>
{
"local_credly": [
"adder:search"
]
}
</lang-strings>