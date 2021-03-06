/**
 * Provides the Moodle Calendar class.
 *
 * @module moodle-form-dateselector
 */

/**
 * A class to overwrite the YUI3 Calendar in order to change the strings..
 *
 * @class M.form_moodlecalendar
 * @constructor
 * @extends Calendar
 */
var MOODLECALENDAR = function() {
    MOODLECALENDAR.superclass.constructor.apply(this, arguments);
};

Y.extend(MOODLECALENDAR, Y.Calendar, {
        initializer: function(cfg) {
            this.set("strings.very_short_weekdays", cfg.WEEKDAYS_MEDIUM);
            this.set("strings.first_weekday", cfg.firstdayofweek);

            // Totara: TL-14954 Use a custom headerRenderer to display the translated month names
            this.set("strings.long_months", cfg.MONTHS_LONG);
            this.set('headerRenderer', function(obj, baseDate) {
                var monthnames = this.get("strings.long_months");
                var m = obj.getMonth();
                return monthnames[m];
            });
        }
    }, {
        NAME: 'Calendar',
        ATTRS: {}
    }
);

M.form_moodlecalendar = M.form_moodlecalendar || {};
M.form_moodlecalendar.initializer = function(params) {
    return new MOODLECALENDAR(params);
};
