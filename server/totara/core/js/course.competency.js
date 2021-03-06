/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2010 onwards Totara Learning Solutions LTD
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Simon Coggins <simon.coggins@totaralms.com>
 * @author Eugene Venter <eugene@catalyst.net.nz>
 * @author Aaron Barnes <aaron.barnes@totaralms.com>
 * @author Dave Wallace <dave.wallace@kineo.co.nz>
 * @package totara
 * @subpackage totara_core
 */
M.totara_coursecompetency = M.totara_coursecompetency || {

    Y: null,
    // optional php params and defaults defined here, args passed to init method
    // below will override these values
    config: {
        id:0
    },

    /**
     * module initialisation method called by php js_init_call()
     *
     * @param object    YUI instance
     * @param string    args supplied in JSON format
     */
    init: function(Y, args){

        var module = this;

        // save a reference to the Y instance (all of its dependencies included)
        this.Y = Y;

        // if defined, parse args into this module's config object
        if (args) {
            var jargs = Y.JSON.parse(args);
            for (var a in jargs) {
                if (Y.Object.owns(jargs, a)) {
                    this.config[a] = jargs[a];
                }
            }
        }

        // check jQuery dependency is available
        if (typeof $ === 'undefined') {
            throw new Error('M.totara_coursecompetency.init()-> jQuery dependency required for this module to function.');
        }

        ///
        /// non resource-level dialog
        ///
        // Create handler for the dialog
        totaraDialog_handler_courseEvidence = function() {
            // Base url
            var baseurl = '';
        };

        totaraDialog_handler_courseEvidence.prototype = new totaraDialog_handler_treeview_multiselect();

        /**
         * Add a row to a table on the calling page
         * Also hides the dialog and any no item notice
         *
         * @param string    HTML response
         * @return void
         */
        totaraDialog_handler_courseEvidence.prototype._update = function(response) {

            // Hide dialog
            this._dialog.hide();

            // Remove no item warning (if exists)
            $('.noitems-'+this._title).remove();

            //Split response into table and div
            var new_table = $(response).filter('table');

            // Grab table
            var table = $('table#list-coursecompetency');

            // If table found
            if (table.length) {
                table.replaceWith(new_table);
            }
            else {
                // Add new table
                $('div#coursecompetency-table-container').append(new_table);
            }
        };

        (function() {
            var url = M.cfg.wwwroot+'/totara/hierarchy/prefix/competency/course/';
            var saveurl = url + 'save.php?sesskey='+M.cfg.sesskey+'&course='+module.config.id+'&type=coursecompletion&instance='+module.config.id+'&deleteexisting=1&update=';

            var handler = new totaraDialog_handler_courseEvidence();
            handler.baseurl = url;

            var buttonsObj = {};
            buttonsObj[M.util.get_string('save', 'totara_core')] = function() { handler._save(saveurl) };
            buttonsObj[M.util.get_string('cancel', 'moodle')] = function() { handler._cancel() };

            totaraDialogs['evidence'] = new totaraDialog(
                'coursecompetency',
                'show-coursecompetency-dialog',
                {
                    buttons: buttonsObj,
                    title: '<h2>'+M.util.get_string('assigncoursecompletiontocompetencies', 'totara_hierarchy')+'</h2>'
                },
                url+'add.php?sesskey='+M.cfg.sesskey+'&id='+module.config.id,
                handler
            );
        })();

        // TODO SCANMSG: when this component is rewritten as a component action
        // select, the following fix will need to be applied to re-assign
        // Moodle auto-submission. Until then, inline jQuery onChange does the
        // work.
    }
};
