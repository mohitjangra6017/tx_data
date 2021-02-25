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
  @module totara_competency
-->

<template>
  <ParticipantContent :content="getPreviewData()" :preview="true" />
</template>

<script>
import ParticipantContent from 'totara_competency/components/performelement_linked_review/ParticipantContent';

export default {
  components: {
    ParticipantContent,
  },

  methods: {
    /**
     * Set placeholder data for preview view
     *
     */
    getPreviewData() {
      return {
        competency: {
          display_name: this.$str(
            'example_competency_title',
            'totara_competency'
          ),
          description: this.$str(
            'example_competency_description',
            'totara_competency'
          ),
        },
        assignment: {
          reason_assigned: this.$str(
            'assignment_reason_self',
            'totara_competency'
          ),
        },

        achievement: {
          id: '0',
          name: this.$str('example_competency_level', 'totara_competency'),
          proficient: false,
        },
      };
    },
  },
};
</script>

<lang-strings>
  {
    "totara_competency": [
      "assignment_reason_self",
      "example_competency_description",
      "example_competency_level",
      "example_competency_title"
    ]
  }
</lang-strings>
