<!--
  This file is part of Totara Enterprise Extensions.

  Copyright (C) 2021 onwards Totara Learning Solutions LTD

  Totara Enterprise Extensions is provided only to Totara
  Learning Solutions LTD’s customers and partners, pursuant to
  the terms and conditions of a separate agreement with Totara
  Learning Solutions LTD or its affiliate.

  If you do not have an agreement with Totara Learning Solutions
  LTD, you may not access, use, modify, or distribute this software.
  Please contact [licensing@totaralearning.com] for more information.

  @author Kian Nguyen <kian.nguyen@totaralearning.com>
  @module
-->
<template>
  <div>
    <template v-if="contentMarketplace">
      <!-- This page rendering is temporary, will be fixed later in Learner workflow -->
      <h1>
        {{ contentMarketplace.name }}
      </h1>

      <p>course_id: {{ contentMarketplace.course }}</p>
      <p>language: {{ contentMarketplace.learning_object.language }}</p>
    </template>
  </div>
</template>

<script>
import contentMarketplaceQuery from 'mod_contentmarketplace/graphql/content_marketplace';

export default {
  props: {
    /**
     * The course's module id, not the content marketplace id.
     */
    cmId: {
      type: Number,
      required: true,
    },
  },

  apollo: {
    contentMarketplace: {
      query: contentMarketplaceQuery,
      variables() {
        return {
          cm_id: this.cmId,
        };
      },
      update({ instance }) {
        return instance;
      },
    },
  },

  data() {
    return {
      contentMarketplace: null,
    };
  },
};
</script>
