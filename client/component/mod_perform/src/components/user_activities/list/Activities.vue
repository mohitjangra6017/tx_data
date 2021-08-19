<!--
  This file is part of Totara Enterprise Extensions.

  Copyright (C) 2020 onwards Totara Learning Solutions LTD

  Totara Enterprise Extensions is provided only to Totara
  Learning Solutions LTD's customers and partners, pursuant to
  the terms and conditions of a separate agreement with Totara
  Learning Solutions LTD or its affiliate.

  If you do not have an agreement with Totara Learning Solutions
  LTD, you may not access, use, modify, or distribute this software.
  Please contact [licensing@totaralearning.com] for more information.

  @author Jaron Steenson <jaron.steenson@totaralearning.com>
  @author Kevin Hottinger <kevin.hottinger@totaralearning.com>
  @module mod_perform
-->
<template>
  <div class="tui-performUserActivityList">
    <ActivitiesFilter
      v-model="userFilters"
      :about="about"
      :filter-options="filterOptions"
      @filter-change="filterChange"
    />

    <ActivitiesCount
      v-model="sortByFilter"
      :about="about"
      :displayed-count="subjectInstances.length"
      :loading="$apollo.loading"
      :sort-by-options="sortByOptions"
      :total="totalActivities"
    />

    <Loader :loading="$apollo.loading">
      <Table
        v-if="!$apollo.loading"
        ref="activity-table"
        :data="subjectInstances"
        :expandable-rows="true"
        :no-items-text="emptyListText"
        :stack-at="850"
      >
        <template v-slot:header-row>
          <ExpandCell :header="true" />

          <!-- Activity name header -->
          <HeaderCell :size="isAboutOthers ? '4' : '6'">
            {{ $str('user_activities_title_header', 'mod_perform') }}
          </HeaderCell>

          <!-- User name header -->
          <HeaderCell v-if="isAboutOthers" size="2">
            {{ $str('user_activities_subject_header', 'mod_perform') }}
          </HeaderCell>

          <!-- Job assignment header -->
          <HeaderCell size="2">
            {{ $str('user_activities_job_assignment_header', 'mod_perform') }}
          </HeaderCell>

          <!-- Due date header -->
          <HeaderCell size="2">
            {{ $str('user_activities_due_date_header', 'mod_perform') }}
          </HeaderCell>

          <!-- Activity type header -->
          <HeaderCell size="2">
            {{ $str('user_activities_type_header', 'mod_perform') }}
          </HeaderCell>

          <!-- Your progress header -->
          <HeaderCell size="2">
            {{
              $str('user_activities_status_header_participation', 'mod_perform')
            }}
          </HeaderCell>

          <!-- Overall progress header -->
          <HeaderCell size="2">
            {{ $str('user_activities_status_header_activity', 'mod_perform') }}
          </HeaderCell>
        </template>

        <!-- Row expandable -->
        <template v-slot:row="{ expand, expandState, row }">
          <ExpandCell
            :aria-label="getExpandLabel(row)"
            :expand-state="expandState"
            @click="expand()"
          />

          <!-- Activity name -->
          <Cell
            :column-header="$str('user_activities_title_header', 'mod_perform')"
            :size="isAboutOthers ? '4' : '6'"
            valign="center"
          >
            <Button
              v-if="
                currentUserHasMultipleRelationships(
                  row.subject.participant_instances
                )
              "
              :styleclass="{ transparent: true }"
              class="tui-performUserActivityList__selectRelationshipLink"
              :text="getActivityTitle(row.subject)"
              @click.prevent="showRelationshipSelector(row)"
            />
            <a v-else :href="getViewActivityUrl(row)">
              {{ getActivityTitle(row.subject) }}
            </a>
          </Cell>

          <!-- User name (removed for own activities) -->
          <Cell
            v-if="isAboutOthers"
            :column-header="
              $str('user_activities_subject_header', 'mod_perform')
            "
            size="2"
            valign="center"
          >
            {{ row.subject.subject_user.fullname }}
          </Cell>

          <!-- Job assignment -->
          <Cell
            :column-header="
              $str('user_activities_job_assignment_header', 'mod_perform')
            "
            size="2"
            valign="center"
          >
            {{ getJobAssignmentDescription(row.subject.job_assignment) }}
          </Cell>

          <!-- Due date -->
          <Cell
            :column-header="
              $str('user_activities_due_date_header', 'mod_perform')
            "
            size="2"
            valign="center"
          >
            {{ row.subject.due_date }}
          </Cell>

          <!-- Activity type -->
          <Cell
            :column-header="$str('user_activities_type_header', 'mod_perform')"
            size="2"
            valign="center"
          >
            {{ row.subject.activity.type.display_name }}
          </Cell>

          <!-- Your progress -->
          <Cell
            :column-header="
              $str('user_activities_status_header_participation', 'mod_perform')
            "
            size="2"
            valign="center"
          >
            {{ getYourProgressText(row.subject.participant_instances) }}

            <OverdueLozenge
              v-if="
                allYourInstancesAreOverdue(row.subject.participant_instances)
              "
            />

            <Lock
              v-if="
                allYourInstancesAreClosed(row.subject.participant_instances)
              "
              :alt="$str('user_activities_closed', 'mod_perform')"
            />
          </Cell>

          <!-- Overall progress -->
          <Cell
            :column-header="
              $str('user_activities_status_header_activity', 'mod_perform')
            "
            size="2"
            valign="center"
          >
            {{ getStatusText(row.subject.progress_status) }}

            <OverdueLozenge v-if="row.subject.is_overdue" />

            <Lock
              v-if="row.subject.availability_status === 'CLOSED'"
              :alt="$str('user_activities_closed', 'mod_perform')"
            />
          </Cell>
        </template>

        <!-- Expanded row content -->
        <template v-slot:expand-content="{ row }">
          <div class="tui-performUserActivityList__expandedRow">
            <p class="tui-performUserActivityList__expandedRow-dateSummary">
              {{
                $str(
                  'user_activities_created_at',
                  'mod_perform',
                  row.subject.created_at
                )
              }}

              <span v-if="row.subject.due_date">
                {{
                  $str(
                    'user_activities_complete_before',
                    'mod_perform',
                    row.subject.due_date
                  )
                }}
              </span>

              <span v-if="isSingleSectionViewOnly(row.subject.activity.id)">
                {{
                  $str(
                    'user_activities_single_section_view_only_activity',
                    'mod_perform'
                  )
                }}
              </span>
            </p>

            <SectionsList
              :activity-id="row.subject.activity.id"
              :anonymous-responses="row.subject.activity.anonymous_responses"
              :current-user-id="currentUserId"
              :is-multi-section-active="
                row.subject.activity.settings.multisection
              "
              :subject-sections="row.sections"
              :subject-user="row.subject.subject_user"
              :view-url="viewUrl"
              @single-section-view-only="flagActivitySingleSectionViewOnly"
            />

            <Button
              :text="$str('print_activity', 'mod_perform')"
              :styleclass="{ small: true }"
              @click="printActivity(row)"
            />
          </div>
        </template>
      </Table>
      <div class="tui-performUserActivityList__paging">
        <Paging
          v-if="totalActivities"
          :items-per-page="paginationLimit"
          :page="paginationPage"
          :total-items="totalActivities"
          @count-change="setItemsPerPage"
          @page-change="setPaginationPage"
        />
      </div>
    </Loader>

    <ModalPresenter
      :open="isRelationshipSelectorShown"
      @request-close="hideRelationshipSelector"
    >
      <RelationshipSelector
        v-model="isRelationshipSelectorShown"
        :current-user-id="currentUserId"
        :is-for-section="false"
        :participant-sections="selectedParticipantSections"
        :subject-user="selectedSubjectUser"
        :view-url="relationshipSelectorUrl"
      />
    </ModalPresenter>
  </div>
</template>
<script>
import ActivitiesCount from 'mod_perform/components/user_activities/list/ActivitiesCount';
import ActivitiesFilter from 'mod_perform/components/user_activities/list/ActivitiesFilter';
import Button from 'tui/components/buttons/Button';
import Cell from 'tui/components/datatable/Cell';
import ExpandCell from 'tui/components/datatable/ExpandCell';
import HeaderCell from 'tui/components/datatable/HeaderCell';
import Loader from 'tui/components/loading/Loader';
import Lock from 'tui/components/icons/Lock';
import ModalPresenter from 'tui/components/modal/ModalPresenter';
import OverdueLozenge from 'mod_perform/components/user_activities/list/ActivityOverdue';
import Paging from 'tui/components/paging/Paging';
import RelationshipSelector from 'mod_perform/components/user_activities/list/RelationshipSelector';
import SectionsList from 'mod_perform/components/user_activities/list/Sections';
import Table from 'tui/components/datatable/Table';
// Query
import subjectInstancesQuery from 'mod_perform/graphql/my_subject_instances';

export default {
  components: {
    ActivitiesCount,
    ActivitiesFilter,
    Button,
    Cell,
    ExpandCell,
    HeaderCell,
    Loader,
    Lock,
    ModalPresenter,
    OverdueLozenge,
    Paging,
    RelationshipSelector,
    SectionsList,
    Table,
  },

  props: {
    about: {
      type: String,
      validator(val) {
        return ['self', 'others'].includes(val);
      },
    },
    //The id of the logged in user.
    currentUserId: {
      required: true,
      type: Number,
    },
    filterOptions: Object,
    printUrl: {
      type: String,
      required: true,
    },
    // An Array of sort options
    sortByOptions: Array,
    viewUrl: {
      type: String,
      required: true,
    },
  },

  data() {
    return {
      activeFilterCount: 0,
      isRelationshipSelectorShown: false,
      // items per page limit
      paginationLimit: 20,
      // Current pagination page
      paginationPage: 1,
      relationshipSelectorUrl: '',
      selectedParticipantSections: [],
      selectedSubjectUser: {},
      singleSectionViewOnlyActivities: [],
      sortByFilter: 'created_at',
      subjectInstances: [],
      // Count of activities across all pages
      totalActivities: 0,
      userFilters: {
        activityType: null,
        excludeCompleted: false,
        ownProgress: null,
        overdueOnly: false,
        search: null,
      },
    };
  },

  computed: {
    isAboutOthers() {
      return this.about === 'others';
    },

    emptyListText() {
      return this.activeFilterCount
        ? this.$str('user_activities_list_none_filtered', 'mod_perform')
        : this.isAboutOthers
        ? this.$str('user_activities_list_none_about_others', 'mod_perform')
        : this.$str('user_activities_list_none_about_self', 'mod_perform');
    },

    /**
     * Active filter options (reactive value for the query)
     *
     * @return {Object}
     */
    currentFilterOptions() {
      return {
        about: [this.about.toUpperCase()],
        activity_type: this.userFilters.activityType,
        exclude_complete: this.userFilters.excludeCompleted,
        overdue: this.userFilters.overdueOnly,
        participant_progress: this.userFilters.ownProgress,
        search_term: this.userFilters.search,
      };
    },
  },

  apollo: {
    subjectInstances: {
      query: subjectInstancesQuery,
      variables() {
        return {
          filters: this.currentFilterOptions,
          options: {
            sort_by: this.sortByFilter,
          },
          pagination: {
            limit: this.paginationLimit,
            page: this.paginationPage,
          },
        };
      },
      update: data => data['mod_perform_my_subject_instances'].items,
      result({ data }) {
        this.totalActivities = data.mod_perform_my_subject_instances.total;
      },
    },
  },

  methods: {
    /**
     * Get "view" url for a specific user activity.
     * This method should only be used in the case of single relationships.
     *
     * @param subjectInstance {{Object}}
     * @returns {string}
     * @see showRelationshipSelector
     */
    getViewActivityUrl(subjectInstance) {
      const participantSection = this.getFirstSectionToParticipate(
        subjectInstance.sections
      );
      if (participantSection) {
        return this.$url(this.viewUrl, {
          participant_section_id: participantSection.id,
        });
      }
      return '';
    },

    /**
     * Get text to describe the subject instance's job assignment.
     *
     * @param {Object|NULL} jobAssignment
     * @return {string|null}
     */
    getJobAssignmentDescription(jobAssignment) {
      if (!jobAssignment) {
        return;
      }
      let fullname = jobAssignment.fullname;

      if (fullname) {
        fullname = fullname.trim();
      }

      // Fullname isn't a required field when creating a job assignment
      return fullname && fullname.length > 0
        ? fullname
        : this.$str(
            'unnamed_job_assignment',
            'mod_perform',
            jobAssignment.idnumber
          );
    },

    /**
     * Get the first section, if relationship id is supplied it will get the first section
     * for the user with the given relationship
     *
     * @param {Array} subjectSections
     * @return {Object|Null} returns a participant_section object
     */
    getFirstSectionToParticipate(subjectSections) {
      let foundSection = null;

      subjectSections.forEach(subjectSection => {
        let found = subjectSection.participant_sections.find(
          item => item.participant_instance.is_for_current_user
        );
        if (found && foundSection === null) {
          foundSection = found;
        }
      });

      return foundSection;
    },

    /**
     * Open the relationship selector modal.
     *
     * @param {Object} selectedSubjectInstance
     * @param {Boolean=undefined} isForPrint
     */
    showRelationshipSelector(selectedSubjectInstance, isForPrint) {
      this.selectedSubjectUser = selectedSubjectInstance.subject.subject_user;
      this.selectedParticipantSections = [];
      selectedSubjectInstance.sections.forEach(subjectSection => {
        subjectSection.participant_sections.forEach(participantSection => {
          this.selectedParticipantSections.push(participantSection);
        });
      });
      this.relationshipSelectorUrl = isForPrint ? this.printUrl : this.viewUrl;
      this.isRelationshipSelectorShown = true;
    },

    /**
     * Close the relationship selector modal.
     */
    hideRelationshipSelector() {
      this.isRelationshipSelectorShown = false;
    },

    /**
     * Get the localized status text for a particular user activity.
     *
     * @param status {String}
     * @returns {string}
     */
    getStatusText(status) {
      switch (status) {
        case 'NOT_STARTED':
          return this.$str('user_activities_status_not_started', 'mod_perform');
        case 'IN_PROGRESS':
          return this.$str('user_activities_status_in_progress', 'mod_perform');
        case 'COMPLETE':
          return this.$str('user_activities_status_complete', 'mod_perform');
        case 'PROGRESS_NOT_APPLICABLE':
          return this.$str(
            'user_activities_status_not_applicable',
            'mod_perform'
          );
        case 'NOT_SUBMITTED':
          return this.$str(
            'user_activities_status_not_submitted',
            'mod_perform'
          );
        default:
          return '';
      }
    },

    /**
     * Get the current users progress on a particular subject instance.
     *
     * @param {Object[]} participantInstances - The participant instances from the subject instance we are getting the progress text for
     * @returns {string}
     */
    getYourProgressText(participantInstances) {
      let relationships = this.filterToCurrentUser(
        participantInstances
      ).map(instance =>
        this.getParticipantStatusText(instance.progress_status)
      );

      return relationships.join(', ');
    },

    /**
     * Get the localized status text for a particular participant .
     *
     * @param status {String}
     * @returns {string}
     */
    getParticipantStatusText(status) {
      switch (status) {
        case 'NOT_STARTED':
          return this.$str(
            'participant_instance_status_not_started',
            'mod_perform'
          );
        case 'IN_PROGRESS':
          return this.$str(
            'participant_instance_status_in_progress',
            'mod_perform'
          );
        case 'COMPLETE':
          return this.$str(
            'participant_instance_status_complete',
            'mod_perform'
          );
        case 'PROGRESS_NOT_APPLICABLE':
          return this.$str(
            'participant_instance_status_progress_not_applicable',
            'mod_perform'
          );
        case 'NOT_SUBMITTED':
          return this.$str(
            'participant_instance_status_not_submitted',
            'mod_perform'
          );
        default:
          return '';
      }
    },

    /**
     * Checks if all participant instances are closed.
     *
     * @param {Array} participantInstances
     * @return {Boolean}
     */
    allYourInstancesAreClosed(participantInstances) {
      return !participantInstances.find(pi => {
        return (
          parseInt(pi.participant_id) === this.currentUserId &&
          pi.availability_status &&
          pi.availability_status !== 'CLOSED'
        );
      });
    },

    /**
     * Checks if all participant instances are overdue.
     *
     * @param {Array} participantInstances
     * @return {Boolean}
     */
    allYourInstancesAreOverdue(participantInstances) {
      return !participantInstances.find(
        pi =>
          parseInt(pi.participant_id) === this.currentUserId && !pi.is_overdue
      );
    },

    /**
     * Returns the activity title generated from the subject instance passed it.
     *
     * @param {Object} subject
     * @returns {string}
     */
    getActivityTitle(subject) {
      var title = subject.activity.name.trim();
      var suffix = subject.created_at ? subject.created_at.trim() : '';

      if (suffix) {
        return this.$str(
          'activity_title_with_subject_creation_date',
          'mod_perform',
          {
            title: title,
            date: suffix,
          }
        );
      }

      return title;
    },

    /**
     * The label to show for the expand row button.
     *
     * @param {Object} subjectInstance
     * @returns {string}
     */
    getExpandLabel(subjectInstance) {
      const activityTitle = this.getActivityTitle(subjectInstance.subject);
      if (!this.isAboutOthers) {
        return activityTitle;
      }
      return this.$str('activity_title_for_subject', 'mod_perform', {
        activity: activityTitle,
        user: subjectInstance.subject.subject_user.fullname,
      });
    },

    /**
     * Does the logged in user have multiple relationships to the subject on an activity.
     *
     * @param {Array} participantInstances
     * @return {Boolean}
     */
    currentUserHasMultipleRelationships(participantInstances) {
      return this.filterToCurrentUser(participantInstances).length > 1;
    },

    /**
     * Filter participant instances to only ones that belong to the logged in user.
     *
     * @param {Object[]} participantInstances
     * @return {Object[]}
     */
    filterToCurrentUser(participantInstances) {
      return participantInstances.filter(pi => pi.is_for_current_user);
    },

    /**
     * Add to the list of activities that only have one section where current user has view-only access.
     *
     * @param {Number} activityId
     */
    flagActivitySingleSectionViewOnly(activityId) {
      this.singleSectionViewOnlyActivities.push(activityId);
    },

    /**
     * Find out if an activity has only one section where current user has view-only access.
     *
     * @param activityId
     * @returns {boolean}
     */
    isSingleSectionViewOnly(activityId) {
      return this.singleSectionViewOnlyActivities.includes(activityId);
    },

    /**
     * Open print-friendly page with activity.
     *
     * @param subjectInstance
     * @return {undefined}
     */
    printActivity(subjectInstance) {
      if (
        this.currentUserHasMultipleRelationships(
          subjectInstance.subject.participant_instances
        )
      ) {
        this.showRelationshipSelector(subjectInstance, true);
        return;
      }

      const participantSection = this.getFirstSectionToParticipate(
        subjectInstance.sections
      );
      const url = this.$url(this.printUrl, {
        participant_section_id: participantSection.id,
      });
      window.open(url);
    },

    /**
     * Update active filter count and reset page
     *
     * @param {Number} activeFilterCount
     */
    filterChange(activeFilterCount) {
      this.activeFilterCount = activeFilterCount;
      this.paginationPage = 1;
    },

    /**
     * Update number of items displayed per page
     *
     * @param {Number} limit
     */
    setItemsPerPage(limit) {
      if (this.$refs['activity-table']) {
        this.$refs['activity-table'].$el.scrollIntoView();
      }

      this.paginationLimit = limit;
    },

    /**
     * Update current paginated page
     *
     * @param {Number} page
     */
    setPaginationPage(page) {
      if (this.$refs['activity-table']) {
        this.$refs['activity-table'].$el.scrollIntoView();
      }

      this.paginationPage = page;
    },
  },
};
</script>
<lang-strings>
  {
    "mod_perform": [
      "activity_title_for_subject",
      "activity_title_with_subject_creation_date",
      "participant_instance_status_complete",
      "participant_instance_status_in_progress",
      "participant_instance_status_not_started",
      "participant_instance_status_not_submitted",
      "participant_instance_status_progress_not_applicable",
      "print_activity",
      "unnamed_job_assignment",
      "user_activities_closed",
      "user_activities_complete_before",
      "user_activities_created_at",
      "user_activities_due_date_header",
      "user_activities_filter",
      "user_activities_job_assignment_header",
      "user_activities_list_none_about_others",
      "user_activities_list_none_about_self",
      "user_activities_list_none_filtered",
      "user_activities_single_section_view_only_activity",
      "user_activities_status_complete",
      "user_activities_status_header_activity",
      "user_activities_status_header_participation",
      "user_activities_status_in_progress",
      "user_activities_status_not_applicable",
      "user_activities_status_not_started",
      "user_activities_status_not_submitted",
      "user_activities_subject_header",
      "user_activities_title_header",
      "user_activities_type_header"
    ]
  }
</lang-strings>

<style lang="scss">
.tui-performUserActivityList {
  min-height: 500px;

  & > * + * {
    margin-top: var(--gap-4);
  }

  &__selectRelationshipLink {
    text-align: left;
    @include tui-font-link();
  }

  &__expandedRow {
    padding: var(--gap-6) var(--gap-4);

    & > * + * {
      margin-top: var(--gap-6);
    }

    &-dateSummary {
      color: var(--color-neutral-6);
    }
  }

  &__paging {
    margin-top: var(--gap-5);
  }
}
</style>
