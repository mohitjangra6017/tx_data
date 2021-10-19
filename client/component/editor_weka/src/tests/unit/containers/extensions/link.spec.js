/**
 * This file is part of Totara Enterprise Extensions.
 *
 * Copyright (C) 2021 onwards Totara Learning Solutions LTD
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
 * @author Jaron Steenson <jaron.steenson@totaralearning.com>
 * @module editor_weka
 */

const createLinkExtension = require('editor_weka/extensions/link').default;

describe('editor_weka/extensions/link.js', () => {
  it('displays mailto links as the email address', async () => {
    // Standard simple mailto link.
    let linkUpdateData = await createLinkExtension()._prepareLinkUpdate({
      url: 'mailto:admin@exapmple.com',
    });

    expect(linkUpdateData.url).toEqual('mailto:admin@exapmple.com');
    expect(linkUpdateData.text).toEqual('admin@exapmple.com');

    // With query strings.
    linkUpdateData = await createLinkExtension()._prepareLinkUpdate({
      url: 'mailto:admin@exapmple.com?subject=Hi.',
    });

    expect(linkUpdateData.url).toEqual('mailto:admin@exapmple.com?subject=Hi.');
    expect(linkUpdateData.text).toEqual('admin@exapmple.com?subject=Hi.');
  });
});
