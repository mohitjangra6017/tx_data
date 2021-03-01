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
  @module mod_perform
-->

<template>
  <div class="tui-performAdminChildElements">
    <Droppable
      v-slot="{
        attrs,
        events,
        dropTarget,
        placeholder,
      }"
      :source-id="$id('child-element-list')"
      source-name="Child Element List"
      :reorder-only="true"
      @drop="handleDropElement"
    >
      <div
        class="tui-performAdminChildElements__dragList"
        v-bind="attrs"
        v-on="events"
      >
        <render :vnode="dropTarget" />
        <Draggable
          v-for="(element, index) in childElements"
          :key="element.id"
          v-slot="{ dragging, attrs, events, moveMenu }"
          :index="index"
          :value="element.id"
          :aria-label="$str('move_element', 'mod_perform', element.title)"
          type="element"
          :disabled="!canDrag(element.id)"
        >
          <div
            class="tui-performAdminChildElements__draggableItem"
            v-bind="attrs"
            v-on="events"
          >
            <render :vnode="moveMenu" />
            <PerformAdminCustomElement
              :activity-context-id="activityContextId"
              :activity-id="activityId"
              :activity-state="activityState"
              :draggable="canDrag(element.id)"
              :dragging="dragging"
              :is-multi-section-active="false"
              :section-component="getElementComponent(element)"
              :section-element="{ element: element }"
              :section-id="sectionId"
              @display="setElementToView(element.id)"
              @display-read="setElementToReadOnly(element.id)"
              @edit="setElementToEdit(element.id)"
              @remove="deleteChildElement(element.id)"
              @update="saveElement(element, $event, index)"
            />
          </div>
        </Draggable>
        <render :vnode="placeholder" />
      </div>
    </Droppable>

    <ContentAddElementButton
      v-if="!isActive"
      :element-plugins="addableElementPlugins"
      :for-child-elements="true"
      @add-element-item="addElement"
    />
  </div>
</template>

<script>
//Components
import ContentAddElementButton from 'mod_perform/components/manage_activity/content/ContentAddElementButton';
import Draggable from 'tui/components/drag_drop/Draggable';
import Droppable from 'tui/components/drag_drop/Droppable';
import PerformAdminCustomElement from 'mod_perform/components/element/PerformAdminCustomElement';
import { uniqueId } from 'tui/util';
import Vue from 'vue';

// Queries
import createChildElementMutation from 'mod_perform/graphql/create_child_element';
import deleteChildElementMutation from 'mod_perform/graphql/delete_child_element';
import updateChildElementMutation from 'mod_perform/graphql/update_child_element';
import reorderChildElementMutation from 'mod_perform/graphql/reorder_child_element';

export default {
  components: {
    ContentAddElementButton,
    Draggable,
    Droppable,
    PerformAdminCustomElement,
  },

  inheritAttrs: false,

  props: {
    activityContextId: Number,
    activityId: Number,
    activityState: Object,
    addableElementPlugins: Array,
    elementId: [Number, String],
    sectionElement: Object,
    sectionId: Number,
  },

  data() {
    return {
      // List of child elements
      childElements: [],
      // Display status for each child element
      elementStates: {},
      // New child element list for unsaved elements
      newElements: [],
    };
  },

  computed: {
    isActive() {
      return this.activityState.name === 'ACTIVE';
    },
  },

  watch: {
    sectionElement() {
      this.getChildElements();
    },
  },

  mounted() {
    this.getChildElements();
  },

  methods: {
    async handleDropElement(info) {
      if (info.destination.index === info.source.index) {
        return;
      }
      let newIndex =
        info.source.index < info.destination.index
          ? info.destination.index + 1
          : info.destination.index;
      let afterChildElementId = this.getSavedChildElementIdBeforeIndex(
        newIndex
      );

      if (afterChildElementId === info.item.value) {
        return;
      }

      await this.reorderChildElement(info.item.value, afterChildElementId);
    },

    /**
     * Add new plugin child element.
     *
     * @param {Object} plugin plugin data for selected element
     */
    addElement(plugin) {
      const tempId = 'unsaved-' + uniqueId();

      const childElement = {
        data: {},
        element_plugin: plugin,
        id: tempId,
        identifier: '',
        parent: this.elementId,
        plugin_name: plugin.plugin_name,
        raw_data: {},
        raw_title: '',
        sort_order: 0,
        title: '',
      };

      this.newElements.push(childElement);
      this.setElementToCreate(tempId);
      this.getChildElements();
    },

    /**
     * Check if the child element can be dragged
     *
     * @param {Number} id child element id
     */
    canDrag(id) {
      return this.getChildState(id) === 'view';
    },

    /**
     * Get list of child element data
     *
     */
    getChildElements() {
      let childElementsList = [];
      let childData = this.sectionElement.element.children;

      // Include all child elements provided by the section element data
      if (childData && childData.length > 0) {
        childData.map(element => {
          let el = Object.assign({}, element);

          // Need to convert stringified data from the query
          if (el.data && Object.keys(el.data).length > 0) {
            el.data = JSON.parse(el.data);
          }

          // Need to convert stringified raw data from the query
          if (el.raw_data && Object.keys(el.raw_data).length > 0) {
            el.raw_data = JSON.parse(el.raw_data);
          }

          // Check if element has a state, if not set it to view
          let viewState = this.getChildState(el.id);
          if (!viewState) {
            this.setElementToView(el.id);
          }

          childElementsList.push(el);
        });
      }

      // Include unsaved new elements in the element list
      this.newElements.map(element => {
        let el = Object.assign({}, element);
        childElementsList.push(el);
      });

      this.childElements = childElementsList;
    },

    /**
     * Get the current state (creating, editing, readOnly, view) of child element
     *
     * @param {Number} id child element id
     * @return {String} creating, editing, readOnly, view
     */
    getChildState(id) {
      return this.elementStates[id];
    },

    /**
     * Get the component type (view/editing/readonly), component and its settings for the current section
     *
     * @param {Object} element Current section content
     * @return {Object}
     */
    getElementComponent(element) {
      const elementPlugin = element.element_plugin;
      const elementState = this.getChildState(element.id);

      let subComponent = {
        component: '',
        settings: elementPlugin.plugin_config,
        type: 'editing',
      };

      if (elementState === 'readOnly') {
        subComponent.type = 'readOnly';
        subComponent.component = tui.asyncComponent(
          elementPlugin.admin_summary_component
        );
      } else if (elementState === 'editing' || elementState === 'creating') {
        subComponent.type = 'editing';
        subComponent.component = tui.asyncComponent(
          elementPlugin.admin_edit_component
        );
      } else {
        subComponent.type = 'view';
        subComponent.component = tui.asyncComponent(
          elementPlugin.admin_view_component
        );
      }

      return subComponent;
    },

    /**
     * Save element state
     */
    saveElement(childElement, elementData, index) {
      const elementState = this.getChildState(childElement.id);

      elementData.identifier = elementData.identifier || '';

      elementData.data = elementData.data
        ? JSON.stringify(elementData.data)
        : '';

      // Create element data structure

      // Editing an existing element
      if (elementState === 'editing') {
        let input = {
          element_details: elementData,
          element_id: childElement.id,
        };
        this.updateChildElement(input, childElement.id);
      }
      // Creating a new element
      else if (elementState === 'creating') {
        let input = {
          parent: childElement.parent,
          after_sibling_element_id: this.getSavedChildElementIdBeforeIndex(
            index
          ),
          element: {
            plugin_name: childElement.element_plugin.plugin_name,
            element_details: elementData,
          },
        };
        this.createChildElement({ input }, childElement.id);
      }
    },

    /**
     * Returns the element_id before the specified index.
     *
     * @param {Number} index
     */
    getSavedChildElementIdBeforeIndex(index) {
      const savedChildElementsBefore = this.childElements
        .slice(0, index)
        .filter(this.elementExists);
      if (savedChildElementsBefore.length === 0) {
        return null;
      }

      let previousSavedChildElement = savedChildElementsBefore
        .reverse()
        .find(this.elementExists);

      return previousSavedChildElement ? previousSavedChildElement.id : null;
    },

    /**
     * Returns if the element exists. i.e saved in the database.
     *
     * @param {Object} element
     * @return boolean
     */
    elementExists(element) {
      return typeof element.id === 'string' && !element.id.includes('unsaved-');
    },

    /**
     * Set the current state (creating, editing, readOnly, view) of child element
     *
     * @param {Number} id
     * @param {String} state state of child element (creating, editing, readOnly, view)
     */
    setChildState(id, state) {
      Vue.set(this.elementStates, id, state);
      this.trackUnsavedChanges();
    },

    /**
     * set child element id state to creating
     *
     * @param {Number} id child element id
     */
    setElementToCreate(id) {
      this.setChildState(id, 'creating');
    },

    /**
     * set child element id state to editing
     *
     * @param {Number} id child element id
     */
    setElementToEdit(id) {
      this.setChildState(id, 'editing');
    },

    /**
     * set child element id state to read only
     *
     * @param {Number} id child element id
     */
    setElementToReadOnly(id) {
      this.setChildState(id, 'readOnly');
    },

    /**
     * set child element id state to view
     *
     * @param {Number} id child element id
     */
    setElementToView(id) {
      // if it's unsaved, remove it
      if (id.substring(0, 7) === 'unsaved') {
        this.newElements = this.newElements.filter(item => id !== item.id);
        this.getChildElements();
      } else {
        this.setChildState(id, 'view');
      }
    },

    /**
     * Track if any child elements are in edit mode
     *
     */
    trackUnsavedChanges() {
      let hasUnsavedChanges = false;

      Object.entries(this.elementStates).forEach(([key]) => {
        if (this.elementStates[key] === 'editing') {
          hasUnsavedChanges = true;
        }
      });

      this.$emit('unsaved-child', {
        key: this.elementId,
        hasChanges: hasUnsavedChanges,
      });
    },

    /**
     * Create a new child element
     *
     * @param {Object} elementData
     * @param id
     */
    async createChildElement(elementData, id) {
      await this.$apollo.mutate({
        mutation: createChildElementMutation,
        variables: elementData,
      });

      // Request for full section data update
      this.$emit('child-update');

      // Remove temp created element
      this.newElements = this.newElements.filter(element => {
        if (element.id === id) {
          return;
        }
        return element;
      });
    },

    /**
     * Delete child element
     *
     * @param {Number} id
     */
    async deleteChildElement(id) {
      let input = {
        element_id: id,
      };
      await this.$apollo.mutate({
        mutation: deleteChildElementMutation,
        variables: { input },
      });

      // Request for full section data update
      this.$emit('child-update');
    },

    /**
     * Update child element
     *
     * @param {Object} elementData
     * @param id
     */
    async updateChildElement(elementData, id) {
      const data = { input: elementData };

      await this.$apollo.mutate({
        mutation: updateChildElementMutation,
        variables: data,
      });

      // Request for full section data update
      this.$emit('child-update');

      // Set the element to view state
      this.setElementToView(id);
    },

    async reorderChildElement(element_id, after_sibling_element_id) {
      const data = {
        input: {
          element_id,
          after_sibling_element_id,
        },
      };

      await this.$apollo.mutate({
        mutation: reorderChildElementMutation,
        variables: data,
      });

      // Request for full section data update
      this.$emit('child-update');
    },
  },
};
</script>

<lang-strings>
  {
    "mod_perform": [
      "move_element"
    ]
  }
</lang-strings>

<style lang="scss">
.tui-performAdminChildElements {
  & > * + * {
    margin-top: var(--gap-4);
  }

  &__dragList {
    & > * + * {
      margin-top: var(--gap-4);
    }
  }
}
</style>
