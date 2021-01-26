<!--
  This file is part of Totara Learn

  Copyright (C) 2021 onwards Totara Learning Solutions LTD

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.

  @author Marco Song <marco.song@totaralearning.com>
  @package totara_evidence
-->

<template>
  <SelectContent
    :participant-instance-id="participantInstanceId"
    :selected-content="selectedContent"
    :section-element-id="sectionElementId"
    @delete-content="deleteContent"
    @update="$emit('update')"
  >
    <template v-slot:content-picker>
      <Button
        :text="$str('add_evidence', 'totara_evidence')"
        @click="adderOpen"
      />

      <EvidenceAdder
        :open="showAdder"
        :existing-items="selectedIds"
        :user-id="userId"
        @added="adderUpdate"
        @cancel="adderClose"
      />
    </template>

    <template v-slot:content-title="{ content }">
      {{ content.name }}
    </template>

    <template v-slot:content-detail="{ content }">
      TBD
    </template>

    <template v-slot:confirm="{ confirm }">
      <Button text="confirm evidences" @click="confirm" />
    </template>
  </SelectContent>
</template>

<script>
import Button from 'tui/components/buttons/Button';
import EvidenceAdder from 'totara_evidence/components/adder/EvidenceAdder';
import SelectContent from 'performelement_linked_review/components/SelectContent';

export default {
  components: {
    Button,
    EvidenceAdder,
    SelectContent,
  },

  props: {
    participantInstanceId: {
      type: [String, Number],
      required: true,
    },
    sectionElementId: String,
    userId: Number,
  },

  data() {
    return {
      selectedContent: [],
      selectedIds: [],
      showAdder: false,
    };
  },

  methods: {
    adderOpen() {
      this.showAdder = true;
    },

    adderUpdate(selection) {
      this.selectedContent = selection.data;
      this.selectedIds = selection.ids;
      this.adderClose();
    },

    adderClose() {
      this.showAdder = false;
    },

    deleteContent(contentId) {
      this.selectedContent = this.selectedContent.filter(
        item => item.id !== contentId
      );
      this.selectedIds = this.selectedIds.filter(e => e !== contentId);
    },
  },
};
</script>

<lang-strings>
  {
    "totara_evidence": [
      "add_evidence"
    ]
  }
</lang-strings>
