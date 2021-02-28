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
    :adder="getAdder()"
    :add-btn-text="$str('add_evidence', 'totara_evidence')"
    :can-show-adder="canShowAdder"
    :cant-add-text="
      $str(
        'awaiting_selection_text',
        'totara_evidence',
        coreRelationship[0].name
      )
    "
    :is-draft="isDraft"
    :participant-instance-id="participantInstanceId"
    :required="required"
    :section-element-id="sectionElementId"
    :user-id="userId"
    @update="$emit('update')"
  >
    <template v-slot:content-preview="{ content }">
      <component :is="previewComponent" :content="content" />
    </template>
  </SelectContent>
</template>

<script>
import EvidenceAdder from 'totara_evidence/components/adder/EvidenceAdder';
import SelectContent from 'performelement_linked_review/components/SelectContent';

export default {
  components: {
    EvidenceAdder,
    SelectContent,
  },

  props: {
    canShowAdder: {
      type: Boolean,
      required: true,
    },
    coreRelationship: Array,
    isDraft: Boolean,
    participantInstanceId: {
      type: [String, Number],
      required: true,
    },
    previewComponent: [Function, Object],
    required: Boolean,
    sectionElementId: String,
    userId: Number,
  },

  methods: {
    /**
     * Get adder component
     *
     * @return {Object}
     */
    getAdder() {
      return EvidenceAdder;
    },
  },
};
</script>

<lang-strings>
  {
    "totara_evidence": [
      "add_evidence",
      "awaiting_selection_text"
    ]
  }
</lang-strings>
