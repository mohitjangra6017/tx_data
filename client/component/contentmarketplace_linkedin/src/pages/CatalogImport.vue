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
  @package contentmarketplace_linkedin
-->

<template>
  <Layout :title="$str('catalog_title', 'contentmarketplace_linkedin')">
    <template v-if="canManageContent" v-slot:content-nav>
      <PageBackLink
        :link="$url('/totara/contentmarketplace/marketplaces.php')"
        :text="
          $str(
            'back_to_manage_content_marketplaces',
            'totara_contentmarketplace'
          )
        "
      />
    </template>

    <template v-slot:content>
      <p>TBD</p>
    </template>
  </Layout>
</template>

<script>
import Layout from 'tui/components/layouts/LayoutOneColumn';
import PageBackLink from 'tui/components/layouts/PageBackLink';

export default {
  components: {
    Layout,
    PageBackLink,
  },

  props: {
    canManageContent: {
      type: Boolean,
      required: true,
    },
  },
};
</script>

<lang-strings>
  {
    "contentmarketplace_linkedin": [
      "catalog_title"
    ],
    "totara_contentmarketplace": [
      "back_to_manage_content_marketplaces"
    ]
  }
</lang-strings>
