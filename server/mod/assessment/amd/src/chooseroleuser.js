/**
 * @copyright 2017-2019 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @author Alex Glover <alex.glover@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */
define(['jquery'], function($) {
    var chooseroleuser = {
        init: function(activetab) {
            if (activetab == 'browse') {
                $('#search-tab').hide();
            } else {
                $('#browse-tab').hide();
            }

            var $selected = $('.selectedroleuser');

            $('a[href="#browse-tab"]').click(function(e) {
                e.preventDefault();
                $('#browse-tab').show();
                $('#search-tab').hide();
            });
            $('a[href="#search-tab"]').click(function(e) {
                e.preventDefault();
                $('#browse-tab').hide();
                $('#search-tab').show();
            });

            $('.treeview .clickable').click(function(e){
                e.preventDefault();

                var selected = $(this).attr('id').substr(5);
                $selected.html($(this).find('a').html());

                $('#tf_fid_mod_assessment_form_chooseroleuser input[name="selected"]').val(selected);
            });
        }
    };

    return chooseroleuser;
});
