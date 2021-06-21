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
  <div
    class="tui-linkedInLearningItem"
    :class="{
      'tui-linkedInLearningItem--small': small,
      'tui-linkedInLearningItem--unselected': unselected,
    }"
  >
    <template v-if="loading">
      <div class="tui-linkedInLearningItem__img">
        <SkeletonContent :has-overlay="true" />
      </div>

      <div class="tui-linkedInLearningItem__content">
        <!-- Course subject -->
        <div class="tui-linkedInLearningItem__subject">
          <SkeletonContent char-length="10" :has-overlay="true" />
        </div>
        <!-- Course title -->
        <h3 class="tui-linkedInLearningItem__title">
          <SkeletonContent char-length="25" :has-overlay="true" />
        </h3>

        <div class="tui-linkedInLearningItem__bar">
          <SkeletonContent char-length="25" :has-overlay="true" />
        </div>
      </div>
    </template>

    <template v-else>
      <!-- Course image -->
      <div
        class="tui-linkedInLearningItem__img"
        :style="{ 'background-image': cardImage }"
      />

      <div class="tui-linkedInLearningItem__content">
        <!-- Course subject -->
        <div class="tui-linkedInLearningItem__subject">
          {{ data.subject }} Coming soon
        </div>
        <!-- Course title -->
        <h3 class="tui-linkedInLearningItem__title">
          {{ data.name }}
        </h3>
        <div class="tui-linkedInLearningItem__bar">
          <div class="tui-linkedInLearningItem__bar-overview">
            <!-- Course level (Beginner, intermediate, advanced) -->
            <div v-if="courseLevelString">
              <span class="sr-only">
                {{
                  $str('a11y_content_difficulty', 'contentmarketplace_linkedin')
                }}
              </span>
              {{ courseLevelString }}
            </div>
            <!-- Course completion time -->
            <div v-if="data.time_to_complete">
              <span class="sr-only">
                {{
                  $str(
                    'a11y_content_time_to_complete',
                    'contentmarketplace_linkedin'
                  )
                }}
              </span>
              {{ data.time_to_complete }}
            </div>
            <!-- Course type (course, learning path) -->
            <div v-if="courseTypeString">
              <span class="sr-only">
                {{ $str('a11y_content_type', 'contentmarketplace_linkedin') }}
              </span>
              {{ courseTypeString }}
            </div>

            <!-- Course appearances in other courses -->
            <div
              v-if="data.courses.length"
              class="tui-linkedInLearningItem__bar-courses"
            >
              <span :aria-hidden="true">
                {{ $str('appears_in', 'contentmarketplace_linkedin') }}
              </span>
              <span class="sr-only">
                {{
                  $str(
                    'a11y_appears_in_n_courses',
                    'contentmarketplace_linkedin',
                    data.courses.length
                  )
                }}
              </span>

              <!-- Popover to display list of other courses this appears in -->
              <Popover size="md" :triggers="['click']">
                <template v-slot:trigger="{ isOpen }">
                  <Button
                    :aria-expanded="isOpen.toString()"
                    :aria-label="
                      $str('a11y_view_courses', 'contentmarketplace_linkedin', {
                        count: data.courses.length,
                        course: data.name,
                      })
                    "
                    :styleclass="{ small: true, transparent: true }"
                    :text="courseNumberString"
                  />
                </template>
                <div>
                  {{
                    $str('content_appears_in', 'contentmarketplace_linkedin')
                  }}
                  <ul class="tui-linkedInLearningItem__bar-coursesList">
                    <li v-for="(course, i) in data.courses" :key="i">
                      {{ course.fullname }}
                    </li>
                  </ul>
                </div>
              </Popover>
            </div>
          </div>
          <!-- Side content -->
          <div
            v-if="$scopedSlots['side-content']"
            class="tui-linkedInLearningItem__bar-side"
          >
            <slot name="side-content" />
          </div>
        </div>
      </div>
    </template>
  </div>
</template>

<script>
// Components
import Button from 'tui/components/buttons/Button';
import Popover from 'tui/components/popover/Popover';
import SkeletonContent from 'totara_contentmarketplace/components/loader/SkeletonContent';

export default {
  components: {
    Button,
    Popover,
    SkeletonContent,
  },

  props: {
    // Contains all course data
    data: {
      type: Object,
      required: true,
    },
    // Current loading state of page
    loading: Boolean,
    // Used for displaying content in smaller format
    small: Boolean,
    // Unselected item (used of fading on review list)
    unselected: Boolean,
  },

  computed: {
    /**
     * Construct a background image URL
     *
     * @return {String}
     */
    cardImage() {
      let url = this.data.image_url;
      if (url === null) {
        return;
      }
      return 'url("' + url + '")';
    },

    /**
     * Provide string for course(s) button text
     *
     * @return {String}
     */
    courseNumberString() {
      let courseLength = this.data.courses.length;

      if (!courseLength) {
        return '';
      }

      return courseLength === 1
        ? this.$str(
            'course_number',
            'contentmarketplace_linkedin',
            courseLength
          )
        : this.$str(
            'course_number_plural',
            'contentmarketplace_linkedin',
            courseLength
          );
    },

    /**
     * Return correct language string for course level
     *
     * @return {String}
     */
    courseLevelString() {
      const key = this.data.level;
      if (!key) {
        return '';
      }

      let level =
        key === 'BEGINNER'
          ? this.$str(
              'course_difficulty_beginner',
              'contentmarketplace_linkedin'
            )
          : key === 'INTERMEDIATE'
          ? this.$str(
              'course_difficulty_intermediate',
              'contentmarketplace_linkedin'
            )
          : key === 'ADVANCED'
          ? this.$str(
              'course_difficulty_advanced',
              'contentmarketplace_linkedin'
            )
          : '';

      return level;
    },

    /**
     * Return correct language string for content type
     *
     * @return {String}
     */
    courseTypeString() {
      const key = this.data.asset_type;
      if (!key) {
        return '';
      }

      let type =
        key === 'COURSE'
          ? this.$str('course_type_course', 'contentmarketplace_linkedin')
          : key === 'LEARNING_PATH'
          ? this.$str(
              'course_type_learning_path',
              'contentmarketplace_linkedin'
            )
          : key === 'VIDEO'
          ? this.$str('course_type_video', 'contentmarketplace_linkedin')
          : '';

      return type;
    },
  },
};
</script>

<lang-strings>
  {
    "contentmarketplace_linkedin": [
      "a11y_appears_in_n_courses",
      "a11y_content_difficulty",
      "a11y_content_time_to_complete",
      "a11y_content_type",
      "a11y_view_courses",
      "appears_in",
      "content_appears_in",
      "course_difficulty_advanced",
      "course_difficulty_beginner",
      "course_difficulty_intermediate",
      "course_type_course",
      "course_type_learning_path",
      "course_type_video",
      "course_number",
      "course_number_plural"
    ]
  }
</lang-strings>

<style lang="scss">
.tui-linkedInLearningItem {
  display: flex;
  flex-direction: column;
  margin-top: var(--gap-2);

  & > * + * {
    margin-top: var(--gap-2);
  }

  &__img {
    height: 120px;
    background: var(--color-neutral-3);
    background-position: center;
    background-size: cover;

    .tui-linkedInLearningItem--unselected & {
      opacity: 0.3;
    }
  }

  &__content {
    @include tui-font-body-small();

    & > * + * {
      margin-top: var(--gap-4);
    }
  }

  &__title {
    @include tui-font-heading-small();
    margin: var(--gap-1) 0 0;

    .tui-linkedInLearningItem--unselected & {
      color: var(--color-neutral-6);
    }
  }

  &__subject {
    color: var(--color-text-hint);
  }

  &__bar {
    display: flex;
    flex-direction: column;
    flex-wrap: wrap;
    min-height: var(--font-size-16);
    color: var(--color-text-hint);
    hyphens: none;

    & > * + * {
      margin-top: var(--gap-3);
    }

    &-side {
      display: flex;
      margin-left: auto;
    }

    &-courses {
      display: flex;

      & > * + * {
        margin-left: var(--gap-1);
      }
    }

    &-coursesList {
      max-height: 160px;
      margin: var(--gap-1) 0 0;
      overflow: auto;
      list-style: none;
    }

    &-overview {
      display: flex;
      flex-wrap: wrap;

      & > * + * {
        position: relative;
        margin-left: var(--gap-2);
        padding-left: calc(var(--gap-2) + 1px);

        &:before {
          position: absolute;
          top: 2px;
          left: 0;
          height: 1em;
          border-left: var(--border-width-thin) solid;
          content: '';
        }
      }
    }
  }
}

@media (min-width: $tui-screen-xs) {
  .tui-linkedInLearningItem {
    flex-direction: row;
    margin-top: 0;

    & > * + * {
      margin-top: 0;
    }

    &__img {
      flex-shrink: 0;
      width: 180px;
      height: 99px;

      .tui-linkedInLearningItem--small & {
        width: 106px;
        height: 60px;
      }
    }

    &__content {
      flex-grow: 1;
      padding-left: var(--gap-4);

      .tui-linkedInLearningItem--small & {
        & > * + * {
          margin-top: var(--gap-2);
        }
      }
    }

    &__title {
      .tui-linkedInLearningItem--small & {
        @include tui-font-heading-x-small();
        margin-top: var(--gap-1);
      }
    }
  }
}

@media (min-width: $tui-screen-md) {
  .tui-linkedInLearningItem {
    &__bar {
      flex-direction: row;

      & > * + * {
        margin-top: 0;
      }
    }
  }
}
</style>
