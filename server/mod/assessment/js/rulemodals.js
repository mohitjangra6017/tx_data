/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package totara
 * @subpackage mod_assessment
 */

/**
 * This file contains the Javascript for the dialog that lets you add cohorts
 * to a course
 */

M.mod_assessment_rulemodals = M.mod_assessment_rulemodals || {

    Y: null,

    // Optional php params and defaults defined here, args passed to init method.
    // Below will override these values.
    config: {},

    /**
     * Module initialisation method called by php js_init_call()
     *
     * @param object    YUI instance
     * @param string    args supplied in JSON format
     */
    init: function(Y, args) {
        // Save a reference to the Y instance (all of its dependencies included).
        this.Y = Y;

        // If defined, parse args into this module's config object.
        if (args) {
            var jargs = Y.JSON.parse(args);
            for (var a in jargs) {
                if (Y.Object.owns(jargs, a)) {
                    this.config[a] = jargs[a];
                }
            }
        }

        // Check jQuery dependency is available.
        if (typeof $ === 'undefined') {
            throw new Error('M.totara_cohortlearning.init()-> jQuery dependency required for this module.');
        }

        this.config['sesskey'] = M.cfg.sesskey;

        this.init_dialogs();
    },


    init_dialogs: function() {

        var handler = new totaraDialog_handler_assessment_role_rule();
        var url = M.cfg.wwwroot + '/mod/assessment/dialog/editrule.php';

        var sesskey = this.config.sesskey;
        var versionid = this.config.versionid;
        var role = this.config.role;

        // Create dialogs from options.
        $('#id_addrulesetmenu option').each(function(index, option) {
            var type = ($(option).val());
            // KTS-508 START - remove  handler for 'Choose' option
            if (type == "") {
                return true;
            }
            // KTS-508 END.
            var typehandler = jQuery.extend(true, {}, handler);
            var typebuttons = {};
            typebuttons[M.util.get_string('save', 'totara_core')] = function() { typehandler.submit(); };
            typebuttons[M.util.get_string('cancel', 'moodle')] = function() { typehandler.cancel(); };

            totaraDialogs['rulemodal_' + type] = new totaraDialog(
                'rulemodal_role',
                null,
                {
                    buttons: typebuttons,
                    title: '<h2>' + M.util.get_string('addrule', 'assessment') + '</h2>'
                },
                url + "?type=" + type
                    + '&versionid=' + versionid
                    + '&role=' + role
                    + '&sesskey=' + sesskey,
                typehandler
            );
        });

        // Open the modals.
        $(document).on('change', '.ruleselector', function(event) {
            event.preventDefault();

            var type = $(this).val();
            var rulesetid = $(this).data('rulesetid');
            $(this).val('');

            totaraDialogs['rulemodal_' + rulesetid + type] = totaraDialogs['rulemodal_' + type];
            totaraDialogs['rulemodal_' + rulesetid + type].default_url += '&rulesetid=' + $(this).data('rulesetid');
            totaraDialogs['rulemodal_' + rulesetid + type].open();
        });

        // Open existing rules for edit.
        $('a.action-icon[data-action="edit"]').click(function(event) {
            event.preventDefault();

            var type = $(this).data('type');
            var ruleid = $(this).data('ruleid');
            totaraDialogs['rulemodal_' + type + ruleid] = totaraDialogs['rulemodal_' + type];
            totaraDialogs['rulemodal_' + type + ruleid].default_url += '&ruleid=' + ruleid;
            totaraDialogs['rulemodal_' + type + ruleid].open();
        });
    }
};

totaraDialog_handler_assessment_role_rule = function() {
    // Base url.
    this.baseurl = '';

    this.submit = function() {
        var elements = $('.selected > div > span', this._container);
        var selected = new Array();
        $select = $('#id_dialogselectval');
        if ($select.size()) {
            selected.push($select.val());
        } else {
            selected = this._get_ids(elements);
        }

        var operator = $('#id_operator').val();
        var ruleid = $('.extradata').data('ruleid');
        var rulesetid = $('.extradata').data('rulesetid');
        var type = $('.extradata').data('type');
        var versionid = $('.extradata').data('versionid');
        var role = $('.extradata').data('role');

        if (selected.length > 0) {
            var selected_vals = selected.join(',');
            var url = M.cfg.wwwroot + '/mod/assessment/ajax/editrule.php'
                + '?sesskey=' + M.cfg.sesskey
                + '&ruleid=' + ruleid
                + '&rulesetid=' + rulesetid
                + '&type=' + type
                + '&operator=' + operator
                + '&versionid=' + versionid
                + '&role=' + role
                + '&value=' + selected_vals;
            $.ajax({
                url: url,
                success: function() {
                    window.onbeforeunload = null;
                    $('#region-main form.mform').submit();
                }
            });
        }

        this._dialog.hide();
    };

    this.cancel = function() {
        window.onbeforeunload = null;   // We didn't change anything, but the browser seems to think so.
        this._cancel();
    };
};
totaraDialog_handler_assessment_role_rule.prototype = new totaraDialog_handler_treeview_multiselect();
