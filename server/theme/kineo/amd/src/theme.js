define([], function() {
    return {
        init: function() {

            var params = {
                header: document.querySelector('.totaraNav'),
                paddingOffset: 10,
                paddingOffsetMeasurement: "px"
            };

            if (!this.elementExists(params.header) || this.getElementStyle(params.header, "position") !== "fixed") {
                return false;
            }

            this.setBodyPadding(params.header, params.paddingOffset, params.paddingOffsetMeasurement);

            window.addEventListener('resize', function() {
                this.setBodyPadding(params.header, params.paddingOffset, params.paddingOffsetMeasurement);
            }.bind(this));

        },

        elementExists: function(el) {
            if (typeof el !== 'undefined' && el !== null) {
                return true;
            }
            return false;
        },

        getElementStyle: function(el, prop) {
            return window.getComputedStyle(el).getPropertyValue(prop);
        },

        setBodyPadding: function(headerElement, paddingOffset, offsetMeasurement) {
            var headerHeight = parseInt(this.getElementStyle(headerElement, "height"));
            var offset = document.querySelector('body').classList.contains('path-login') ? 0 : parseInt(paddingOffset);
            var measurement = offsetMeasurement.toString();
            
            document.querySelector('body').setAttribute("style", "padding-top: " + (headerHeight + offset) + measurement);
        }

    };
});