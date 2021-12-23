define(['jquery', 'core/notification', 'core/custom_interaction_events', 'core/modal', 'core/modal_registry'],
    function($, Notification, CustomEvents, Modal, ModalRegistry) {

        var registered = false;
        var SELECTORS = {
            SAVE_BUTTON: '[data-action="save"]',
            CANCEL_BUTTON: '[data-action="cancel"]',
        };

        /**
         * Constructor for the Modal.
         *
         * @param {object} root The root jQuery element for the modal
         */
        var ModalCohort = function(root) {
            Modal.call(this, root);

            if (!this.getFooter().find(SELECTORS.SAVE_BUTTON).length) {
                Notification.exception({message: 'No save button found'});
            }

            if (!this.getFooter().find(SELECTORS.CANCEL_BUTTON).length) {
                Notification.exception({message: 'No cancel button found'});
            }
        };

        ModalCohort.TYPE = 'block_carousel-ModalCohort';
        ModalCohort.prototype = Object.create(Modal.prototype);
        ModalCohort.prototype.constructor = ModalCohort;

        /**
         * Set up all of the event handling for the modal.
         *
         * @method registerEventListeners
         */
        ModalCohort.prototype.registerEventListeners = function() {
            // Apply parent event listeners.
            Modal.prototype.registerEventListeners.call(this);

            this.getModal().on(CustomEvents.events.activate, SELECTORS.CANCEL_BUTTON, function() {
                this.hide();
            }.bind(this));
        };

        // Automatically register with the modal registry the first time this module is imported so that you can create modals
        // of this type using the modal factory.
        if (!registered) {
            ModalRegistry.register(ModalCohort.TYPE, ModalCohort, 'block_carousel/__config_modal_cohort');
            registered = true;
        }

        return ModalCohort;
    });