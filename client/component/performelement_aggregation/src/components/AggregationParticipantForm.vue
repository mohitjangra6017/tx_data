<!--
  This file is part of Totara Learn

  Copyright (C) 2020 onwards Totara Learning Solutions LTD

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

  @author Jaron Steenson <jaron.steenson@totaralearning.com>
  @package mod_perform
-->

<template>
  <div class="tui-aggregationParticipantForm">
    <div v-if="hasExcludedValues">
      {{
        $str(
          'header_blurb_with_exclusions',
          'performelement_aggregation',
          excludedValuesCsv
        )
      }}
    </div>
    <div>
      {{ $str('header_blurb', 'performelement_aggregation') }}
    </div>

    <ElementParticipantFormContent
      v-bind="$attrs"
      :participant-can-answer="sectionElement.can_respond"
      :element="element"
      :section-element="sectionElement"
      :from-print="false"
      :is-draft="isDraft"
    >
      <template v-slot:content="{ labelId }">
        <ElementParticipantResponse>
          <template v-slot:content>
            <ResponseDisplay
              :element="element"
              :data="sectionElement.response_data_formatted_lines"
            />
          </template>
        </ElementParticipantResponse>
      </template>
    </ElementParticipantFormContent>
  </div>
</template>

<script>
import ElementParticipantResponse from 'mod_perform/components/element/ElementParticipantResponse';
import ResponseDisplay from 'mod_perform/components/element/participant_form/ResponseDisplay';
import ElementParticipantFormContent from 'mod_perform/components/element/ElementParticipantFormContent';

export default {
  components: {
    ElementParticipantFormContent,
    ElementParticipantResponse,
    ResponseDisplay,
  },
  props: {
    path: [String, Array],
    error: String,
    isDraft: Boolean,
    element: {
      type: Object,
      required: true,
    },
    participantInstanceId: {
      type: Number,
      required: false,
    },
    sectionElement: {
      type: Object,
      required: true,
    },
  },
  computed: {
    hasExcludedValues() {
      return this.excludedValues.length > 0;
    },
    excludedValuesCsv() {
      return this.excludedValues.join(', ');
    },
    excludedValues() {
      if (!this.element.data.excludedValues) {
        return [];
      }

      // Remove any empty entries.
      return this.element.data.excludedValues.filter(
        value => value !== null && value.trim() !== ''
      );
    },
  },
};
</script>

<style lang="scss">
.tui-aggregationParticipantForm {
  & > * + * {
    margin-top: var(--gap-4);
  }
}
</style>

<lang-strings>
{
  "performelement_aggregation": [
    "header_blurb",
    "header_blurb_with_exclusions"
  ]
}
</lang-strings>
