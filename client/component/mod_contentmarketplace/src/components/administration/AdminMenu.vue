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
  @package mod_contentmarketplace
-->
<template>
  <div class="tui-linkedinActivityAdminMenu">
    <Button
      v-if="stackedLayout"
      :text="$str('administration', 'mod_contentmarketplace')"
      @click="showAdminModal"
    />

    <Dropdown v-else :close-on-click="false">
      <template v-slot:trigger="{ toggle, isOpen }">
        <Button
          :aria-expanded="isOpen ? 'true' : 'false'"
          :aria-label="$str('administration', 'mod_contentmarketplace')"
          :caret="true"
          :text="$str('administration', 'mod_contentmarketplace')"
          @click="toggle"
        />
      </template>

      <Tree
        v-model="openTreeBranches"
        class="tui-linkedinActivityAdminMenu__tree tui-linkedinActivityAdminMenu__tree--dropDown"
        :tree-data="treeData"
        @input="$emit('input', $event)"
      >
        <!-- Branch label -->
        <template v-slot:custom-label="{ label, linkUrl, topLevel }">
          <div
            class="tui-linkedinActivityAdminMenu__tree-contentsLabel"
            :class="{
              'tui-linkedinActivityAdminMenu__tree-topBranch': topLevel,
            }"
          >
            <DropdownItem v-if="linkUrl" :href="linkUrl" :no-padding="true">
              {{ label }}
            </DropdownItem>
            <template v-else>
              {{ label }}
            </template>
          </div>
        </template>
      </Tree>
    </Dropdown>

    <ModalPresenter :open="modalOpen" @request-close="closeModal">
      <Modal size="sheet" :aria-labelledby="$id('admin-modal')">
        <ModalContent
          :title="$str('administration', 'mod_contentmarketplace')"
          :title-id="$id('admin-modal')"
          @dismiss="closeModal"
        >
          <Tree
            v-model="openTreeBranches"
            class="tui-linkedinActivityAdminMenu__tree"
            label-type="link"
            :tree-data="treeData"
            @input="$emit('input', $event)"
          />
        </ModalContent>
      </Modal>
    </ModalPresenter>
  </div>
</template>

<script>
import Button from 'tui/components/buttons/Button';
import Dropdown from 'tui/components/dropdown/Dropdown';
import DropdownItem from 'tui/components/dropdown/DropdownItem';
import Modal from 'tui/components/modal/Modal';
import ModalContent from 'tui/components/modal/ModalContent';
import ModalPresenter from 'tui/components/modal/ModalPresenter';
import Tree from 'tui/components/tree/Tree';
import { config } from 'tui/config';

import settingsTreeQuery from 'totara_core/graphql/settings_navigation_tree';

export default {
  components: {
    Button,
    Dropdown,
    DropdownItem,
    Modal,
    ModalContent,
    ModalPresenter,
    Tree,
  },

  props: {
    /**
     * Tree data for admin options
     */
    stackedLayout: Boolean,
  },

  data() {
    return {
      modalOpen: false,
      openTreeBranches: ['modulesettings'],
    };
  },

  apollo: {
    treeData: {
      query: settingsTreeQuery,
      variables() {
        return {
          context_id: config.context.id,
        };
      },
      update({ tree: data }) {
        return data;
      },
    },
  },

  methods: {
    /**
     * Close admin modal.
     */
    closeModal() {
      this.modalOpen = false;
    },

    showAdminModal() {
      this.modalOpen = true;
    },
  },
};
</script>

<lang-strings>
  {
    "mod_contentmarketplace": [
      "administration"
    ]
  }
</lang-strings>

<style lang="scss">
.tui-linkedinActivityAdminMenu {
  &__tree {
    @include tui-wordbreak--hard();

    &-topBranch {
      font-weight: bold;
    }

    &-contentsItem {
      margin-top: var(--gap-2);
      white-space: normal;
    }

    &-contentsLabel {
      width: 100%;
    }

    &--dropDown {
      margin: 0 var(--gap-2);
    }
  }
}
</style>
