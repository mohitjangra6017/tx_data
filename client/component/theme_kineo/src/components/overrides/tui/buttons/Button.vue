<style lang="scss">
.tui-formBtn,
%tui-formBtn {
  &:active,
  &:focus,
  &:active:focus,
  &:active:hover,
  &:hover {
    color: var(--btn-text-color-hover);
    background: var(--btn-bg-color-hover);
    border-color: var(--btn-border-color-hover);
  }
  &--prim {
    &:active,
    &:focus,
    &:active:focus,
    &:active:hover,
    &:hover {
      color: var(--btn-prim-text-color-hover);
      background: var(--btn-prim-bg-color-hover);
      border-color: var(--btn-prim-border-color-hover);
    }
  }
}
</style>