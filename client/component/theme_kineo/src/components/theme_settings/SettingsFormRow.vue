<template>
  <FormRowStack spacing="large">
    <FormRow
      v-for="setting in settings"
      v-bind:key="setting.identifier"
      :label="setting.name"
      :is-stacked="true"
    >
      <FormColor
        v-if="setting.type === 'colour'"
        :id="setting.identifier"
        :name="[setting.identifier, 'value']"
        :validations="validations(setting)"
        :aria-describedby="setting.identifier"
      />

      <FormSelect
        v-else-if="setting.type === 'dropdown'"
        :id="setting.identifier"
        :name="[setting.identifier, 'value']"
        :options="setting.options.dropdown_options"
        :aria-describedby="setting.identifier"
      />

      <FormText
        v-else-if="setting.type === 'text'"
        :id="setting.identifier"
        :name="[setting.identifier, 'value']"
        :aria-describedby="setting.identifier"
        :value="setting.default"
        :validations="validations(setting)"
      />

      <FormTextarea
        v-else-if="setting.type === 'textarea'"
        :id="setting.identifier"
        :name="[setting.identifier, 'value']"
        :aria-describedby="setting.identifier"
        :value="setting.default"
      />

      <FormToggleSwitch
        v-else-if="setting.type === 'toggle'"
        :id="setting.identifier"
        :text="setting.identifier"
        :name="[setting.identifier, 'value']"
        :aria-describedby="setting.identifier"
      />

      <FormUrl
        v-else-if="setting.type === 'url'"
        :id="setting.identifier"
        :text="setting.identifier"
        :name="[setting.identifier, 'value']"
        :aria-describedby="setting.identifier"
        :validations="getUrlValidationFn(setting)"
      />

      <ImageUploadSetting
        v-else-if="setting.type === 'image'"
        :metadata="fileData[setting.identifier]"
        :context-id="contextId"
        :show-delete="true"
        @update="$emit('updateImage', $event)"
        @delete="$emit('deleteImage', $event)"
      />

      <FormField
        v-else-if="setting.type === 'editor'"
        v-slot="{ value, update }"
        :name="[setting.identifier, 'value']"
        char-length="full"
      >
        <Editor
          :id="setting.identifier"
          :value="value"
          :default-format="getHtmlFormat()"
          :context-id="contextId"
          :usage-identifier="{
                  component: setting.options.component,
                  area: setting.options.area,
                }"
          variant="standard"
          @input="update"
        />
      </FormField>

      <FormRowDefaults v-if="setting.options.show_default">
        {{ getSettingDefaultLabel(setting) }}
      </FormRowDefaults>

      <FormRowDetails v-if="setting.options.show_identifier">
        {{ getIdentifierLabel(setting) }}
      </FormRowDetails>

      <FormRowDetails>
        {{ setting.description }}
      </FormRowDetails>

    </FormRow>
  </FormRowStack>
</template>
<script>
  import theme_settings from 'tui/lib/theme_settings';
  import FormRowDetails from 'tui/components/form/FormRowDetails';
  import FormRowDefaults from 'tui/components/form/FormRowDefaults';
  import FormRowStack from 'tui/components/form/FormRowStack';
  import {
    FormColor,
    FormField,
    FormText,
    FormSelect,
    FormToggleSwitch,
    FormTextarea,
    FormUrl,
    FormRow
  } from 'tui/components/uniform';
  import { Format } from 'tui/editor';
  import Editor from 'tui/components/editor/Editor';
  import ImageUploadSetting from 'tui/components/theme_settings/ImageUploadSetting';
  import { v as coreValidation } from 'tui/validation';
  import { customValidation as cv } from 'theme_kineo/customValidation';

  export default {

    components: {
      theme_settings,
      FormColor,
      FormField,
      FormText,
      FormSelect,
      FormToggleSwitch,
      FormTextarea,
      FormRow,
      FormRowDetails,
      FormRowDefaults,
      FormRowStack,
      FormUrl,
      Format,
      Editor,
      ImageUploadSetting
    },

    props: {
      settings: Array,
      allThemeVariables: Object,
      contextId: Number,
      fileData: Object,
    },

    data() {
      cv.initSettings(this.allThemeVariables);
      return {
        theme_settings: theme_settings,
        custom_validation: cv
      }
    },

    methods: {

      validations(setting) {
        let rules = [];
        if (setting.options.validation === undefined) {
          return () => rules;
        }
        setting.options.validation.forEach(iterator => {
          if (typeof iterator === "object") {
            let name = iterator.id;
            if (cv.hasOwnProperty(name)) {
              rules.push(cv[name](iterator.value));
            }
          }else if(typeof iterator === "string") {
            if (cv.hasOwnProperty(iterator)) {
              rules.push(cv[iterator]());
            }

            if (iterator === "required") {
              rules.push(coreValidation.required());
            }
          }
        });

        return () => rules;
      },

      /**
       * Custom validations are a little bonkers, but.
       * We must return a function that takes all the validation functions available (so we can use them if we want)
       * Then inside that, we must return either another function (for the validation) or a validation object.
       */
      getUrlValidationFn(setting) {
        return validations => {
          return {
            validate: (value) => (new RegExp(setting.options.pattern)).test(value),
            message: () => this.$str('invalid_url', 'theme_kineo')
          }
        };
      },

      getHtmlFormat() {
        return Format.HTML;
      },

      getSettingDefaultLabel(setting) {
        return setting.options.default_label || setting.default;
      },

      getIdentifierLabel(setting) {
        return '@' + setting.identifier;
      },

    },

    provide() {
      return {
        custom_validation: this.custom_validation
      }
    }

  }
</script>

<lang-strings>
{
"theme_kineo": [
  "invalid_url",
  "invalid_font_size",
  "invalid_size",
  "invalid_colour",
  "invalid_min_value",
  "invalid_pixel_size",
  "invalid_max_value"
]
}
</lang-strings>