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
  @author Mark Metcalfe <mark.metcalfe@totaralearning.com>
  @module totara_competency
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
        :text="$str('add_competencies', 'totara_competency')"
        @click="adderOpen"
      />

      <AssignedCompetencyAdder
        :open="showAdder"
        :existing-items="selectedIds"
        :user-id="userId"
        @added="adderUpdate"
        @cancel="adderClose"
      />
    </template>

    <template v-slot:content-title="{ content }">
      {{ content.competency.display_name }}
    </template>

    <template v-slot:content-detail="{ content }">
      TBD
    </template>

    <template v-slot:confirm="{ confirm }">
      <Button text="confirm competencies" @click="confirm" />
    </template>
  </SelectContent>
</template>

<script>
import AssignedCompetencyAdder from 'totara_competency/components/adder/AssignedCompetencyAdder';
import Button from 'tui/components/buttons/Button';
import SelectContent from 'performelement_linked_review/components/SelectContent';

export default {
  components: {
    AssignedCompetencyAdder,
    Button,
    SelectContent,
  },

  props: {
    participantInstanceId: {
      type: [String, Number],
      required: true,
    },
    sectionElementId: String,
    settings: Object,
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
  "totara_competency": [
    "add_competencies"
  ]
}
</lang-strings>
