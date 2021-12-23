<?php
$string['allowadditionalcssclasses'] = 'Allow additional CSS classes';
$string['configallowadditionalcssclasses'] = 'Adds a configuration option to HTML block instances allowing additional CSS classes to be set.';
$string['configclasses'] = 'Additional CSS classes';
$string['configclasses_help'] = 'The purpose of this configuration is to aid with theming by helping distinguish HTML blocks from each other. Any CSS classes entered here (space delimited) will be appended to the block\'s default classes.';
$string['configcontent'] = 'Content';
$string['configtitle'] = 'Block title';
$string['configselector'] = 'Selector';
$string['configselector_help'] = 'If this selector is not empty it will apply the background image to it instead of the block itself.';
$string['configcolour'] = 'Background colour (hex)';
$string['configcolour_help'] = 'This background colour will be added as inline style to the Selector element';
$string['configimagestrategy'] = 'Image selection style';

$string['configtextcolour'] = 'Main text colour';
$string['configtextcolour_help'] = 'If your image is predominately dark, you should set the text colour to light to provide the best experience. Likewise, a light image should have dark text.';
$string['darktext'] = 'Dark text';
$string['lighttext'] = 'Light text';

$string['backgroundoptions'] = 'Background Options';
$string['configbgrepeat'] = 'Background Repeat';
$string['configbgrepeat_help'] = 'Repeats the background image. Options are:<ul>
<li>Repeat Horizontal: the background image will be repeated horizontally only.</li>
<li>Repeat Vertical: the background image will be repeated vertically only.</li>
<li>Repeat Both: repeats the background image both vertically and horizontally.</li>
<li>No Repeat: The image will only be displayed once, even if it doesn\'t fill up the space.</li></ul>';
$string['configbgpos'] = 'Background Position';
$string['configbgpos_help'] = 'Positions the background image. Options are:<ul>
<li>Top, bottom, left, right: Anchors the image to the given side. If the image is larger than the background area, it will flow off the opposite side.</li>
<li>Centre: The centre of the image matches the centre of the background area. If the image is larger than the background area, it will flow off all sides.</li>
<li>Custom: Allows positioning by an exact number from the top left corner. All standard CSS units (such as px, pt, em) are accepted.</li></ul>';
$string['configbgsize'] = 'Background Size';
$string['configbgsize_help'] = 'Sizes the background image. Options are:<ul>
<li>Automatically sized: The image is sized at 100% of the original size, or smaller if the container is smaller.</li>
<li>Fill background completely: The image will completely fill the background area, even if it is enlarged past 100%, potentially leading to pixelation.</li>
<li>Contain background: The image will fit into the container perfectly, without bleeding out any of the edges. This may leave a gap where it doesn\'t fill the background area completely.</li>
<li>Custom: Allows a custom width to be used. The height can also be set either to auto (which keeps the image aspect ratio) or another custom value. All standard CSS units (such as px, pt, em) are accepted.</li></ul>';
$string['configbgattachment'] = 'Background Attachment';
$string['configbgattachment_help'] = 'Sets the how the background is attached to the window. Options are:<ul>
<li>Scroll: The image is sized based on the height of the element is set on, and scrolls with that element on the page.</li>
<li>Fixed: The image maintains a fixed size (generally the size of the image unless using the size override above). The image does not scroll with the viewport, and will maintain its position on the page.</li></ul>';
$string['configctsize'] = 'Content Size';
$string['configctsize_help'] = 'Sizes the container the image is shown in. Options are:<ul>
<li>Automatically sized: The image container is sized at 100% of the original image size.</li>
<li>Custom: Allows a custom size to be used. All standard CSS units (such as px, pt, em) are accepted.</li></ul>';

$string['posleft'] = 'Left Position (with units)';
$string['postop'] = 'Top Position (with units)';
$string['sizewidth'] = 'Width (with units)';
$string['sizeheight'] = 'Height (with units)';

$string['repeat'] = 'Repeat Both';
$string['repeat-x'] = 'Repeat Horizontal';
$string['repeat-y'] = 'Repeat Vertical';
$string['no-repeat'] = 'No Repeat';

$string['top'] = 'Top';
$string['bottom'] = 'Bottom';
$string['left'] = 'Left';
$string['right'] = 'Right';
$string['center'] = 'Centre';
$string['custom'] = 'Custom';

$string['auto'] = 'Automatically sized';
$string['cover'] = 'Fill background completely';
$string['contain'] = 'Contain background';

$string['scroll'] = 'Scroll';
$string['fixed'] = 'Fixed';

$string['banner:addinstance'] = 'Add a new Banner block';
$string['banner:myaddinstance'] = 'Add a new Banner block';
$string['leaveblanktohide'] = 'leave blank to hide the title';
$string['newhtmlblock'] = '(Banner)';
$string['pluginname'] = 'Banner';

$string['invalidnumber'] = 'Invalid number or unit';
$string['variables:list'] = 'You may use the following user specific variables in the content: {$a}';
