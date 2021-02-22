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

  @author Mark Metcalfe <mark.metcalfe@totaralearning.com>
  @module totara_competency
-->

<template>
  <div>Showing competency assignment ID: {{ content.id }}</div>
</template>

<script>
// The GraphQL query to use for getting the selected competencies.
import linkedCompetenciesQuery from 'totara_competency/graphql/perform_linked_competencies';

export { linkedCompetenciesQuery as query };

export default {
  props: {
    content: {
      type: Object,
      required: true,
    },
    settings: Object,
  },
};
</script>
