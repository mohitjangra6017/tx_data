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
        @reviewing-selection="viewReviewContent"
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
    </template>

    <!-- Table of selected courses -->
    <template v-slot:review-table>
      <ReviewTable
        :category-options="categoryOptions"
        :items="learningObjects.items"
        :selected-item-categories="selectedItemCategories"
        :selected-items="selectedItems"
        @change-course-category="setSingleCourseCategory"
        @update="setSelectedItems"
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
import ReviewTable from 'contentmarketplace_linkedin/components/tables/LinkedInReviewTable';
import SelectionTable from 'contentmarketplace_linkedin/components/tables/LinkedInSelectionTable';
import SortFilter from 'totara_contentmarketplace/components/filters/SortFilter';
// Query
import learningObjectsQuery from 'contentmarketplace_linkedin/graphql/catalog_import_learning_objects';

export default {
  components: {
    Basket,
    CountAndFilters,
    Layout,
    LinkedInFilters,
    LinkedInPrimaryFilter,
    PageBackLink,
    ReviewTable,
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
      categoryOptions: [
        { label: 'Marketing', id: 'marketing' },
        { label: 'User experience', id: 'user' },
        { label: 'Security', id: 'sec' },
      ],
      learningObjects: {
        items: [],
        total: 0,
      },
      filters: {
        software: [
          {
            id: 'software',
            label: 'Software',
            content: {
              items: [
                {
                  id: 'linux',
                  label: 'Linux',
                },
                {
                  id: 'mac',
                  label: 'Mac',
                },
                {
                  id: 'windows',
                  label: 'Windows',
                },
              ],
            },
            children: [],
          },
        ],
        subject: [
          {
            id: 'subjects',
            label: 'Subjects',
            content: {},
            children: [
              {
                id: 'business',
                label: 'Business',
                content: {
                  items: [
                    {
                      id: 'cloud',
                      label: 'Cloud computing',
                    },
                    {
                      id: 'datascience',
                      label: 'Data science',
                    },
                  ],
                },
                children: [],
              },
              {
                id: 'creative',
                label: 'Creative',
                content: {
                  items: [
                    {
                      id: 'webDevelopment',
                      label: 'Web development',
                    },
                    {
                      id: 'mobileDevelopment',
                      label: 'Mobile development',
                    },
                  ],
                },
                children: [],
              },
            ],
          },
        ],
        type: [
          {
            id: 'type',
            label: 'Type',
            content: {
              items: [
                {
                  id: 'course',
                  label: 'Course',
                },
                {
                  id: 'learningPath',
                  label: 'Learning Path',
                },
                {
                  id: 'video',
                  label: 'Video',
                },
              ],
            },
            children: [],
          },
        ],
        time: [
          {
            id: 'time',
            label: 'Time to complete',
            content: {
              items: [
                {
                  id: 'lessThat5',
                  label: '0 - 5 minutes',
                },
                {
                  id: '1HourOrLess',
                  label: 'Less than 1 hour',
                },
                {
                  id: 'lessThan3Hours',
                  label: 'Less than 3 hours',
                },
              ],
            },
            children: [],
          },
        ],
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
      // Open Filter tree branches
      openBranches: {
        software: [],
        subjects: ['subjects'],
        time: [],
        type: [],
      },
      // Selected category value
      selectedCategory: {
        id: null,
        label: null,
      },
      selectedFilters: {
        search: '',
        software: [],
        subjects: [],
        time: [],
        type: [],
      },
      selectedItemCategories: {},
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
      // Showing display for reviewing selected items
      reviewingSelectedItems: false,
    };
  },

  apollo: {
    learningObjects: {
      query: learningObjectsQuery,
      variables() {
        return {
          input: {
            pagination: {
              cursor: null,
            },
            sort_by: this.selectedSortOrderFilter,
            filters: {
              language: this.selectedLanguage,
            },
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
    createCourses() {
      // TODO
      console.log('creating courses');
    },

    /**
     * Reset active side panel filters
     *
     */
    resetPanelFilters() {
      this.selectedFilters = {
        search: '',
        software: [],
        subjects: [],
        time: [],
        type: [],
      };
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

      this.selectedItemCategories[data.courseId] = selectedCategory;
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
     * Update the view (either viewing catalogue or reviewing selected items)
     *
     * @param {Boolean} reviewing
     */
    viewReviewContent(reviewing) {
      // If switching to review display, update default categories of items
      if (reviewing) {
        let selectedCategories = {};

        // Add an entry for each selected course and set it to the default
        this.selectedItems.forEach(key => {
          selectedCategories[key] = this.selectedCategory;
        });

        // Reset individual item categories to the default
        this.selectedItemCategories = selectedCategories;
      }

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
      "sort_filter_alphabetical",
      "sort_filter_latest"
    ],
    "totara_contentmarketplace": [
      "back_to_manage_content_marketplaces"
    ]
  }
</lang-strings>
