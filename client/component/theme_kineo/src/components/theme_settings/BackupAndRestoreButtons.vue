<template>
  <div class="backup-restore__section">
    <ActionLink
        :href="backupLink(selectedTenantId, themeName)" target="_blank"
        :text="$str('theme_backup:title', 'theme_kineo')"
        :styleclass="{
            primary: true,
          }"
    />
    <ActionLink
        :href="restoreLink(selectedTenantId, themeName)"
        :text="$str('theme_restore:title', 'theme_kineo')"
        :styleclass="{
            primary: true,
          }"
    />
  </div>
</template>

<script>
import ActionLink from 'tui/components/links/ActionLink';

export default {
  components: {
    ActionLink
  },

  props: {
    /**
     * Tenant ID or null if global/multi-tenancy not enabled.
     */
    selectedTenantId: Number,

    themeName: String
  },

  methods: {
    backupLink(tenantId, themeName) {
      return this.$url('/theme/kineo/theme_backup.php', {
        theme_name: themeName,
        tenant_id: tenantId,
      });
    },
    restoreLink(tenantId, themeName) {
      return this.$url('/theme/kineo/theme_restore.php', {
        theme_name: themeName,
        tenant_id: tenantId,
      });
    },
  }
}

</script>

<lang-strings>
{
  "theme_kineo": [
    "theme_backup:title",
    "theme_restore:title"
  ]
}
</lang-strings>

<style lang="scss">
  .backup-restore {
    &__section {
      @include tui-stack-vertical(var(--gap-4));
    }
  }
  .tui-formBtn--prim,
  .tui-iconBtn--prim,
  .tui-actionLink--prim {
   &,
   &:active,
    &:visited {
     color: var(--btn-prim-text-color);
     background-color: var(--btn-prim-bg-color);
     border-color: var(--btn-prim-border-color);
     &:hover {
       color: var(--btn-prim-text-color-hover);
       background-color: var(--btn-prim-bg-color-hover);
       border-color: var( --btn-prim-border-color-hover);
     }
    }
    &.disabled,
    &[disabled] {
      &,
      &:active:focus,
      &:hover,
      &:focus {
        color: var(--btn-prim-text-color-disabled);
        background-color: var(--btn-prim-border-color-disabled);
        border-color: var(--btn-prim-border-color-disabled);
      }
    }
  }
</style>
