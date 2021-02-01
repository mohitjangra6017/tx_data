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

  @author Steve Barnett <steve.barnett@totaralearning.com>
  @module totara_notification
-->

<template>
  <div class="tui-notificationPage">
    <NotificationHeader :title="title" />
    <Layout class="tui-notificationPage">
      <!--filter-->
      <template v-slot:left />
      <template v-slot:right>
        <NotificationTable class="tui-notificationPage__table" />
      </template>
    </Layout>
  </div>
</template>
<script>
import Layout from 'tui/components/layouts/LayoutTwoColumn';
import NotificationTable from 'totara_notification/components/table/NotificationTable';
import NotificationHeader from 'totara_notification/components/header/NotificationHeader';

export default {
  components: {
    Layout,
    NotificationTable,
    NotificationHeader,
  },

  props: {
    title: {
      type: String,
      required: true,
    },
  },
};
</script>
