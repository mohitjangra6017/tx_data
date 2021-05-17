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
    class="tui-linkedInTableCard"
    :class="{
      'tui-linkedInTableCard--small': small,
      'tui-linkedInTableCard--unselected': unselected,
    }"
  >
    <!-- Course image -->
    <div
      class="tui-linkedInTableCard__img"
      :style="{ 'background-image': cardImage }"
    />

    <div class="tui-linkedInTableCard__content">
      <!-- Course subject -->
      <div class="tui-linkedInTableCard__subject">
        {{ data.subject }}
      </div>
      <!-- Course title -->
      <h3 class="tui-linkedInTableCard__title">
        {{ data.name }}
      </h3>
      <div class="tui-linkedInTableCard__bar">
        <div class="tui-linkedInTableCard__bar-overview">
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
          <div v-if="data.time">
            <span class="sr-only">
              {{
                $str(
                  'a11y_content_completion_time',
                  'contentmarketplace_linkedin'
                )
              }}
            </span>
            {{ data.time }}
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
            class="tui-linkedInTableCard__bar-courses"
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
                {{ $str('content_appears_in', 'contentmarketplace_linkedin') }}
                <ul class="tui-linkedInTableCard__bar-coursesList">
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
          class="tui-linkedInTableCard__bar-side"
        >
          <slot name="side-content" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// Components
import Button from 'tui/components/buttons/Button';
import Popover from 'tui/components/popover/Popover';

export default {
  components: {
    Button,
    Popover,
  },

  props: {
    // Contains all course data
    data: {
      type: Object,
      required: true,
    },
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
      return 'url(' + url + ')';
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
     * Return correct language string for course type
     *
     * @return {String}
     */
    courseTypeString() {
      const key = this.data.type;
      if (!key) {
        return '';
      }

      let type =
        key === 'course'
          ? this.$str('course_type_course', 'contentmarketplace_linkedin')
          : key === 'learningPath'
          ? this.$str(
              'course_type_learning_path',
              'contentmarketplace_linkedin'
            )
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
      "a11y_content_completion_time",
      "a11y_content_difficulty",
      "a11y_content_type",
      "a11y_view_courses",
      "appears_in",
      "content_appears_in",
      "course_difficulty_advanced",
      "course_difficulty_beginner",
      "course_difficulty_intermediate",
      "course_type_course",
      "course_type_learning_path",
      "course_number",
      "course_number_plural"
    ]
  }
</lang-strings>

<style lang="scss">
.tui-linkedInTableCard {
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

    .tui-linkedInTableCard--unselected & {
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

    .tui-linkedInTableCard--unselected & {
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
  .tui-linkedInTableCard {
    flex-direction: row;
    margin-top: 0;

    & > * + * {
      margin-top: 0;
    }

    &__img {
      flex-shrink: 0;
      width: 180px;
      height: 99px;

      .tui-linkedInTableCard--small & {
        width: 106px;
        height: 60px;
      }
    }

    &__content {
      flex-grow: 1;
      padding-left: var(--gap-4);

      .tui-linkedInTableCard--small & {
        & > * + * {
          margin-top: var(--gap-2);
        }
      }
    }

    &__title {
      .tui-linkedInTableCard--small & {
        @include tui-font-heading-x-small();
        margin-top: var(--gap-1);
      }
    }
  }
}

@media (min-width: $tui-screen-md) {
  .tui-linkedInTableCard {
    &__bar {
      flex-direction: row;

      & > * + * {
        margin-top: 0;
      }
    }
  }
}
</style>
