<?php

/**
 * Comment
 *
 * @package    block
 * @subpackage awesome
 * @copyright  &copy; 2017 CG Kineo {@link http://www.kineo.com}
 * @author     kaushtuv.gurung
 * @version    1.0
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Awesome';
$string['awesome'] = 'Awesome';
$string['awesome:addinstance'] = 'Add a new Awesome block';
$string['awesome:myaddinstance'] = 'Add a new Awesome block to My Learning';

// global settings
$string['global_defaulttemplate'] = 'Global default template';
$string['global_defaulttemplatedesc'] = 'Sets the default block template globally. This setting can be overwritten by the block instance setting.';
$string['global_defaultbackgroundcolour'] = 'Global block background colour';
$string['global_defaultbackgroundcolourdesc'] = 'Sets the global default block background colour. This setting can be overwritten by the block instance setting.';
$string['global_defaultheadertextcolour'] = 'Global header colour';
$string['global_defaultheadertextcolourdesc'] = 'Global header text colour. Enter a hex value. e.g. #333333;';
$string['global_defaulttextcolour'] = 'Global block text colour';
$string['global_defaulttextcolourdesc'] = 'Sets the global default block text colour. This setting can be overwritten by the block instance setting.';
$string['global_defaultlinktext'] = 'Global default link text';
$string['global_defaultlinktextdesc'] = 'Sets the default block link text. This setting can be overwritten by the block instance setting.';
$string['global_defaulticoncolour'] = 'Global default icon colour';
$string['global_defaulticoncolourdesc'] = 'Sets the default icon colour. This setting can be overwritten by the block instance setting.';
$string['global_defaulticonbackgroundcolour'] = 'Global default icon background colour';
$string['global_defaulticonbackgroundcolourdesc'] = 'Sets the global default icon background colour. This setting can be overwritten by the block instance setting.';

// settings form
// Depreciated
$string['whattodisplay'] = 'What to display';
$string['displayname'] = 'Display name';
$string['displayname_help'] = 'Add a custom display name for the block. This will only be visible while the editing is set to on. The default is Awesome.';

// configs
$string['selectone'] = 'Select one';
$string['template'] = 'Template';
$string['template_help'] = '<table class="block-awesome-template-table"><tbody><tr><td>Template 1</td><td><img src="/blocks/awesome/pix/templates/template1.jpg"></td></tr><tr><td>Template 2</td><td><img src="/blocks/awesome/pix/templates/template2.jpg"></td></tr><tr><td>Template 3</td><td><img src="/blocks/awesome/pix/templates/template3.jpg"></td></tr><tr><td>Template 4</td><td><img src="/blocks/awesome/pix/templates/template4.jpg"></td></tr></tbody></table>';
$string['templateno'] = 'Template {$a}';
$string['linktext'] = 'Link text';
$string['url'] = 'Link URL';
$string['url_help'] = 'If the URL field is left empty, the Link text will not be displayed.';
$string['image'] = 'Block image';
$string['hideimage'] = 'Hide image';
$string['headertextcolour'] = 'Header colour';
$string['headertextcolour_help'] = 'Header text colour. Enter a hex value. e.g. #333333;';

// Template specific settings
$string['contentsettings'] = 'Content settings';
$string['header'] = 'Header text';
$string['header_help'] = 'Header text. On some templates, this field will not be visible.';
$string['subheader'] = 'Sub-header text';
$string['subheader_help'] = 'Sub-header text. On some templates, this field will not be visible.';
$string['subheaderurl'] = 'Sub-header link url';
$string['subheaderurl_help'] = 'Sub-header link url. This cannot be configured if "URL Covering whole block" is enabled. On some templates, this field will not be visible.';
$string['content'] = 'Content text';
$string['content_help'] = 'Content text. On some templates, this field will not be visible.';
$string['faicon'] = 'Fontawesome icon';
$string['faicon_help'] = 'If an icon is added (e.g. enter "rebel"). This will appear beside the content in some templates. Find the complete list of icons <a href="http://fontawesome.io/icons/" target="_blank">Here</a>';
$string['textcolour'] = 'Text colour';
$string['textcolour_help'] = 'Text colour. Enter a hex value. e.g. #333333;';
$string['iconcolour'] = 'Icon colour.';
$string['iconcolour_help'] = 'The default value is white (#ffffff).';
$string['iconbackgroundcolour'] = 'Icon background colour.';
$string['iconbackgroundcolour_help'] = 'The default value is transparent.';
$string['backgroundcolour'] = 'Background colour';
$string['backgroundcolour_help'] = 'Background colour. Enter a hex value. e.g. #ffffff;';
$string['responsiveimage'] = 'Responsive image';
$string['responsiveimage_help'] = 'Make the image resonsive (100%) for some templates, e.g. Template 4 can be used as a static banner by turning this on.';
$string['largeicon'] = 'Large icon';
$string['largeicon_help'] = 'Make the FontAwesome icon larger. This is only applicable if the template has a FontAwesome icon.';
$string['newtab'] = 'Open link in a new tab';

// Dummy text
$string['dummyheader'] = 'Duis aute irure';
$string['dummycontent'] = 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.';
// KINSST-76
$string['stylepanel'] = 'Block Styles';
$string['subheadertextcolour'] = 'Sub-header colour';
$string['subheadertextcolour_help'] = 'Sub-header text colour. Enter a hex value. e.g. #333333;';
// Font size
$string['headerfontsize'] = 'Header font size';
$string['subheaderfontsize'] = 'Sub-header font size';
$string['contentfontsize'] = 'Content font size';
$string['headerfontweight'] = 'Header font weight';
$string['subheaderfontweight'] = 'Sub-header font weight';
$string['contentfontweight'] = 'Content font weight';
// ICAHAS-26
$string['linkfontsize'] = 'Link font size';
$string['linkfontweight'] = 'Link font weight';
$string['linkcolour'] = 'Link colour';
$string['linkcolour_help'] = 'Link colour. Enter a hex value. e.g. #333333;';
// ICAHAS-26
$string['btnnochevron'] = 'Remove chevron from button';
// KINSST-84
$string['headerbgcolour'] = 'Header background colour';
$string['headerbgcolour_help'] = 'Background colour of the header.';
$string['clone'] = 'Clone this block';
// PETHAS-41
$string['header_textalign'] = 'Header text alignment';
$string['subheader_textalign'] = 'SubHeader text alignment';
$string['content_textalign'] = 'Content text alignment';
$string['left'] = 'Left';
$string['center'] = 'Center';
$string['right'] = 'Right';

// HOMHAS-271
$string['urlcoveringblock'] = 'URL Covering whole block';

// IHCHAS-220
$string['blockborderraidus'] = 'Block border radius';
$string['blockbordercolor'] = 'Block border colour';
$string['buttonalign'] = 'Block button alignment';

$string['duplicate:success'] = 'Block duplicated';
$string['duplicate:fail'] = 'You do not have permission to duplicate this block';

