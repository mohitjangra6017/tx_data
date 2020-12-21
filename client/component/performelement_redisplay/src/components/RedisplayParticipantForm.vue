<!--
  This file is part of Totara Enterprise Extensions.

  Copyright (C) 2020 onwards Totara Learning Solutions LTD

  Totara Enterprise Extensions is provided only to Totara
  Learning Solutions LTD's customers and partners, pursuant to
  the terms and conditions of a separate agreement with Totara
  Learning Solutions LTD or its affiliate.

  If you do not have an agreement with Totara Learning Solutions
  LTD, you may not access, use, modify, or distribute this software.
  Please contact [licensing@totaralearning.com] for more information.

  @author Kunle Odusan <kunle.odusan@totaralearning.com>
  @module performelement_redisplay
  -->

<template>
  <div class="tui-redisplayParticipantForm">
    {{ redisplayData.title }}

    <div class="tui-redisplayParticipantForm__cardArea">
      <Card class="tui-redisplayParticipantForm__card">
        <h4 class="tui-redisplayParticipantForm__card-title">
          {{ element.data.elementTitle }}
        </h4>

        <div class="tui-redisplayParticipantForm__card-content">
          <FormRow
            v-if="redisplayData.your_response"
            :label="$str('your_response', 'mod_perform')"
          >
            <div class="tui-redisplayParticipantForm__card-contentResponse">
              <component
                :is="sourceComponent"
                :data="JSON.parse(redisplayData.your_response.response_data)"
                :response-lines="
                  redisplayData.your_response.response_data_formatted_lines
                "
              />
            </div>
          </FormRow>

          <OtherParticipantResponses
            :section-element="{
              responseDisplayComponent: sourceComponent,
              other_responder_groups: redisplayData.other_responder_groups,
            }"
            :anonymous-responses="redisplayData.is_anonymous"
            :anonymous-label="
              $str('anonymous_responses', 'performelement_redisplay')
            "
          />
        </div>
      </Card>
    </div>
  </div>
</template>

<script>
import Card from 'tui/components/card/Card';
import OtherParticipantResponses from 'mod_perform/components/user_activities/participant/OtherParticipantResponses';
import { FormRow } from 'tui/components/uniform';
import subjectInstancePreviousResponsesQuery from 'performelement_redisplay/graphql/subject_instance_previous_responses';
import subjectInstancePreviousResponsesForExternalParticipantQuery from 'performelement_redisplay/graphql/subject_instance_previous_responses_nosession';

export default {
  components: {
    Card,
    FormRow,
    OtherParticipantResponses,
  },

  props: {
    element: {
      type: Object,
      validator: function(value) {
        return value.data && value.data.elementPluginDisplayComponent;
      },
    },
  },
  data() {
    return {
      redisplayData: {
        title: null,
        your_response: null,
        other_responder_groups: [],
        is_anonymous: false,
      },
    };
  },

  apollo: {
    redisplayData: {
      query() {
        return this.element.token && this.element.token.length > 0
          ? subjectInstancePreviousResponsesForExternalParticipantQuery
          : subjectInstancePreviousResponsesQuery;
      },
      fetchPolicy: 'network-only',
      variables() {
        return {
          input: {
            participant_section_id: this.element.participantSectionId,
            section_element_id: this.element.data.sectionElementId,
            token: this.element.token || null,
          },
        };
      },
      update(data) {
        return data.redisplayData;
      },
    },
  },

  computed: {
    sourceComponent() {
      return tui.asyncComponent(
        this.element.data.elementPluginDisplayComponent
      );
    },
  },

  methods: {
    /**
     * Check question has other responses
     *
     * @param groupResponses
     * @returns {boolean}
     */
    hasResponses(groupResponses) {
      return groupResponses.length > 0;
    },
  },
};
</script>

<lang-strings>
  {
    "mod_perform": [
      "your_response"
    ],
    "performelement_redisplay": [
      "anonymous_responses"
    ]
  }
</lang-strings>

<style lang="scss">
.tui-redisplayParticipantForm {
  &__cardArea {
    margin: var(--gap-4) 0 0 var(--gap-8);
  }

  &__card {
    flex-direction: column;
    padding: var(--gap-4);

    & > * + * {
      margin-top: var(--gap-4);
    }

    &-title {
      margin: 0;
      @include tui-font-heading-label();
    }

    &-content {
      & > * + * {
        margin-top: var(--gap-4);
      }
    }

    &-contentResponse {
      padding-top: var(--gap-1);
    }
  }
}
</style>
