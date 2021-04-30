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
  @module tui
-->

<template>
  <div
    class="tui-treeBranch"
    :class="{
      'tui-treeBranch--top': topLevel,
      'tui-treeBranch--separator': separator && topLevel,
    }"
  >
    <div
      class="tui-treeBranch__trigger"
      :class="{ 'tui-treeBranch__trigger--top': topLevel }"
    >
      <!-- Branch expand trigger -->
      <ButtonIcon
        ref="trigger"
        class="tui-treeBranch__trigger-btn"
        :styleclass="{ transparent: true }"
        :aria-expanded="open.toString()"
        :aria-controls="regionId"
        :aria-describedby="regionDescriptionId"
        :aria-label="label"
        data-tree-trigger="true"
        @click="toggleExpand()"
      >
        <CollapseIcon v-if="open" />
        <ExpandIcon v-else />
      </ButtonIcon>
    </div>
    <div class="tui-treeBranch__content">
      <!-- Branch bar -->
      <div class="tui-treeBranch__bar">
        <!-- Branch label (Can be text, button or link) -->

        <template v-if="$scopedSlots['custom-label']">
          <slot name="custom-label" :label="label" />
        </template>

        <template v-else>
          <Button
            v-if="labelType === 'button'"
            :id="branchLabelId"
            class="tui-treeBranch__bar-btn"
            :styleclass="{ primary: true, transparent: true }"
            :text="label"
            @click="$emit('label-click', branchId)"
          />

          <a
            v-else-if="labelType === 'link' && linkUrl"
            :id="branchLabelId"
            class="tui-treeBranch__bar-link"
            :href="linkUrl"
          >
            {{ label }}
          </a>

          <h3 v-else :id="branchLabelId" class="tui-treeBranch__bar-label">
            {{ label }}
          </h3>
        </template>

        <div class="tui-treeBranch__bar-side">
          <slot v-if="sideContent" name="side" :sideContent="sideContent" />
        </div>
      </div>

      <span :id="regionDescriptionId" class="sr-only">
        {{ regionAccessibleLabel }}
      </span>
      <div v-show="open" :id="regionId" role="region" :aria-label="label">
        <!-- Sub-branches -->
        <template v-if="depth !== depthLimit">
          <div
            v-for="(child, index) in children"
            :key="child.id"
            class="tui-treeBranch__child"
          >
            <TreeBranch
              :branch-id="child.id"
              :children="child.children"
              :content="child.content"
              :depth="depth + 1"
              :depth-limit="depthLimit"
              :label="child.label"
              :label-type="labelType"
              :link-url="child.linkUrl"
              :open-list="openList"
              :position="index + 1"
              :siblings="children.length"
              :side-content="child.sideContent"
              @expanded="$emit('expanded', $event)"
              @label-click="$emit('label-click', $event)"
            >
              <template v-slot:content="{ content }">
                <slot name="content" :content="content" />
              </template>

              <template
                v-if="$scopedSlots['custom-label']"
                v-slot:custom-label="{ label }"
              >
                <slot name="custom-label" :label="label" />
              </template>

              <template v-slot:side="{ sideContent }">
                <slot name="side" :sideContent="sideContent" />
              </template>
            </TreeBranch>
          </div>
        </template>

        <!-- Branch leaves -->
        <div class="tui-treeBranch__leaf" :aria-labelledby="branchLabelId">
          <slot name="content" :content="getOutputContent()" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Button from 'tui/components/buttons/Button';
import ButtonIcon from 'tui/components/buttons/ButtonIcon';
import CollapseIcon from 'tui/components/icons/Collapse';
import ExpandIcon from 'tui/components/icons/Expand';

export default {
  name: 'TreeBranch',

  components: {
    Button,
    ButtonIcon,
    CollapseIcon,
    ExpandIcon,
  },

  props: {
    branchId: String,
    children: Array,
    content: Object,
    depth: Number,
    depthLimit: Number,
    label: String,
    labelType: String,
    linkUrl: String,
    openList: Array,
    position: Number,
    separator: Boolean,
    siblings: Number,
    sideContent: Object,
    topLevel: Boolean,
  },

  data() {
    return {
      open: false,
    };
  },

  computed: {
    /**
     * Provide label ID for accessibility tags
     *
     * @return {String}
     */
    branchLabelId() {
      return this.$id('label');
    },

    /**
     * Provide region description ID for accessibility tags
     *
     * @return {String}
     */
    regionDescriptionId() {
      return this.$id('regionDesc');
    },

    /**
     * Provide region ID for accessibility tags
     *
     * @return {String}
     */
    regionId() {
      return this.$id('region');
    },

    /**
     * Provide accessibility label for region
     *
     * @return {String}
     */
    regionAccessibleLabel() {
      return this.$str('a11y_tree_region_summary', 'totara_core', {
        depth: this.depth,
        label: this.label,
        position: this.position,
        siblings: this.siblings,
      });
    },
  },

  watch: {
    /**
     * Check if this branch should be expanded
     *
     */
    openList(list) {
      this.setOpenState(list);
    },
  },

  mounted() {
    this.setOpenState(this.openList);
  },

  methods: {
    /**
     * set the open (expanded) state of the branch
     *
     * @param {Array} list
     */
    setOpenState(list) {
      this.open = list.includes(this.branchId);
    },

    /**
     * Propagate expanded value change to parent
     *
     */
    toggleExpand() {
      this.$emit('expanded', {
        key: this.branchId,
        expanded: !this.open,
      });
    },

    /**
     * Get content for the slot
     *
     * @return {Object}
     */
    getOutputContent() {
      let content = this.content;

      content.subContent = [];
      // Get content data from removed children
      if (this.depth === this.depthLimit && this.children.length) {
        this.children.forEach((subBranch, index) => {
          content.subContent[index] = this.getSubContent(subBranch);
        });
      }

      return content;
    },

    /**
     * Get the content from branches removed by the depth limit
     *
     * @param {Object} branch
     * @return {Object}
     */
    getSubContent(branch) {
      if (!branch.children.length) {
        return branch.content;
      }

      branch.content.subContent = [];
      branch.children.forEach((subBranch, index) => {
        branch.content.subContent[index] = this.getSubContent(subBranch);
      });
      return branch.content;
    },
  },
};
</script>

<lang-strings>
{
  "totara_core": [
    "a11y_tree_region_summary"
  ]
}
</lang-strings>

<style lang="scss">
.tui-treeBranch {
  display: flex;
  width: 100%;

  &--top {
    position: relative;
    padding: var(--gap-2) 0;
  }

  &--separator {
    &:after {
      position: absolute;
      bottom: 1px;
      left: 0;
      width: 100%;
      border-bottom: var(--border-width-thin) solid var(--color-neutral-5);
      content: '';
    }
  }

  &__content {
    width: 100%;
    padding-left: calc(var(--gap-1) / 2);
  }

  &__trigger {
    padding-top: calc(var(--gap-1) / 2);

    &-btn {
      left: calc(var(--gap-1) / 2 * -1);
      padding: 0 var(--gap-1);
    }

    &--top {
      .tui-treeBranch__trigger-btn {
        left: 0;
      }
    }
  }

  &__bar {
    display: flex;
    width: 100%;
    min-width: 0;

    & > * + * {
      margin-left: var(--gap-2);
    }

    &-btn {
      flex-grow: 0;
      flex-shrink: 1;
      line-height: 1.2;
      text-align: left;
      -ms-word-break: break-all;
      word-break: break-word;
    }

    &-label,
    &-link {
      @include tui-font-heading-label();
      margin: 0;
      -ms-word-break: break-all;
      word-break: break-word;
      hyphens: none;
    }

    &-side {
      flex-shrink: 0;
      margin-left: auto;
    }
  }

  &__child {
    margin: 0;
    padding-top: var(--gap-3);
    list-style: none;
  }
}
</style>
