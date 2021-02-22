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
  @module performelement_linked_review
-->

<template>
  <div class="tui-linkedReviewAdminView">
    <Card class="tui-linkedReviewAdminView__card">
      <Component :is="getTypeComponent()" />
    </Card>

    <div class="tui-linkedReviewAdminView__questions">
      <PerformAdminChildElements
        :activity-context-id="activityContextId"
        :activity-id="activityId"
        :activity-state="activityState"
        :addable-element-plugins="respondableElementPlugins"
        :element-id="elementId"
        :section-element="sectionElement"
        :section-id="sectionId"
        @child-update="$emit('child-update')"
        @unsaved-child="$emit('unsaved-child', $event)"
      />
    </div>
  </div>
</template>

<script>
import Card from 'tui/components/card/Card';
import PerformAdminChildElements from 'mod_perform/components/element/PerformAdminChildElements';

export default {
  components: {
    Card,
    PerformAdminChildElements,
  },

  inheritAttrs: false,

  props: {
    activityContextId: Number,
    activityId: Number,
    activityState: Object,
    data: Object,
    elementId: [Number, String],
    elementPlugins: Array,
    sectionElement: Object,
    sectionId: Number,
  },

  computed: {
    /**
     * Provide the plugin list for elements that can be added
     *
     */
    respondableElementPlugins() {
      return this.elementPlugins.filter(
        elementPlugin =>
          elementPlugin.plugin_config.is_respondable &&
          elementPlugin.plugin_name !== 'linked_review'
      );
    },
  },

  methods: {
    getTypeComponent() {
      if (!this.sectionElement.element.data.components) {
        return null;
      }

      return tui.asyncComponent(
        this.sectionElement.element.data.components.admin_view
      );
    },
  },
};
</script>

<style lang="scss">
.tui-linkedReviewAdminView {
  &__card {
    flex-direction: column;
    padding: var(--gap-4);
  }
}
</style>
