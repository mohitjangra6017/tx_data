define(['jquery', 'core/notification', 'core/custom_interaction_events', 'core/modal', 'core/modal_registry'],
    function ($, Notification, CustomEvents, Modal, ModalRegistry) {

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
        var ModalCurated = function (root) {
            Modal.call(this, root);

            if (!this.getFooter().find(SELECTORS.SAVE_BUTTON).length) {
                Notification.exception({message: 'No save button found'});
            }

            if (!this.getFooter().find(SELECTORS.CANCEL_BUTTON).length) {
                Notification.exception({message: 'No cancel button found'});
            }
        };

        ModalCurated.TYPE = 'block_carousel-ModalCurated';
        ModalCurated.prototype = Object.create(Modal.prototype);
        ModalCurated.prototype.constructor = ModalCurated;

        /**
         * Set up all of the event handling for the modal.
         *
         * @method registerEventListeners
         */
        ModalCurated.prototype.registerEventListeners = function () {
            // Apply parent event listeners.
            Modal.prototype.registerEventListeners.call(this);

            this.getModal().on(CustomEvents.events.activate, SELECTORS.CANCEL_BUTTON, function () {
                this.hide();
            }.bind(this));
        };

        // Automatically register with the modal registry the first time this module is imported so that you can create modals
        // of this type using the modal factory.
        if (!registered) {
            ModalRegistry.register(ModalCurated.TYPE, ModalCurated, 'block_carousel/__config_modal_curated');
            registered = true;
        }

        return ModalCurated;
    });