/**
 * @copyright   City & Guilds Kineo 2017
 * @author      Steven Hughes <steven.hughes@kineo.com>
 */

M.mod_assessment_markreviewed = M.mod_assessment_markreviewed || {

    Y: null,

    // optional php params and defaults defined here, args passed to init method
    // below will override these values
    config: {},

    /**
     * module initialisation method called by php js_init_call()
     *
     * @param Y object    YUI instance
     * @param args string    args supplied in JSON format
     */
    init: function(Y, args) {
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
            throw new Error('M.mod_assessment_markreviewed.init()-> jQuery dependency required for this module.');
        }

        $('table#failedassessmentdashboard').delegate('a.markreview', 'click', function() {
            var button = $(this),
                attemptId = button.data('id');

            if (parseInt(attemptId) > 0) {

                $.ajax({
                    url: M.cfg.wwwroot + '/mod/assessment/markasreviewedajax.php',
                    method: 'POST',
                    data: {
                        'attemptid' : attemptId
                    }
                }).done(function(data) {
                    if (data.status === 'OK') {
                        button.text(data.message);
                        button.removeClass('markreview');
                        button.addClass('disabled');
                    } else {
                        button.text(M.util.get_string('button:markasreviewed', 'rb_source_faileddashboard') + ' (failed)')
                    }
                });
            }
        });
    }
};