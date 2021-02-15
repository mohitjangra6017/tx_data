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
  @module samples
-->

<template>
  <div class="tui-samples__ProgressTracker">
    <p>
      A data-driven progress track component for showing step-based progress,
      for example achieving a goal or steps in a workflow.
    </p>

    <SamplesExample>
      <div
        class="tui-samples__ProgressTracker__wrapper"
        :class="
          forceVertical
            ? 'tui-samples__ProgressTracker__wrapper--vertical'
            : 'tui-samples__ProgressTracker__wrapper--horizontal'
        "
      >
        <div class="tui-samples__ProgressTracker__content">
          <Range
            v-model="workflowItemsToShow"
            :value="workflowItemsToShow"
            :min="1"
            :max="itemsWorkflow.length"
          />
          <br />
          <ProgressTrackerNav
            :key="getUniqueId()"
            :items="itemsWorkflow.slice(0, workflowItemsToShow)"
            :size="size"
            :gap="gap"
            :force-vertical="forceVertical"
            marker-mode="workflow"
          >
            <template v-slot="{ entry, index }">
              <!--
                ProgressTrackerNav doesn't know about what is going into its
                ProgressTrackerNavItems, but will iterate over entries and drop in
                the supplied content to a new ProgressTrackerNavItem for each entry.
              -->
              Custom progressItem components for
              {{ entry.description }}, whose states are:<br />{{ entry.states }}
            </template>
          </ProgressTrackerNav>
        </div>
        <div class="tui-samples__ProgressTracker__content">
          <Range
            v-model="achievementItemsToShow"
            :value="achievementItemsToShow"
            :min="1"
            :max="itemsAchievement.length"
          />
          <br />

          <ProgressTrackerNav
            :key="getUniqueId()"
            :items="itemsAchievement.slice(0, achievementItemsToShow)"
            :size="size"
            :gap="gap"
            :force-vertical="forceVertical"
            :label-opens-popover="labelOpensPopover"
            :popover-trigger-type="popoverTriggerType"
          >
            <template v-slot="{ entry }">
              States are:<br />{{ entry.states }}
            </template>
          </ProgressTrackerNav>
        </div>
      </div>
    </SamplesExample>

    <SamplesPropCtl>
      <FormRow label="gap">
        <RadioGroup v-model="gap" :horizontal="true">
          <Radio value="small">Small</Radio>
          <Radio value="medium">Medium</Radio>
          <Radio value="large">Large</Radio>
        </RadioGroup>
      </FormRow>

      <FormRow label="size">
        <RadioGroup v-model="size" :disabled="forceVertical" :horizontal="true">
          <Radio value="small">Small</Radio>
          <Radio value="medium">Medium</Radio>
          <Radio value="large">Large</Radio>
        </RadioGroup>
      </FormRow>

      <FormRow label="forceVertical">
        <RadioGroup v-model="forceVertical" :horizontal="true">
          <Radio :value="true">True</Radio>
          <Radio :value="false">False</Radio>
        </RadioGroup>
      </FormRow>

      <FormRow label="labelOpensPopover">
        <RadioGroup v-model="labelOpensPopover" :horizontal="true">
          <Radio :value="true">True</Radio>
          <Radio :value="false">False</Radio>
        </RadioGroup>
      </FormRow>

      <FormRow label="popoverTriggerType">
        <CheckboxGroup v-model="popoverTriggerType">
          <Checkbox value="click">Click</Checkbox>
          <Checkbox value="hover">Hover</Checkbox>
        </CheckboxGroup>
      </FormRow>

      <FormRow label="markerMode">
        <p>
          <em
            >Supported but each mode has validators on allowed progressItem
            states, refer to implementation.</em
          >
        </p>
      </FormRow>
    </SamplesPropCtl>
  </div>
</template>

<script>
import ProgressTrackerNav from 'tui/components/progresstracker/ProgressTrackerNav';
import FormRow from 'tui/components/form/FormRow';
import Radio from 'tui/components/form/Radio';
import RadioGroup from 'tui/components/form/RadioGroup';
import Checkbox from 'tui/components/form/Checkbox';
import CheckboxGroup from 'tui/components/form/CheckboxGroup';
import Range from 'tui/components/form/Range';
import SamplesExample from 'samples/components/sample_parts/misc/SamplesExample';
import SamplesPropCtl from 'samples/components/sample_parts/misc/SamplesPropCtl';

// hack to ensure popover re-renders if the v-model data for `popoverTriggerType` changes
import { uniqueId } from 'tui/util';

export default {
  components: {
    ProgressTrackerNav,
    FormRow,
    Radio,
    RadioGroup,
    Checkbox,
    CheckboxGroup,
    Range,
    SamplesExample,
    SamplesPropCtl,
  },

  data() {
    return {
      gap: 'medium',
      size: 'medium',
      forceVertical: true,
      labelOpensPopover: true,
      popoverTriggerType: ['click'],
      workflowItemsToShow: 5,
      itemsWorkflow: [
        {
          id: 1,
          description: 'Workflow Step 1',
          states: ['ready'],
        },
        {
          id: 2,
          description: 'Workflow Step 2',
          states: ['selected'],
        },
        {
          id: 3,
          description: 'Workflow Step 3',
          states: ['optional'],
        },
        {
          id: 4,
          description: 'Workflow Step 4',
          states: ['optional', 'selected'],
        },
        {
          id: 5,
          description: 'Workflow Step 5',
          states: ['locked'],
        },
        {
          id: 6,
          description: 'Workflow Step 6',
          states: ['locked', 'optional'],
        },
        {
          id: 7,
          description: 'Workflow Step 7',
          states: ['locked', 'selected'],
        },
        {
          id: 8,
          description: 'Workflow Step 8',
          states: ['locked', 'optional', 'selected'],
        },
        {
          id: 9,
          description: 'Workflow Step 9',
          states: ['done'],
        },
        {
          id: 10,
          description: 'Workflow Step 10',
          states: ['done', 'selected'],
        },
        {
          id: 11,
          description: 'Workflow Step turned up to 11',
          states: ['ready', 'done', 'optional', 'selected', 'locked'],
        },
      ],
      achievementItemsToShow: 5,
      itemsAchievement: [
        {
          id: 1,
          description: 'Achievement Step 1',
          label: 'Achievement Step 1',
          states: ['pending'],
        },
        {
          id: 2,
          description: 'Achievement Step 2',
          label: 'Achievement Step 2',
          states: ['pending', 'target'],
        },
        {
          id: 3,
          description: 'Achievement Step 3',
          label: 'Achievement Step 3',
          states: ['complete'],
        },
        {
          id: 4,
          description: 'Achievement Step 3',
          label: 'Achievement Step 3',
          states: ['complete', 'current'],
        },
        {
          id: 5,
          description: 'Achievement Step 4',
          label: 'Achievement Step 4',
          states: ['achieved'],
        },
        {
          id: 6,
          description: 'Achievement Step 4',
          label: 'Achievement Step 4',
          states: ['achieved', 'current'],
        },
        {
          id: 7,
          description: 'Achievement Step 4',
          label: 'Achievement Step 4',
          states: ['achieved', 'target'],
        },
        {
          id: 8,
          description: 'Achievement Step 4',
          label: 'Achievement Step 4',
          states: ['achieved', 'target', 'current'],
        },
      ],
    };
  },
  methods: {
    getUniqueId() {
      return uniqueId();
    },
  },
};
</script>
<style lang="scss">
.tui-samples__ProgressTracker__wrapper {
  display: flex;
  gap: 50px;

  &--vertical {
    flex-direction: row;

    .tui-samples__ProgressTracker__content {
      width: calc(50% - 50px);
    }
  }
  &--horizontal {
    flex-direction: column;
  }
}
</style>
