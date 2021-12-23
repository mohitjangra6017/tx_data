<style lang="scss">
.tui-tabs--horizontal {
  .tui-tabs__tabs {
    li {
      a.tui-tabs__link {
        border-radius: var(--tabs-border-radius);
        font-size: var(--tabs-font-size);
        text-transform: var(--tabs-text-transform);
        background-color: var(--tabs-bg-color);
        border-color: var(--tabs-border-color);
        color: var(--tabs-text-color);
        font-weight: var(--tabs-font-weight);

        &:hover,
        &:focus {
          background-color: var(--tabs-bg-color-hover);
          border-color: var(--tabs-border-color-hover);
          color: var(--tabs-text-color-hover);
        }

        &:active,
        &:active:focus {
          color: var(--tabs-text-color);
        }
      }

      &.tui-tabs__tab--active {
        a.tui-tabs__link {
          color: var(--tabs-text-color-selected);
          background-color: var(--tabs-bg-color-selected);
          border-radius: var(--tabs-border-radius-active);
          border-color: var(--tabs-border-color-selected);
          border-bottom: 1px solid var(--tabs-bg-color-selected);
          &:hover {
            background-color: var(--tabs-bg-color-active-hover);
            border-color: var(--tabs-border-color-active-hover);
            color: var(--tabs-text-color-active-hover);
          }
        }
      }
    }
  }
}
</style>