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
  <div>Showing Evidence ID: {{ content.id }}</div>
</template>

<script>
// The GraphQL query to use for getting the selected evidence.
import linkedEvidencesQuery from 'totara_evidence/graphql/perform_linked_evidence_items';
import linkedEvidencesQueryExternal from 'totara_evidence/graphql/perform_linked_evidence_items_nosession';

export {
  linkedEvidencesQuery as query,
  linkedEvidencesQueryExternal as query_external,
};

export default {
  props: {
    content: {
      type: Object,
      required: true,
    },
  },
};
</script>
