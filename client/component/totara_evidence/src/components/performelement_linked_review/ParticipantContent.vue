<!--
  This file is part of Totara Learn

  Copyright (C) 2021 onwards Totara Learning Solutions LTD

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.

  @author Marco Song <marco.song@totaralearning.com>
  @package totara_evidence
-->

<template>
  <div v-if="content" class="tui-linkedReviewViewEvidence">
    Showing Evidence ID: {{ content && content.id }}
  </div>

  <div v-else class="tui-linkedReviewViewEvidenceMissing">
    {{ $str('perform_review_evidence_missing', 'totara_evidence') }}
  </div>
</template>

<script>
export default {
  props: {
    content: {
      type: Object,
    },
  },
};
</script>

<lang-strings>
{
  "totara_evidence": [
    "perform_review_evidence_missing"
  ]
}
</lang-strings>

<style lang="scss">
.tui-linkedReviewViewEvidence {
  & > * + * {
    margin-top: var(--gap-4);
  }
}
</style>
