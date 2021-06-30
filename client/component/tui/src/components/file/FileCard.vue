<!--
  This file is part of Totara Enterprise Extensions.

  Copyright (C) 2020 onwards Totara Learning Solutions LTD

  Totara Enterprise Extensions is provided only to Totara
  Learning Solutions LTD's customers and partners, pursuant to
  the terms and conditions of a separate agreement with Totara
  Learning Solutions LTD or its affiliate.

  If you do not have an agreement with Totara Learning Solutions
  LTD, you may not access, use, modify, or distribute this software.
  Please contact [licensing@totaralearning.com] for more information.

  @author Kian Nguyen <kian.nguyen@totaralearning.com>
  @author Brian Barnes <brian.barnes@totaralearning.com>
  @module tui
-->

<template>
  <div
    class="tui-fileCard"
    :class="{
      'tui-fileCard--downloadable': !!downloadUrl,
    }"
    :tabindex="downloadUrl ? 0 : -1"
    :role="downloadUrl ? 'link' : null"
    @click="downloadFile"
    @keydown.enter="downloadFile"
  >
    <FileIcon
      :filename="filename"
      size="600"
      :custom-class="['tui-fileCard__icon']"
      :title="$str('filewithname', 'totara_core', filename)"
    />

    <div class="tui-fileCard__info">
      <div
        class="tui-fileCard__filename"
        :data-file-extension="fileExtension"
        :class="[truncate && `tui-fileCard__filename--truncate`]"
      >
        <p ref="filenameText" class="tui-fileCard__filenameText">
          {{ filename }}
        </p>
      </div>

      <p class="tui-fileCard__fileSize">
        <FileSize :size="fileSize" />
      </p>
    </div>
  </div>
</template>

<script>
import FileIcon from 'tui/components/icons/files/compute/FileIcon';
import FileSize from 'tui/components/file/FileSize';
import { isRtl } from 'tui/i18n';

export default {
  components: {
    FileIcon,
    FileSize,
  },

  inheritAttrs: false,

  props: {
    /**
     * How big the file is (in bytes)
     */
    fileSize: {
      type: [String, Number],
      required: true,
    },

    /**
     * The name of the file (to be displayed)
     * Note: The extension must me correct to display the correct icon
     */
    filename: {
      type: String,
      required: true,
    },

    /**
     * The URL associated to the file
     * If this is not set, the file will not be downloadable
     */
    downloadUrl: {
      type: String,
      default: null,
    },
  },

  data() {
    return {
      truncate: true,
    };
  },

  computed: {
    /**
     * Gets the extenstion of the file (if present)
     *
     * @returns {String|null}
     */
    fileExtension() {
      const separator = '.';
      if (!this.filename.includes(separator)) {
        // No dot.
        return null;
      }

      let parts = this.filename.split(separator);
      return parts.pop();
    },
  },

  mounted() {
    if (isRtl()) {
      this.truncate = false;
    } else {
      this.$nextTick().then(() => {
        const el = this.$refs.filenameText;
        if (el.offsetWidth >= el.scrollWidth) {
          this.truncate = false;
        }
      });
    }
  },

  methods: {
    /**
     * Downloads the file bu setting the windows URL
     */
    downloadFile() {
      if (!this.downloadUrl) {
        return;
      }

      window.document.location.href = this.downloadUrl;
    },
  },
};
</script>
<lang-strings>
  {
    "totara_core": [
      "filewithname"
    ]
  }
</lang-strings>

<style lang="scss">
.tui-fileCard {
  @media (max-width: 490px) {
    // From 490px downward
    width: 100%;
    overflow: hidden;
  }

  @media (min-width: 491px) {
    // From 490px onward
    flex-basis: 20%;
    min-width: 235px;
  }

  position: relative;
  display: flex;
  align-items: center;
  padding: var(--gap-2);
  white-space: normal;
  border: var(--border-width-thin) solid var(--color-neutral-5);
  border-radius: var(--card-border-radius);

  &__info {
    width: calc(100% - 3.2rem - var(--gap-2) - 2.2em);
  }

  &__fileSize {
    margin: 0;
    font-size: var(--font-size-3);
    white-space: nowrap;
  }

  &__filename {
    position: relative;

    &Text {
      margin: 0;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
    }

    &--truncate {
      &:after {
        position: absolute;
        top: 0;
        left: 100%;
        width: 2.2em;
        content: attr(data-file-extension);
      }
    }
  }

  &__icon {
    flex-shrink: 0;
    width: 3.2rem;
    margin-right: var(--gap-2);
    color: var(--color-state);
  }

  &--downloadable {
    cursor: pointer;
  }
}
</style>
