/* eslint-disable camelcase */
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

define(['jquery', 'core/modal_factory', 'core/modal_events'], function($, ModalFactory, ModalEvents) {
    return {
        init: function() {

            $('#region-main input:radio').change(function() {
                $(this).closest('form').submit();
            });

            $('#region-main input:checkbox').change(function() {
                $(this).closest('form').submit();
            });

            $('#region-main form').on('submit', function() {
                window.onbeforeunload = null;
            });

            $('a[data-action="deleteitem"]').on('click', function() {
                var $deleted = $(this);
                ModalFactory.create({
                    type: ModalFactory.types.CONFIRM,
                    body: M.util.get_string('deleteruleitemconfirm', 'assessment')
                })
                .then(function(modal) {
                    modal.getRoot().on(ModalEvents.yes, function() {
                        var selected_vals = [];
                        var $rule = $('#rule' + $deleted.data('ruleid'));
                        var $ruleitems = $rule.find('.ruleparamcontainer a[data-action="deleteitem"]');
                        $ruleitems.each(function(index, value) {
                            if ($(value).data('itemid') !== $deleted.data('itemid')) {
                                selected_vals.push($(value).data('itemid'));
                            }
                        });

                        $.ajax({
                            url: M.cfg.wwwroot + '/mod/assessment/ajax/editrule.php'
                                + '?sesskey=' + M.cfg.sesskey
                                + '&ruleid=' + $deleted.data('ruleid')
                                + '&versionid=' + $deleted.data('versionid')
                                + '&role=' + $deleted.data('role')
                                + '&value=' + selected_vals,
                            success: function() {
                                location.reload();
                            }
                        });
                    });
                    modal.show();
                });
            });

            $('a[data-action="delete"]').click(function() {
                var $deleted = $(this);
                ModalFactory.create({
                    type: ModalFactory.types.CONFIRM,
                    body: M.util.get_string('deleteruleconfirm', 'assessment')
                })
                .then(function(modal) {
                    modal.getRoot().on(ModalEvents.yes, function() {
                        $.ajax({
                            url: M.cfg.wwwroot + '/mod/assessment/ajax/deleterule.php'
                                + '?sesskey=' + M.cfg.sesskey
                                + '&ruleid=' + $deleted.data('ruleid'),
                            success: function() {
                                location.reload();
                            }
                        });
                    });
                    modal.show();
                });
            });
        }
    };
});
