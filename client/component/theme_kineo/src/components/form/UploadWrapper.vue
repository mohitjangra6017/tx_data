<template>
  <Upload :repository-id="repositoryId" :item-id="itemId" :href="href" :one-file="true" @upload-finished="fileUpdated" @upload-started="fileStarted">
    <slot />
    <template
        v-slot:default="{
        selectEvents,
        inputEvents,
        dragEvents,
        dragAttrs,
        deleteDraft,
        files,
        isDrag,
      }"
    >
      <div
          class="tui-uploadWrapper"
          :class="{ 'tui-uploadWrapper--highlight': isDrag }"
          v-on="dragEvents"
      >
        <input
            v-show="false"
            ref="inputFile"
            type="file"
            v-on="inputEvents"
        />
        <Button
            :disabled="isUploading"
            :text="$str('theme_restore:select_file', 'theme_kineo')"
            :aria-label="$str('theme_restore:select_file', 'theme_kineo')"
            v-on="selectEvents"
        />
        <ul>
          <li
              v-for="file in files"
              :key="file.name"
              :class="{ 'tui-uploadWrapper__file--done': file.done }"
          >
            {{ file.name }} {{ getFileProgress(file) }}
            <ButtonIcon
                v-show="file.done"
                :styleclass="{ transparent: true }"
                :aria-label="$str('delete', 'core')"
                @click="deleteDraft(file)"
            >
              <DeleteIcon />
            </ButtonIcon>
          </li>
        </ul>
      </div>
    </template>
  </Upload>
</template>

<script>
import Upload from 'tui/components/form/Upload';
import Button from 'tui/components/buttons/Button';
import ButtonIcon from 'tui/components/buttons/ButtonIcon';
import DeleteIcon from 'tui/components/icons/Delete';

export default {
  components: {
    Upload,
    Button,
    ButtonIcon,
    DeleteIcon,
  },

  props: {
    href: {
      type: String,
      required: true,
    },
    itemId: {
      type: Number,
      required: true,
    },
    repositoryId: {
      type: Number,
      required: true,
    },
  },

  data() {
    return {
      isUploading: false,
    }
  },

  methods: {
    fileUpdated (file) {
      this.isUploading = false;
      this.$emit('file-updated', file);
    },
    fileStarted (file) {
      this.isUploading = true;
    },
    getFileProgress(file) {
      if (file.progress > 0) {
        return Math.floor(file.progress) + '%';
      } else if (!file.done) {
        return this.$str('theme_restore:uploading', 'theme_kineo') + '...';
      } else {
        return this.$str('theme_restore:uploaded', 'theme_kineo');
      }
    }
  }
};
</script>

<lang-strings>
{
"core": [
"delete"
],
"theme_kineo": [
"theme_restore:select_file",
"theme_restore:uploading",
"theme_restore:uploaded"
]
}
</lang-strings>