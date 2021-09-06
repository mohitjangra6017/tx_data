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

  @author Qingyang Liu <qingyang.liu@totaralearning.com>
  @module totara_oauth2
-->

<template>
  <Layout
    class="tui-oauth2ProviderPage"
    :title="title"
    :loading="$apollo.loading"
  >
    <template v-if="!$apollo.loading" v-slot:content>
      <template v-if="hasNoRecordError">
        <span class="tui-oauth2ProviderPage__errorTitle">
          {{ $str('no_record_found', 'totara_oauth2') }}
        </span>
      </template>

      <template v-else>
        <Uniform input-width="full" class="tui-oauth2ProviderPage__form">
          <FormRow :label="$str('client_provider_name', 'totara_oauth2')">
            <InputSizedText>{{ provider.name }}</InputSizedText>
          </FormRow>

          <FormRow
            :label="$str('client_provider_description', 'totara_oauth2')"
          >
            <InputSizedText>{{ provider.description }}</InputSizedText>
          </FormRow>

          <FormRow :label="$str('client_id', 'totara_oauth2')">
            <InputSizedText>{{ provider.clientId }}</InputSizedText>
          </FormRow>

          <FormRow :label="$str('client_secret', 'totara_oauth2')">
            <InputSizedText>{{ provider.clientSecret }}</InputSizedText>
          </FormRow>

          <FormRow :label="$str('oauth_url', 'totara_oauth2')">
            <InputSizedText>{{ provider.oauthUrl }}</InputSizedText>
          </FormRow>

          <FormRow :label="$str('xapi_url', 'totara_oauth2')">
            <InputSizedText>{{ provider.xapiUrl }}</InputSizedText>
          </FormRow>
        </Uniform>
      </template>
    </template>
  </Layout>
</template>

<script>
import Layout from 'tui/components/layouts/LayoutOneColumn';
import { Uniform } from 'tui/components/uniform';
import FormRow from 'tui/components/form/FormRow';
import InputSizedText from 'tui/components/form/InputSizedText';

// GraphQL
import clientProviders from 'totara_oauth2/graphql/client_providers';

export default {
  components: {
    Layout,
    FormRow,
    Uniform,
    InputSizedText,
  },

  props: {
    title: {
      type: String,
      required: true,
    },

    id: {
      type: Number,
      default: null,
    },
  },

  data() {
    return {
      provider: {},
    };
  },

  computed: {
    hasNoRecordError() {
      return this.id === null;
    },
  },

  apollo: {
    providers: {
      query: clientProviders,
      variables() {
        return {
          id: this.id,
        };
      },

      skip() {
        return !this.id;
      },

      update({ providers }) {
        if (providers.length > 0) {
          // Currently only one record in the array, safely get the first element
          const {
            client_id,
            client_secret,
            description,
            name,
            oauth_url,
            xapi_url,
          } = providers[0];
          this.provider = {
            clientId: client_id,
            clientSecret: client_secret,
            description: description,
            name: name,
            oauthUrl: oauth_url,
            xapiUrl: xapi_url,
          };
        }

        return providers;
      },
    },
  },
};
</script>

<lang-strings>
  {
    "totara_oauth2": [
      "client_provider_name",
      "client_provider_description",
      "client_id",
      "client_secret",
      "oauth_url",
      "xapi_url",
      "no_record_found"
    ]
  }
</lang-strings>
