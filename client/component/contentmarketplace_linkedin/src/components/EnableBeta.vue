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
  @deprecated Since Totara 15.0
-->

<!--
  Deprecated since Totara 15.0
  Please do not make customisations to this component, as it will be removed in Totara 16.0
-->

<template>
  <div>
    <a href="#" @click="modalOpen = true">
      {{ $str('setup', 'totara_contentmarketplace') }}
    </a>

    <ModalPresenter :open="modalOpen" @request-close="modalOpen = false">
      <Modal size="normal" :aria-labelledby="$id('title')">
        <Uniform vertical validation-mode="submit" @submit="submit">
          <ModalContent
            :close-button="true"
            :title="
              $str(
                'warningenablemarketplace:title',
                'contentmarketplace_linkedin'
              )
            "
            :title-id="$id('title')"
            @dismiss="modalOpen = false"
          >
            <p>
              {{
                $str(
                  'warningenablemarketplace:body:html',
                  'contentmarketplace_linkedin'
                )
              }}
            </p>
            <p
              v-html="$str('beta_registration', 'contentmarketplace_linkedin')"
            />

            <FormRow
              v-slot="{ label }"
              :label="
                $str(
                  'beta_registration_access_code',
                  'contentmarketplace_linkedin'
                )
              "
            >
              <FormText
                name="accessCode"
                :aria-label="label"
                :validations="v => [v.required(), correctCodeValidation]"
                :spellcheck="false"
              />
            </FormRow>

            <template v-slot:buttons>
              <ButtonGroup>
                <Button
                  :styleclass="{ primary: 'true' }"
                  :text="$str('enable')"
                  :loading="loading"
                  type="submit"
                />
                <ButtonCancel @click="modalOpen = false" />
              </ButtonGroup>
            </template>
          </ModalContent>
        </Uniform>
      </Modal>
    </ModalPresenter>
  </div>
</template>

<script>
import Button from 'tui/components/buttons/Button';
import ButtonCancel from 'tui/components/buttons/Cancel';
import ButtonGroup from 'tui/components/buttons/ButtonGroup';
import Modal from 'tui/components/modal/Modal';
import ModalContent from 'tui/components/modal/ModalContent';
import ModalPresenter from 'tui/components/modal/ModalPresenter';
import Uniform from 'tui/components/uniform/Uniform';
import FormRow from 'tui/components/form/FormRow';
import FormText from 'tui/components/uniform/FormText';
import { config } from 'tui/config';

export default {
  components: {
    Button,
    ButtonCancel,
    ButtonGroup,
    Modal,
    ModalContent,
    ModalPresenter,
    Uniform,
    FormRow,
    FormText,
  },

  data() {
    return {
      modalOpen: false,
      loading: false,
    };
  },

  methods: {
    correctCodeValidation(code) {
      // This doesn't need to be secure, we just use an access code to make it more annoying to circumvent.
      if (code === 'linkedin2905') {
        return null;
      }
      return this.$str(
        'beta_registration_access_code_invalid',
        'contentmarketplace_linkedin'
      );
    },

    submit() {
      window.location = this.$url(
        '/totara/contentmarketplace/marketplaces.php',
        {
          id: 'linkedin',
          enable: 1,
          sesskey: config.sesskey,
        }
      );
      this.loading = true;
    },
  },
};
</script>

<lang-strings>
{
  "contentmarketplace_linkedin": [
    "beta_registration",
    "beta_registration_access_code",
    "beta_registration_access_code_invalid",
    "warningenablemarketplace:body:html",
    "warningenablemarketplace:title"
  ],
  "core": [
    "enable"
  ],
  "totara_contentmarketplace": [
    "setup"
  ]
}
</lang-strings>
