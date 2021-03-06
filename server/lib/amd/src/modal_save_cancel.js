// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Contain the logic for the save/cancel modal.
 *
 * @module     core/modal_save_cancel
 * @class      modal_save_cancel
 * @package    core
 * @copyright  2016 Ryan Wyllie <ryan@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(['jquery', 'core/notification', 'core/custom_interaction_events', 'core/modal', 'core/modal_events'],
        function($, Notification, CustomEvents, Modal, ModalEvents) {

    var SELECTORS = {
        SAVE_BUTTON: '[data-action="save"]',
        CANCEL_BUTTON: '[data-action="cancel"]',
    };

    /**
     * Constructor for the Modal.
     *
     * @param {object} root The root jQuery element for the modal
     */
    var ModalSaveCancel = function(root) {
        Modal.call(this, root);

        if (!this.getFooter().find(SELECTORS.SAVE_BUTTON).length) {
            Notification.exception({message: 'No save button found'});
        }

        if (!this.getFooter().find(SELECTORS.CANCEL_BUTTON).length) {
            Notification.exception({message: 'No cancel button found'});
        }
    };

    ModalSaveCancel.prototype = Object.create(Modal.prototype);
    ModalSaveCancel.prototype.constructor = ModalSaveCancel;

    /**
     * Override parent implementation to prevent changing the footer content.
     */
    ModalSaveCancel.prototype.setFooter = function() {
        Notification.exception({message: 'Can not change the footer of a save cancel modal'});
        return;
    };

    /**
     * Disable the save button.
     */
    ModalSaveCancel.prototype.disableSave = function() {
        this.getFooter().find(SELECTORS.SAVE_BUTTON).prop('disabled', true);
    };

    /**
     * Enable the save button.
     */
    ModalSaveCancel.prototype.enableSave = function() {
        this.getFooter().find(SELECTORS.SAVE_BUTTON).prop('disabled', false);
    };

    /**
     * Set up all of the event handling for the modal.
     *
     * @method registerEventListeners
     */
    ModalSaveCancel.prototype.registerEventListeners = function() {
        // Apply parent event listeners.
        Modal.prototype.registerEventListeners.call(this);

        this.getModal().on(CustomEvents.events.activate, SELECTORS.SAVE_BUTTON, function(e, data) {
            var saveEvent = $.Event(ModalEvents.save);
            this.getRoot().trigger(saveEvent, this);

            if (!saveEvent.isDefaultPrevented()) {
                this.hide();
                data.originalEvent.preventDefault();
            }
        }.bind(this));

        this.getModal().on(CustomEvents.events.activate, SELECTORS.CANCEL_BUTTON, function(e, data) {
            var cancelEvent = $.Event(ModalEvents.cancel);
            this.getRoot().trigger(cancelEvent, this);

            if (!cancelEvent.isDefaultPrevented()) {
                this.hide();
                data.originalEvent.preventDefault();
            }
        }.bind(this));
    };

    return ModalSaveCancel;
});
