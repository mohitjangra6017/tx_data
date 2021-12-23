define(['jquery'], function($) {
    return {
        initialize: function() {
            this.togglePenalty();

            if ($) {
                $('#id_config_choicetype').change(this.togglePenalty);
            }
        },
        togglePenalty: function() {
            var $typeselect = $('#id_config_choicetype');
            if ($typeselect.val() == 1) {
                $('#fitem_id_responses input.penalty').prop('readonly', true);
            } else {
                $('#fitem_id_responses input.penalty').prop('readonly', false);
            }
        },
    };
});
