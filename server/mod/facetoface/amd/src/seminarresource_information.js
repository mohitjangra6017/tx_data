/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2021 onwards Totara Learning Solutions LTD
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
 * @author Tatsuhiro Kirihara <tatsuhiro.kirihara@totaralearning.com>
 * @package mod_facetoface
 */

define(['core/config'], function(cfg) {
    /**
     * Internal class to handle the action button
     * @constructor
     * @param {HTMLElement} element
     */
    function SeminarResourceInformation(element) {
        this.action = element.querySelector('.mod_facetoface__resource-card__notification a.action');
    }

    SeminarResourceInformation.prototype = {
        /** @type {HTMLElement} The parent element */
        element: null,

        /** @type {HTMLAnchorElement} An action link */
        action: null,

        /** @type {HTMLFormElement} An action link */
        form: null,

        /**
         * Sets up the action event
         */
        setupEvents: function() {
            var that = this;

            this.action.classList.remove('action');
            this.action.classList.add('mod_facetoface__resource-card__notification-action');
            this.action.setAttribute('role', 'button');
            this.action.setAttribute('href', '#');
            this.action.addEventListener('click', function(e) {
                e.preventDefault();
                if (!that.form) {
                    var form = document.createElement('form');
                    form.method = 'POST';
                    var sesskey = document.createElement('input');
                    sesskey.type = 'hidden';
                    sesskey.name = 'sesskey';
                    sesskey.value = cfg.sesskey;
                    form.appendChild(sesskey);
                    var action = document.createElement('input');
                    action.type = 'hidden';
                    action.name = 'action';
                    action.value = '1';
                    form.appendChild(action);
                    document.body.appendChild(form);
                    that.form = form;
                }
                that.form.submit();
            });
        }
    };

    /**
     * Initialise our widget.
     * @param {HTMLElement} element
     * @return {Promise} resolved once initialisation is complete
     */
    function init(element) {
        return new Promise(function(resolve) {
            var controller = new SeminarResourceInformation(element);
            controller.setupEvents();
            resolve(controller);
        });
    }

    return {
        init: init
    };
});
