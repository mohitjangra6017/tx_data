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
    :loading="false"
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
      <CountAndFilters :count="items.length" :filters="activeFilterStrings" />
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
        :items="items"
        :selected-items="selectedItems"
        @update="setSelectedItems"
      />
    </template>

    <!-- Table of selected courses -->
    <template v-slot:review-table>
      <ReviewTable
        :category-options="categoryOptions"
        :items="items"
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
      items: [
        {
          id: 1,
          courses: [
            { fullname: 'example course 1' },
            { fullname: 'example course 2' },
            { fullname: 'example course 3' },
            { fullname: 'example course 4' },
            { fullname: 'example course 5' },
            { fullname: 'example course 6' },
            { fullname: 'example course 7' },
            { fullname: 'example course 8' },
            { fullname: 'example course 9' },
            { fullname: 'example course 10' },
            { fullname: 'example course 11' },
            { fullname: 'example course 12' },
          ],
          level: 'INTERMEDIATE',
          subject: 'Leadership',
          image_url:
            '../../../server/pluginfile.php/1336/course/images/1620708777/image?preview=totara_catalog_medium&theme=ventura',
          time: '9h 7m',
          name: 'Become a Leader',
          type: 'learningPath',
        },
        {
          id: 2,
          courses: [{ fullname: 'example course 1' }],
          level: 'BEGINNER',
          subject: 'Communication',
          image_url:
            '../../../server/pluginfile.php/2661/course/images/1620710323/image?preview=totara_catalog_medium&theme=ventura',
          time: '4h 22m',
          name: 'Diversity, Inclusion, and Belonging for All',
          type: 'learningPath',
        },
        {
          id: 3,
          courses: [],
          level: 'INTERMEDIATE',
          subject: 'Development',
          image_url:
            '../../../server/pluginfile.php/2685/course/images/1620710698/image?preview=totara_catalog_medium&theme=ventura',
          time: '1h 47m',
          name: 'Software Testing: Planning Tests for Mobile',
          type: 'course',
        },
        {
          id: 4,
          courses: [
            { fullname: 'example course 1' },
            { fullname: 'example course 4' },
          ],
          level: 'ADVANCED',
          subject: 'Financial',
          image_url:
            '../../../server/pluginfile.php/2679/course/images/1620711202/image?preview=totara_catalog_medium&theme=ventura',
          time: '4m 30s',
          name: '5 Tips for Building Your Financial Life',
          type: 'course',
        },
        {
          id: 5,
          courses: [{ fullname: 'example course 1' }],
          level: 'BEGINNER',
          subject: 'Spreadsheets',
          image_url:
            '../../../server/pluginfile.php/2673/course/images/1620711691/image?preview=totara_catalog_medium&theme=ventura',
          time: '24h 43m',
          name: 'Master Microsoft Excel',
          type: 'learningPath',
        },
        {
          id: 6,
          courses: [],
          level: 'INTERMEDIATE',
          subject: 'Leadership',
          image_url:
            '../../../server/pluginfile.php/2667/course/images/1620712061/image?preview=totara_catalog_medium&theme=ventura',
          time: '6h 40m',
          name: 'Become an Inclusive Leader',
          type: 'learningPath',
        },
        {
          id: 7,
          courses: [
            { fullname: 'example course 1' },
            { fullname: 'example course 4' },
          ],
          level: 'BEGINNER',
          subject: 'Customer Support',
          image_url:
            '../../../server/pluginfile.php/2655/course/images/1620712305/image?preview=totara_catalog_medium&theme=ventura',
          time: '5h 25m',
          name: 'Become a Customer Service Specialist',
          type: 'learningPath',
        },
      ],
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
