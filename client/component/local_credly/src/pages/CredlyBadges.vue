<!--
    @copyright   City and Guilds Kineo 2021
    @author      Michael Geering <michael.geering@kineo.com>
-->

<template>
  <div class="credly-badges">
    <PageHeading :title="$str('page:badges:heading', 'local_credly')" />

    <ButtonGroup>
      <Button
        :disabled="syncStarted"
        :styleclass="{ primary: 'true' }"
        :text="$str('page:badges:sync', 'local_credly')"
        :aria-label="$str('page:badges:sync', 'local_credly')"
        @click="sync"
      />
    </ButtonGroup>
    <NotificationBanner
      v-if="syncStarted"
      type="info"
      :message="$str('page:badges:synchronising', 'local_credly')"
    />
    <Loader :loading="($apollo.loading && (!hasBadges || filters.length > 0)) || syncStarted">
      <div v-if="(hasBadges || filters.length > 0) && !syncStarted" class="content">
        <LearningFilter
          @submit-search="updateFilter"
          @filter="updateFilter"
          @clear="clearFilter"
        />
        <p>
          {{
            this.$str('page:badges:showing_x_of_y_badges', 'local_credly', {
              count: getBadges().length,
              total: badges.metadata.total
            })
          }}
        </p>
        <ProgramAdder
          :open="showProgramAdder"
          :existing-items="[]"
          :title="programAdderTitle"
          :table-header-name="'Program Name'"
          @cancel="hideProgramAdder"
          @added="programAdderDataUpdated"
        />
        <CertificationAdder
            :open="showCertificationAdder"
            :existing-items="[]"
            :title="certificationAdderTitle"
            :table-header-name="'Certification Name'"
            @cancel="hideCertificationAdder"
            @added="certificationAdderDataUpdated"
        />
        <CourseAdder
          :open="showCourseAdder"
          :existing-items="[]"
          :title="courseAdderTitle"
          :table-header-name="'Course Name'"
          @cancel="hideCourseAdder"
          @added="courseAdderDataUpdated"
        />
        <Table
            :data="getBadges()"
            :color-odd-rows="true"
        >
          <template v-slot:header-row>
            <HeaderCell size="3">{{ $str('page:badges:headerName', 'local_credly') }}</HeaderCell>
            <HeaderCell size="3">{{ $str('page:badges:headerId', 'local_credly') }}</HeaderCell>
            <HeaderCell size="3">{{ $str('page:badges:headerType', 'local_credly') }}</HeaderCell>
            <HeaderCell size="3">{{ $str('page:badges:headerLearning', 'local_credly') }}</HeaderCell>
            <HeaderCell class="link-to-learning-header" size="2">{{ $str('page:badges:headerLinkToLearning', 'local_credly') }}</HeaderCell>
          </template>
          <template v-slot:row="{ row }">
            <Cell size="3" :column-header="$str('page:badges:headerName', 'local_credly')">
              {{ row.name }}
            </Cell>

            <Cell size="3" :column-header="$str('page:badges:headerId', 'local_credly')">
              {{ row.credlyId }}
            </Cell>
            <Cell size="3" :column-header="$str('page:badges:headerType', 'local_credly')">
              <span>{{getLinkType(row)}}</span>
            </Cell>
            <Cell size="3" :column-header="$str('page:badges:headerLearning', 'local_credly')">
              {{ row.linkedLearningName }}
            </Cell>
            <Cell class="link-to-learning-col" size="2" :column-header="$str('page:badges:headerLinkToLearning', 'local_credly')">
              <Dropdown
                position="bottom-right"
                class="credly-more-menu"
              >
                <template v-slot:trigger="{ toggle }">
                  <MoreButton
                    :no-padding="true"
                    :aria-label="$str('page:badges:moreMenu', 'local_credly')"
                    @click="toggle"
                  />
                </template>
                <DropdownItem
                  v-if="!isBadgeLinked(row)"
                  @click="openProgramAdder(row)"
                >
                  {{ $str('page:badges:linkProgram', 'local_credly') }}
                </DropdownItem>
                <DropdownItem
                    v-if="!isBadgeLinked(row)"
                    @click="openCertificationAdder(row)"
                >
                  {{ $str('page:badges:linkCertification', 'local_credly') }}
                </DropdownItem>
                <DropdownItem
                  v-if="!isBadgeLinked(row)"
                  @click="openCourseAdder(row)"
                >
                  {{ $str('page:badges:linkCourse', 'local_credly') }}
                </DropdownItem>
                <DropdownItem
                    v-if="isBadgeLinked(row)"
                    @click="unlinkBadge(row)"
                >
                  {{ $str('page:badges:unlinkLearning', 'local_credly') }}
                </DropdownItem>
              </Dropdown>
            </Cell>
          </template>
        </Table>
        <div v-if="hasMoreBadges" class="loadMore">
          <LoadingButton
              :text="$str('page:badges:loadmore', 'local_credly')"
              :loading="$apollo.loading"
              @click="loadMore"
          />
        </div>
      </div>
      <div v-else>
        <p>
          {{ $str('err:no_badges', 'local_credly') }}
        </p>
      </div>
    </Loader>
  </div>
</template>

<script>
import PageHeading from "tui/components/layouts/PageHeading";
import Loader from "tui/components/loading/Loader";
import CredlyBadgeQuery from 'local_credly/graphql/badges';
import SyncBadges from 'local_credly/graphql/sync_badges';
import CredlyLinkBadgeMutation from 'local_credly/graphql/link_badge';
import Table from 'tui/components/datatable/Table';
import Cell from 'tui/components/datatable/Cell';
import HeaderCell from 'tui/components/datatable/HeaderCell';
import LoadingButton from "totara_engage/components/buttons/LoadingButton";
import Dropdown from 'tui/components/dropdown/Dropdown';
import DropdownItem from 'tui/components/dropdown/DropdownItem';
import MoreButton from 'tui/components/buttons/MoreIcon';
import ProgramAdder from "../components/ProgramAdder";
import CertificationAdder from "../components/CertificationAdder";
import CourseAdder from "../components/CourseAdder";
import Button from 'tui/components/buttons/Button';
import ButtonGroup from 'tui/components/buttons/ButtonGroup';
import NotificationBanner from 'tui/components/notifications/NotificationBanner';
import LearningFilter from "../components/LearningFilter";



export default {
  components: {
    ProgramAdder,
    CertificationAdder,
    CourseAdder,
    LoadingButton,
    Loader,
    PageHeading,
    Table,
    Cell,
    HeaderCell,
    Dropdown,
    DropdownItem,
    MoreButton,
    Button,
    ButtonGroup,
    NotificationBanner,
    LearningFilter
  },

  data() {
    return {
      badges: {
        items: null,
        metadata: {
          total: 0,
          next: 1,
        },
      },
      showProgramAdder: false,
      programAdderTitle: '',
      programAdderOpenedFor: {},
      showCertificationAdder: false,
      certificationAdderTitle: '',
      certificationAdderOpenedFor: {},
      showCourseAdder: false,
      courseAdderTitle: '',
      courseAdderOpenedFor: {},
      syncStarted: false,
      filters: []
    }
  },

  computed: {
    hasMoreBadges() {
      return this.badges.metadata.next !== null;
    },
    hasBadges() {
      return this.badges.items !== null;
    },
  },

  methods: {
    sync: async function() {
      this.syncStarted = true;
      await this.$apollo.mutate({
        mutation: SyncBadges
      });
      window.location.reload();
    },

    updateFilter(selection) {
      this.filters = [
        {name: 'type', value: selection.learningtype},
        {name: 'search', value: selection.search},
      ];
      this.$apollo.queries.badges.setVariables({page: 1, filters: this.filters});
      this.$apollo.queries.badges.refresh();
    },

    clearFilter() {
      this.filters = [];
    },

    getBadges() {
      return this.badges.items;
    },

    loadMore() {
      this.$apollo.queries.badges.fetchMore({
        variables: {
          page: this.badges.metadata.next,
          filters: this.filters,
        },
        updateQuery: (previousResult, {fetchMoreResult}) => {
          fetchMoreResult.badges.items.unshift(
              ...this.badges.items
          );

          return fetchMoreResult;
        },
      });
    },

    openProgramAdder(badge) {
      this.programAdderTitle = this.$str('page:badges:programAdderTitle', 'local_credly', badge.name);
      this.programAdderOpenedFor = badge;
      this.showProgramAdder = true;
    },
    openCertificationAdder(badge) {
      this.certificationAdderTitle = this.$str('page:badges:certificationAdderTitle', 'local_credly', badge.name);
      this.certificationAdderOpenedFor = badge;
      this.showCertificationAdder = true;
    },
    openCourseAdder(badge) {
      this.courseAdderTitle = this.$str('page:badges:courseAdderTitle', 'local_credly', badge.name);
      this.courseAdderOpenedFor = badge;
      this.showCourseAdder = true;
    },
    isBadgeLinked(badge) {
      return badge.programId || badge.certificationId || badge.courseId;
    },
    getLinkType (badge){
      if (!this.isBadgeLinked(badge)) {
        return this.$str('linktype:not_linked', 'local_credly');
      }
      if (badge.programId) {
        return this.$str('program', 'totara_program');
      }
      if (badge.certificationId) {
        return this.$str('certification', 'totara_program');
      }
      if (badge.courseId) {
        return this.$str('course', 'core');
      }
    },
    hideProgramAdder() {
      this.showProgramAdder = false;
      this.programAdderOpenedFor = {};
    },
    hideCertificationAdder() {
      this.showCertificationAdder = false;
      this.certificationAdderOpenedFor = {};
    },
    hideCourseAdder() {
      this.showCourseAdder = false;
      this.courseAdderOpenedFor = {};
    },

    programAdderDataUpdated: async function ({ids, data}) {
      let programId = ids[0];
      const resultData = await this.$apollo.mutate({
        mutation: CredlyLinkBadgeMutation,
        variables: {
          link: {
            credlyId: this.programAdderOpenedFor.credlyId,
            learningId: programId,
            learningType: 'program',
          }
        },
      });

      const idx = this.badges.items.findIndex(item => item.credlyId === this.programAdderOpenedFor.credlyId);
      const page = Math.ceil((idx + 1) / 50);
      await this.refetchBadges(page);
      this.hideProgramAdder();
    },
    certificationAdderDataUpdated: async function ({ids, data}) {
      let certificationId = ids[0];
      const resultData = await this.$apollo.mutate({
        mutation: CredlyLinkBadgeMutation,
        variables: {
          link: {
            credlyId: this.certificationAdderOpenedFor.credlyId,
            learningId: certificationId,
            learningType: 'certification',
          }
        },
      });

      const idx = this.badges.items.findIndex(item => item.credlyId === this.certificationAdderOpenedFor.credlyId);
      const page = Math.ceil((idx + 1) / 50);
      await this.refetchBadges(page);
      this.hideCertificationAdder();
    },
    courseAdderDataUpdated: async function ({ids, data}) {
      let courseId = ids[0];
      const resultData = await this.$apollo.mutate({
        mutation: CredlyLinkBadgeMutation,
        variables: {
          link: {
            credlyId: this.courseAdderOpenedFor.credlyId,
            learningId: courseId,
            learningType: 'course',
          }
        },
      });

      const idx = this.badges.items.findIndex(item => item.credlyId === this.courseAdderOpenedFor.credlyId);
      const page = Math.ceil((idx + 1) / 50);
      await this.refetchBadges(page);
      this.hideCourseAdder();
    },
    unlinkBadge: async function (badge) {
      const resultData = await this.$apollo.mutate({
        mutation: CredlyLinkBadgeMutation,
        variables: {
          link: {
            credlyId: badge.credlyId,
            learningId: null,
            learningType: 'unlinked',
          }
        },
      });

      const idx = this.badges.items.findIndex(item => item.credlyId === badge.credlyId);
      const page = Math.ceil((idx + 1) / 50);
      await this.refetchBadges(page);
    },
    // Reload the given page of badges and slice them back into the badge array neatly
    refetchBadges(page) {
      const currentMetadata = this.badges.metadata;
      this.$apollo.queries.badges.fetchMore({
        variables: {
          page: page,
          filters: this.filters,
        },
        updateQuery: (previousResult, {fetchMoreResult}) => {
          fetchMoreResult.badges.items = [
            ...this.badges.items.slice(0, (page - 1) * 50),
            ...fetchMoreResult.badges.items,
            ...this.badges.items.slice(page * 50),
          ];

          fetchMoreResult.metadata = currentMetadata;

          return fetchMoreResult;
        }
      });
    }

  },

  apollo: {
    badges: {
      query: CredlyBadgeQuery,
      fetchPolicy: 'network-only',
      variables() {
        return {
          page: 1,
          filters: this.filters
        };
      },
      update: (data) => {
        return data.badges;
      },
    },
  }
}
</script>

<lang-strings>
{
"local_credly": [
"page:badges:heading",
"page:badges:headerName",
"page:badges:headerId",
"page:badges:headerType",
"page:badges:headerLearning",
"page:badges:headerLinkToLearning",
"page:badges:moreMenu",
"page:badges:loadmore",
"page:badges:linkProgram",
"page:badges:linkCertification",
"page:badges:unlinkLearning",
"page:badges:linkCourse",
"page:badges:programAdderTitle",
"page:badges:certificationAdderTitle",
"page:badges:courseAdderTitle",
"page:badges:showing_x_of_y_badges",
"page:badges:sync",
"page:badges:synchronising",
"linktype:not_linked",
"err:no_badges"
],
"totara_program": [
"program",
"certification"
],
"core": [
"course"
]
}
</lang-strings>

<style scoped lang="scss">
.content {
  margin-top: var(--gap-5);
}

.loadMore {
  button {
    display: block;
    margin: var(--gap-5) auto;
  }
}
.link-to-learning-header {
  text-align: right;
  position: relative;
  right: 4rem;
}
.credly-more-menu {
  float: right;
  position: relative;
  right: 4rem;
}
.credly-badges {
  .tui-dataTableRow {
    margin-left: calc(0px - var(--border-width-thin));
    border: var(--border-width-thin) solid var(--datatable-expanded-border-color);
    border-bottom: none;
    box-shadow: var(--shadow-2);
    background-color: var(--datatable-expanded-bg-color);
  }
}
</style>