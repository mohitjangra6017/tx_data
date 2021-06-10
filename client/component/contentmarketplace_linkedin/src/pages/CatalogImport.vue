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
  @package contentmarketplace_linkedin
-->

<template>
  <Layout
    :loading="isLoading"
    :reviewing-selection="reviewingSelectedItems"
    :review-title="$str('catalog_review_title', 'contentmarketplace_linkedin')"
    :selection-title="$str('catalog_title', 'contentmarketplace_linkedin')"
  >
    <template v-if="canManageContent" v-slot:content-nav>
      <PageBackLink
        :link="$url('/totara/contentmarketplace/marketplaces.php')"
        :text="
          $str(
            'back_to_manage_content_marketplaces',
            'totara_contentmarketplace'
          )
        "
      />
    </template>

    <!-- Primary filter for selecting language -->
    <template v-slot:primary-filter>
      <LinkedInPrimaryFilter
        v-if="languageFilterOptions.length > 1"
        :options="languageFilterOptions"
        :selected="selectedLanguage"
        @filter-change="setPrimaryFilter"
      />
    </template>

    <!-- Filter panel -->
    <template v-slot:filters="{ contentId }">
      <LinkedInFilters
        v-model="selectedFilters"
        :content-id="contentId"
        :filters="filters"
        :open-branches="openBranches"
      />
    </template>

    <!-- Basket for tracking selection, reviewing and creating courses -->
    <template v-slot:basket>
      <Basket
        :category-options="categoryOptions"
        :selected-category="selectedCategory.id"
        :selected-items="selectedItems"
        :viewing-selected="reviewingSelectedItems"
        @category-change="setDefaultSelectedCategory"
        @clear-selection="clearSelectedItems"
        @create-courses="createCourses"
        @reviewing-selection="switchContentView"
      />
    </template>

    <!-- Table count and active filters used -->
    <template v-slot:summary-count>
      <CountAndFilters
        :filters="activeFilterStrings"
        :count="learningObjects.total"
      />
    </template>

    <!-- Sort order filter for table -->
    <template v-slot:summary-sort>
      <SortFilter
        :options="sortFilterOptions"
        :sort-by="selectedSortOrderFilter"
        @filter-change="setSortOrderFilter"
      />
    </template>

    <!-- Table of available courses -->
    <template v-slot:select-table>
      <SelectionTable
        :items="learningObjects.items"
        :selected-items="selectedItems"
        @update="setSelectedItems"
      />

      <SelectionPaging
        :current-page="paginationPage"
        :items-per-page="paginationLimit"
        :total-items="learningObjects.total"
        @items-per-page-change="setItemsPerPage"
        @page-change="setPaginationPage"
      />
    </template>

    <!-- Table of selected courses -->
    <template v-if="reviewingLearningObjects" v-slot:review-table>
      <ReviewTable
        :category-options="categoryOptions"
        :items="reviewingLearningObjects.items"
        :selected-item-categories="reviewingItemCategories"
        :selected-items="selectedItems"
        @change-course-category="setSingleCourseCategory"
        @update="setSelectedItems"
      />

      <ReviewPaging
        :last-page="!reviewingLearningObjects.next_cursor.length"
        @next-page="updateReviewPage"
      />
    </template>
  </Layout>
</template>

<script>
import Basket from 'totara_contentmarketplace/components/basket/Basket';
import CountAndFilters from 'totara_contentmarketplace/components/CountAndFilters';
import Layout from 'totara_contentmarketplace/pages/CatalogImportLayout';
import LinkedInFilters from 'contentmarketplace_linkedin/components/filters/LinkedInSideFilters';
import LinkedInPrimaryFilter from 'contentmarketplace_linkedin/components/filters/LinkedInPrimaryFilter';
import PageBackLink from 'tui/components/layouts/PageBackLink';
import ReviewPaging from 'totara_contentmarketplace/components/paging/ReviewLoadMore';
import ReviewTable from 'contentmarketplace_linkedin/components/tables/LinkedInReviewTable';
import SelectionPaging from 'totara_contentmarketplace/components/paging/SelectionPaging';
import SelectionTable from 'contentmarketplace_linkedin/components/tables/LinkedInSelectionTable';
import SortFilter from 'totara_contentmarketplace/components/filters/SortFilter';
import { notify } from 'tui/notifications';

// Query
import courseCategoriesQuery from 'contentmarketplace_linkedin/graphql/catalog_import_course_categories';
import createCourseQuery from 'contentmarketplace_linkedin/graphql/catalog_import_create_course';
import filterOptionsQuery from 'contentmarketplace_linkedin/graphql/catalog_import_learning_objects_filter_options';
import learningObjectsQuery from 'contentmarketplace_linkedin/graphql/catalog_import_learning_objects';

export default {
  components: {
    Basket,
    CountAndFilters,
    Layout,
    LinkedInFilters,
    LinkedInPrimaryFilter,
    PageBackLink,
    ReviewPaging,
    ReviewTable,
    SelectionPaging,
    SelectionTable,
    SortFilter,
  },

  props: {
    canManageContent: {
      type: Boolean,
      required: true,
    },
  },

  data() {
    return {
      // Array of active filter strings
      activeFilterStrings: ['Data science', 'Videos'],
      // Available Sort filter options
      categoryOptions: [],
      filters: {
        assetType: [],
        subjects: [],
        timeToComplete: [],
      },
      // Available language options for primary filter
      languageFilterOptions: [
        {
          id: 'en',
          label: 'English',
        },
        {
          id: 'fr',
          label: 'French',
        },
      ],
      // Available learning content populated by learningObjectsQuery
      learningObjects: {
        items: [],
        total: 0,
      },
      // Open Filter tree branches
      openBranches: {
        assetType: [],
        subjects: ['subjects'],
        timeToComplete: [],
      },
      // items per page limit
      paginationLimit: 20,
      // Selection view pagination page
      paginationPage: 1,
      // Categories assigned to reviewing items
      reviewingItemCategories: {},
      // List of selected items provided to review step
      reviewingItemList: null,
      // Selected learning content populated by learningObjectsQuery
      reviewingLearningObjects: {
        items: [],
        next_cursor: '',
        total: 0,
      },
      // Current load more page on review display
      reviewingLoadMorePage: 1,
      // Showing display for reviewing selected items
      reviewingSelectedItems: false,
      // Selected category value
      selectedCategory: {
        id: null,
        label: null,
      },
      selectedFilters: {
        assetType: [],
        search: '',
        subjects: [],
        timeToComplete: [],
      },
      // Selected course ID's
      selectedItems: [],
      // Selected language value from primary filter
      selectedLanguage: 'en',
      // Selected sort filter value
      selectedSortOrderFilter: 'LATEST',
      // Available Sort filter options
      sortFilterOptions: [
        {
          label: this.$str('sort_filter_latest', 'contentmarketplace_linkedin'),
          id: 'LATEST',
        },
        {
          label: this.$str(
            'sort_filter_alphabetical',
            'contentmarketplace_linkedin'
          ),
          id: 'ALPHABETICAL',
        },
      ],
    };
  },

  apollo: {
    categoryOptions: {
      query: courseCategoriesQuery,
    },

    learningObjects: {
      query: learningObjectsQuery,
      variables() {
        return {
          input: {
            filters: {
              asset_type: this.selectedFilters.assetType,
              ids: null,
              language: this.selectedLanguage,
              search: this.selectedFilters.search,
              time_to_complete: this.selectedFilters.timeToComplete,
            },
            pagination: {
              limit: this.paginationLimit,
              page: this.paginationPage,
            },
            sort_by: this.selectedSortOrderFilter,
          },
        };
      },
      update({ result: data }) {
        // return data;
        // TODO: Remove this when actual courses come through via the query.
        return Object.assign({}, data, {
          items: data.items.map(item => {
            return Object.assign({}, item, {
              courses: [
                { fullname: 'example course 1' },
                { fullname: 'example course 2' },
                { fullname: 'example course 3' },
                { fullname: 'example course 4' },
              ],
            });
          }),
        });
      },
    },

    reviewingLearningObjects: {
      query: learningObjectsQuery,
      skip() {
        return !this.reviewingSelectedItems;
      },
      variables() {
        return {
          input: {
            filters: {
              asset_type: null,
              ids: this.reviewingItemList,
              language: this.selectedLanguage,
              search: null,
              time_to_complete: null,
            },
            pagination: {
              limit: 20,
              page: 1,
            },
            sort_by: 'LATEST',
          },
        };
      },
      update({ result: data }) {
        // return data;
        // TODO: Remove this when actual courses come through via the query.
        return Object.assign({}, data, {
          items: data.items.map(item => {
            return Object.assign({}, item, {
              courses: [
                { fullname: 'example course 1' },
                { fullname: 'example course 2' },
                { fullname: 'example course 3' },
                { fullname: 'example course 4' },
              ],
            });
          }),
        });
      },
    },

    filters: {
      query: filterOptionsQuery,
      variables() {
        return {
          input: {
            language: this.selectedLanguage,
          },
        };
      },
      update({ result: data }) {
        data = JSON.parse(JSON.stringify(data));

        let filterOptions = {
          assetType: data.asset_type,
          subjects: data.subjects,
          timeToComplete: data.time_to_complete,
        };
        return filterOptions;
      },
    },
  },

  computed: {
    /**
     * Are we currently mutating or querying data via graphQL?
     *
     * @return {Boolean}
     */
    isLoading() {
      return this.$apollo.loading;
    },
  },

  methods: {
    /**
     * Remove all selected items from basket
     *
     */
    clearSelectedItems() {
      this.selectedItems = [];
    },

    /**
     * Creating courses
     *
     */
    async createCourses() {
      try {
        const {
          data: { payload },
        } = await this.$apollo.mutate({
          mutation: createCourseQuery,
          variables: {
            input: this.selectedItems.map(item => {
              return {
                learning_object_id: item,
                category_id: this.selectedItemCategories[item].id,
              };
            }),
          },
        });

        if (payload.redirect_url) {
          window.location.href = payload.redirect_url;
          return;
        }

        if (payload.message.length > 0) {
          await notify({
            message: payload.message,
            type: payload.success ? 'success' : 'error',
          });
        }
      } catch (e) {
        await notify({
          message: this.$str(
            'content_creation_unknown_failure',
            'contentmarketplace_linkedin'
          ),
          type: 'error',
        });
      }
    },

    /**
     * Reset active side panel filters
     *
     */
    resetPanelFilters() {
      this.selectedFilters = {
        assetType: [],
        search: '',
        subjects: [],
        timeToComplete: [],
      };
    },

    /**
     * Set all selected item categories to the default
     *
     */
    setAllCategoriesToDefault() {
      let selectedCategories = {};

      // Add an entry for each selected course and set it to the default
      this.selectedItems.forEach(key => {
        selectedCategories[key] = this.selectedCategory;
      });

      // Reset individual item categories to the default
      this.reviewingItemCategories = selectedCategories;
    },

    /**
     * Set the default selected category for all courses
     *
     * @param {String} value
     */
    setDefaultSelectedCategory(value) {
      // Set to default if no value
      if (value === null) {
        this.selectedCategory = {
          id: null,
          label: null,
        };

        return;
      }

      // Store key and string for selected value
      this.selectedCategory = this.categoryOptions.find(key => {
        return key.id === value;
      });
    },

    /**
     * Update number of items displayed in paginated selection results
     *
     * @param {Number} limit
     */
    setItemsPerPage(limit) {
      this.paginationLimit = limit;
    },

    /**
     * Set the next page for the reviewing load more button
     *
     * @param {Number} page
     */
    setLoadMorePage(page) {
      this.reviewingLoadMorePage = page;
    },

    /**
     * Update current paginated page of selection results
     *
     * @param {Number} page
     */
    setPaginationPage(page) {
      this.paginationPage = page;
    },

    /**
     * Set the language primary filter value
     *
     * @param {String} value
     */
    setPrimaryFilter(value) {
      this.resetPanelFilters();
      this.selectedLanguage = value;
    },

    /**
     * Set selected course items (chosen from the table)
     *
     * @param {Array} items
     */
    setSelectedItems(items) {
      this.selectedItems = items;
    },

    /**
     * Update selected category for a single course
     *
     * @param {Object} value
     */
    setSingleCourseCategory(data) {
      // Get string & ID for selected value
      let selectedCategory = this.categoryOptions.find(key => {
        return key.id === data.value;
      });

      this.reviewingItemCategories[data.courseId] = selectedCategory;
    },

    /**
     * Set the sort order filter value
     *
     * @param {String} value
     */
    setSortOrderFilter(value) {
      this.selectedSortOrderFilter = value;
    },

    /**
     * Update displayed results on review page (load more)
     *
     */
    updateReviewPage() {
      // Increase page number
      this.setLoadMorePage(this.reviewingLoadMorePage + 1);

      // Fetch additional data
      this.$apollo.queries.reviewingLearningObjects.fetchMore({
        variables: {
          input: {
            filters: {
              asset_type: [],
              ids: this.reviewingItemList,
              language: this.selectedLanguage,
              search: '',
              time_to_complete: [],
            },
            pagination: {
              limit: 20,
              page: this.reviewingLoadMorePage,
            },
            sort_by: 'LATEST',
          },
        },
        updateQuery: (previousResult, { fetchMoreResult }) => {
          fetchMoreResult.result.items.unshift(...previousResult.result.items);
          return fetchMoreResult;
        },
      });
    },

    /**
     * Update the view (either viewing catalogue or reviewing selected items)
     *
     * @param {Boolean} reviewing
     */
    switchContentView(reviewing) {
      // If switching to review display, update default categories of items
      if (reviewing) {
        // Reset load more button
        this.setLoadMorePage(1);

        // Set all item categories to the default value
        this.setAllCategoriesToDefault();

        // Provide selected item list as a unique array
        this.reviewingItemList = this.selectedItems;
      } else {
        // Reset filters
        this.resetPanelFilters();

        // Reset pagination settings
        this.setItemsPerPage(20);
        this.setPaginationPage(1);
      }

      // Switch view
      this.reviewingSelectedItems = reviewing;
    },
  },
};
</script>

<lang-strings>
  {
    "contentmarketplace_linkedin": [
      "catalog_title",
      "catalog_review_title",
      "content_creation_unknown_failure",
      "sort_filter_alphabetical",
      "sort_filter_latest"
    ],
    "totara_contentmarketplace": [
      "back_to_manage_content_marketplaces"
    ]
  }
</lang-strings>
