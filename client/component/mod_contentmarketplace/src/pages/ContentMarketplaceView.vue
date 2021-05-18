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
    <h1>
      DONE AS PART OF TL-30335
    </h1>
  </div>
</template>

<script>
export default {
  props: {
    /**
     * The content marketplace id, not the course's module id.
     */
    marketplaceId: {
      type: Number,
      required: true,
    },
  },
};
</script>
