<template>
  <Form :native-submit="true" method="POST" :action="getFormUrl()">
    <InputHidden
        :name="'draft_id'"
        v-bind:value="draftId"
    />
    <NotificationBanner type="info" :message="this.$str('theme_restore:info', 'theme_kineo', this.maxFileSize)"/>
    <FormRow>
      <UploadWrapper
          :href="fileUrl"
          :item-id="itemId"
          :repository-id="repositoryId"
          @file-updated="fileUploaded"
      />
    </FormRow>
    <FormRow>
      <ButtonGroup>
        <Button
            :disabled="!uploaded"
            :styleclass="{ primary: 'true' }"
            :text="'Save'"
            :aria-label="'Save'"
            type="submit"
        />
      </ButtonGroup>
    </FormRow>
  </Form>
</template>

<script>
import { FormRow } from 'tui/components/uniform';
import Form from 'tui/components/form/Form';
import UploadWrapper from 'theme_kineo/components/form/UploadWrapper';
import Button from 'tui/components/buttons/Button';
import ButtonGroup from 'tui/components/buttons/ButtonGroup';
import InputHidden from "tui/components/form/InputHidden";
import NotificationBanner from 'tui/components/notifications/NotificationBanner';

export default {

  components: {
    InputHidden,
    Button,
    ButtonGroup,
    FormRow,
    Form,
    UploadWrapper,
    NotificationBanner
  },

  props: {
    contextId: Number,
    tenantId: Number,

    fileUrl: String,
    itemId: Number,
    repositoryId: Number,
    maxFileSize: String
  },

  data() {
    return {
      draftId: null,
      uploaded: false
    }
  },

  methods: {
    fileUploaded(file) {
      this.draftId = this.itemId;
      this.uploaded = true;
    },
    getSaveButtonLabel() {
      return this.$str('save', 'totara_core');
    },
    getFormUrl () {
      return window.location;
    },
  }
}
</script>

<lang-strings>
{
"totara_core": ["save"],
"theme_kineo": ["theme_restore:info"]
}
</lang-strings>