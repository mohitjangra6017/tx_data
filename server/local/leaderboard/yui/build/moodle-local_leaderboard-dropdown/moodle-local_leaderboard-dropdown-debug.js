YUI.add('moodle-local_leaderboard-dropdown', function (Y, NAME) {

/**
 * A module to manage dropdowns on the rule add/edit form.
 *
 * @module moodle-tool_monitor-dropdown
 */

/**
 * A module to manage dependent selects on the edit page.
 *
 * @since Moodle 2.8
 * @class moodle-tool_monitor.dropdown
 * @extends Base
 * @constructor
 */
function DropDown() {
    DropDown.superclass.constructor.apply(this, arguments);
}


var SELECTORS = {
    PLUGIN: '#id_plugin',
    EVENTNAME: '#id_eventname',
    OPTION: 'option',
    CHOOSE: 'option[value=""]',
    MOD_COMPLETIONS: '#id_mod_completions',
    COUNT: '#id_count',
    FREQUENCY: '#id_frequency',
    USE_GRADE: '#id_usegrade',
    SCORE: '#id_score'
};

var core = 'core';

var modCompletionEvent = '\\core\\event\\course_module_completion_updated';

var gradeEvents = [
    '\\mod_quiz\\event\\attempt_submitted',
    '\\mod_assign\\event\\submission_graded',
    '\\mod_lesson\\event\\essay_assessed',
    '\\mod_scorm\\event\\status_submitted',
];

Y.extend(DropDown, Y.Base, {

    /**
     * Reference to the plugin node.
     *
     * @property plugin
     * @type Object
     * @default null
     * @protected
     */
    plugin: null,

    /**
     * Reference to the plugin node.
     *
     * @property eventname
     * @type Object
     * @default null
     * @protected
     */
    eventname: null,

    /**
     * Reference to the course module completion node
     *
     * @property mod_completions
     * @default null
     * @protected
     */
    modCompletions: null,

    /**
     * Initializer.
     * Basic setup and delegations.
     *
     * @method initializer
     */
    initializer: function() {
        this.plugin = Y.one(SELECTORS.PLUGIN);
        this.eventname = Y.one(SELECTORS.EVENTNAME);
        this.modCompletions = Y.one(SELECTORS.MOD_COMPLETIONS);
        this.awardCount = Y.one(SELECTORS.COUNT);
        this.awardFrequency = Y.one(SELECTORS.FREQUENCY);
        this.useGrade = Y.one(SELECTORS.USE_GRADE);
        this.score = Y.one(SELECTORS.SCORE);
        var selection = this.eventname.get('value'); // Get selected event name.
        if (this.eventname.get('value') === modCompletionEvent) {
            this.awardFrequency.set('disabled', true);
            this.awardCount.set('value', '');
            this.awardCount.set('disabled', true);
        }
        this.updateEventsList();
        this.updateSelection(selection, this.eventname);
        this.plugin.on('change', this.updateEventsList, this);
        this.eventname.on('change', this.handleEventSelected.bind(this));
        this.useGrade.on('change', this.handleUseGradeUpdated.bind(this));
        if (this.modCompletions !== null){
            this.modCompletions.on('change', this.setModuleCompletionEvent.bind(this));
        }
    },

    /**
     * Remove score from form if usegrades selected
     */
    handleUseGradeUpdated: function() {
        if (this.useGrade.get('checked') === true) {
            this.score.set('value', '');
        }
    },

    /**
     * Method to handle form elements when certain events are selected
     */
    handleEventSelected: function() {
        if (this.eventname.get('value') === modCompletionEvent) {
            this.awardCount.set('value', '');
            this.awardCount.set('disabled', true);
            this.awardFrequency.set('disabled', true);
        } else {
            this.awardCount.set('disabled', false);
            this.awardFrequency.set('disabled', false);
        }

        if (gradeEvents.includes(this.eventname.get('value'))) {
            this.useGrade.set('disabled', false);
        } else {
            this.useGrade.set('disabled', true);
        }
    },

    /**
     * Method to set the plugin and event to activity completion when modCompletions checkbox set
     */
    setModuleCompletionEvent: function() {
        if (this.modCompletions.get('checked') === true) {
            this.updateSelection(core, this.plugin);
            this.updateEventsList(this.eventname.get('value'), this.eventname);
            this.updateSelection(modCompletionEvent, this.eventname);
            this.plugin.set('disabled', true);
            this.eventname.set('disabled', true);
            this.score.set('disabled', false);
        } else {
            this.plugin.set('disabled', false);
            this.eventname.set('disabled', false);
            this.awardCount.set('disabled', false);
            this.awardFrequency.set('disabled', false);
            this.updateSelection('', this.plugin);
            this.updateEventsList(this.eventname.get('value'), this.eventname);
        }
    },

    /**
     * Method to update the events list drop down when plugin list drop down is changed.
     *
     * @method updateEventsList
     */
    updateEventsList: function() {
        var node, options, choosenode;
        var plugin = this.plugin.get('value'); // Get component name.
        var namespace = '\\' + plugin + '\\';
        this.eventname.all(SELECTORS.OPTION).remove(true); // Delete all nodes.
        options = this.get('eventlist');

        // Mark the default choose node as visible and selected.
        choosenode = Y.Node.create('<option value="">' + options[''] + '</option>');
        choosenode.set('selected', 'selected');
        this.eventname.appendChild(choosenode);

        Y.Object.each(options, function(value, key) {
            // Make sure we highlight only nodes with correct namespace.
            if (key.substring(0, namespace.length) === namespace) {
                node = Y.Node.create('<option value="' + key + '">' + value + '</option>');
                this.eventname.appendChild(node);
            }
        }, this);

    },

    /**
     * Method to update the selected node from the options list.
     *
     * @method updateSelection
     * @param {string} selection The options node value that should be selected.
     * @param {node} type selector
     */
    updateSelection: function(selection, type) {
        type.get('options').each(function(opt) {
            if (opt.get('value') === selection) {
                opt.set('selected', 'selected');
            }
        }, this);
        type.simulate('change');
    }
}, {
    NAME: 'dropDown',
    ATTRS: {
        /**
         * A list of events and plugins with components.
         *
         * @attribute mixed
         * @default null
         * @type Object
         */
        eventlist: null,
        pluginlist: null
    }
});

Y.namespace('M.local_leaderboard.DropDown').init = function(config) {
    return new DropDown(config);
};

}, '@VERSION@', {"requires": ["base", "event", "node", "node-event-simulate"]});
