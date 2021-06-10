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

  @author Dave Wallace <dave.wallace@totaralearning.com>
  @author Kevin Hottinger <kevin.hottinger@totaralearning.com>
  @module tui
-->

<template>
  <div
    ref="interactable"
    class="tui-progressTrackerNavCircleWorkflow tui-progressTrackerNavCircleWorkflow__outer"
    :class="{
      'tui-progressTrackerNavCircleWorkflow--ready': ready,
      'tui-progressTrackerNavCircleWorkflow--done': done,
      'tui-progressTrackerNavCircleWorkflow--selected': selected,
      'tui-progressTrackerNavCircleWorkflow--locked': locked,
      'tui-progressTrackerNavCircleWorkflow--optional': optional,
    }"
    :aria-hidden="!isInteractable"
    :role="ariaRole"
    :tabindex="isInteractable ? '0' : '-1'"
    @keypress="handleKeypress($event)"
  >
    <div class="tui-progressTrackerNavCircleWorkflow__middle">
      <div class="tui-progressTrackerNavCircleWorkflow__inner">
        <span
          v-if="isInteractable"
          class="tui-progressTrackerNavCircleWorkflow__label"
        >
          {{ $str('a11y_progresstracker_action', 'totara_tui') }}
        </span>
        <LockIcon
          v-if="locked"
          :alt="$str('completionstatus_locked', 'totara_tui')"
          :size="100"
          class="tui-progressTrackerNavCircleWorkflow__icon--locked"
        />
        <SuccessIcon
          v-else-if="done"
          :alt="$str('completionstatus_done', 'totara_tui')"
          :size="100"
          class="tui-progressTrackerNavCircleWorkflow__icon--done"
        />
      </div>
    </div>
  </div>
</template>

<script>
import LockIcon from 'tui/components/icons/Lock';
import SuccessIcon from 'tui/components/icons/Success';
export default {
  components: {
    LockIcon,
    SuccessIcon,
  },
  props: {
    ariaRole: {
      default: 'button',
      type: String,
      validator: function(value) {
        const allowedOptions = ['button', 'link', ''];
        return allowedOptions.includes(value);
      },
    },
    isInteractable: {
      default: true,
      type: Boolean,
    },
    states: {
      default: () => ['ready'],
      type: Array,
      validator: function(values) {
        const allowedOptions = [
          'ready',
          'done',
          'selected',
          'locked',
          'optional',
        ];
        // warn on invalid state found within the supplied Array
        return !values.filter(value => {
          return allowedOptions.indexOf(value) === -1;
        }).length;
      },
    },
  },
  data() {
    return {
      open: false,
    };
  },
  computed: {
    ready() {
      return this.states.includes('ready');
    },
    done() {
      return this.states.includes('done');
    },
    selected() {
      return this.states.includes('selected');
    },
    locked() {
      return this.states.includes('locked');
    },
    optional() {
      return this.states.includes('optional');
    },
  },

  methods: {
    handleKeypress(e) {
      if (
        this.isInteractable &&
        ['enter', 'spacebar', ' '].includes(e.key.toLowerCase())
      ) {
        this.$refs.interactable.click();
      }
      return e.preventDefault();
    },
  },
};
</script>

<style lang="scss">
.tui-progressTrackerNavCircleWorkflow {
  // states
  $ready: #{&}--ready;
  $locked: #{&}--locked;
  $optional: #{&}--optional;
  $selected: #{&}--selected;
  $done: #{&}--done;

  &__outer {
    z-index: 2;
    display: flex;
    flex-shrink: 0;
    align-items: center;
    justify-content: center;
    width: var(--progresstracker-full-marker-size);
    height: var(--progresstracker-full-marker-size);
    border: var(--border-width-normal) transparent solid;
    border-radius: 50%;

    /**
     * states
     **/
    &#{$locked}#{$selected} {
      border-color: var(--progresstracker-color-locked);
    }

    &#{$selected} {
      border-color: var(--progresstracker-color-selected);
      border-style: solid;
    }
  }

  &__middle {
    display: flex;
    align-items: center;
    justify-content: center;
    width: calc(
      calc(var(--progresstracker-full-marker-size) / 2) + var(--gap-2)
    );
    height: calc(
      calc(var(--progresstracker-full-marker-size) / 2) + var(--gap-2)
    );
    background-color: transparent;
    border: var(--border-width-thin) solid transparent;
    border-radius: 50%;

    /**
     * states
     **/
    #{$ready} & {
      border-color: var(--progresstracker-color-ready);
    }

    #{$locked} & {
      border-color: var(--progresstracker-color-locked);
    }

    #{$locked}#{$optional} & {
      border-color: var(--progresstracker-color-locked);
    }

    #{$locked}#{$selected} & {
      background: var(--progresstracker-color-locked);
      border-color: var(--progresstracker-color-locked);
    }

    #{$done} & {
      background: var(--progresstracker-color-done);
      border-color: var(--progresstracker-color-done);
    }

    #{$optional} & {
      border-color: var(--progresstracker-color-optional);
      border-style: dashed;
    }

    #{$selected} & {
      background: var(--progresstracker-color-selected);
      border-color: var(--progresstracker-color-selected);
    }

    #{$selected}#{$done} & {
      border-color: var(--progresstracker-color-done);
    }
  }

  &__inner {
    display: flex;
    align-items: center;
    justify-content: center;
    width: var(--gap-5);
    height: var(--gap-5);
    border-radius: 50%;

    /**
     * states
     **/
    #{$locked} & {
      color: var(--progresstracker-color-locked);
    }

    #{$selected} & {
      background-color: var(--progresstracker-color-selected);
    }

    #{$locked}#{$selected} & {
      color: var(--progresstracker-color-locked--inverse);
      background-color: var(--progresstracker-color-locked);
    }

    #{$done} & {
      color: var(--progresstracker-color-done--inverse);
      background-color: var(--progresstracker-color-done);
    }
  }

  &__icon--locked {
    width: 1.2rem;
    height: 1.2rem;
  }
  &__icon--done {
    width: 1.6rem;
    height: 1.6rem;
  }

  &__label {
    @include sr-only();
  }
}
</style>

<lang-strings>
{
  "totara_tui": [
    "completionstatus_done",
    "completionstatus_locked",
    "a11y_progresstracker_action"
  ]
}
</lang-strings>
