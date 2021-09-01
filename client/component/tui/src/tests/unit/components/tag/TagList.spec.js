/**
 * This file is part of Totara Enterprise Extensions.
 *
 * Copyright (C) 2020 onwards Totara Learning Solutions LTD
 *
 * Totara Enterprise Extensions is provided only to Totara
 * Learning Solutions LTD's customers and partners, pursuant to
 * the terms and conditions of a separate agreement with Totara
 * Learning Solutions LTD or its affiliate.
 *
 * If you do not have an agreement with Totara Learning Solutions
 * LTD, you may not access, use, modify, or distribute this software.
 * Please contact [licensing@totaralearning.com] for more information.
 *
 * @author Tatsuhiro Kirihara <tatsuhiro.kirihara@totaralearning.com>
 * @module tui
 */

import { mount, shallowMount } from '@vue/test-utils';
import component from 'tui/components/tag/TagList.vue';
let wrapper;

const stubs = {
  Close: true,
  InputText: true,
  Expand: true,
  OverflowDetector: {
    render() {
      return this.$scopedSlots.default({ measuring: true });
    },
  },
};

const data = function() {
  return {
    visible: 0,
  };
};

const propsData = {
  tags: [{ id: 1, text: 'Tui' }],
};

describe('presentation/tag/TagList.vue', () => {
  beforeAll(() => {
    wrapper = mount(component, {
      stubs,
      data,
      propsData,
    });
  });

  it('Checks snapshot', () => {
    expect(wrapper.element).toMatchSnapshot();
  });

  it('closeOnClick is false by default', () => {
    expect(wrapper.vm.closeOnClick).toBe(false);
  });

  it('closeOnClick is passed through dropdown', () => {
    wrapper = shallowMount(component, {
      stubs,
      data,
      propsData: Object.assign({}, propsData, { closeOnClick: true }),
    });
    expect(wrapper.element).toMatchSnapshot();
  });

  it('uses the labelName provided for dropdown button, input field and remove tag button', () => {
    wrapper = mount(component, {
      propsData: Object.assign({}, propsData, {
        labelName: 'Learning category',
      }),
    });
    expect(wrapper.element).toMatchSnapshot();
  });
});
