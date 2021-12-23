define(['jquery', 'core/templates', 'core/modal_factory', 'block_carousel/modal_cohort', 'block_carousel/select2'],
function($, Templates, ModalFactory, ModalCurated, Select2) {

    return {

        blockid: null,

        /**
         * Init carousels
         *
         */
        init: function(blockid) {
            var cohort = this;
            cohort.blockid = blockid;
            cohort.init_modal();
            $(document).on('click', '#btn-cohort-config-trigger', function() {
                cohort.add_cohort_search_fields();
            });
            $(document).on('click', '#save-cohort', function() {
                cohort.save_cohort();
            });
        },

        init_modal: function() {
            // Button defined in
            // edit_forms/cohort_content_settings.php
            var trigger = $('#btn-cohort-config-trigger');

            ModalFactory.create({type: ModalCurated.TYPE}, trigger)
            .done(function(modal) {
                var root = modal.getRoot();
                root.addClass('carousel-cohort-modal');
            });
        },

        add_cohort_search_fields: function() {
            var cohort = this;
            $.ajax({
                method: "POST",
                url: M.cfg.wwwroot + '/blocks/carousel/ajax/cohort_filters.php',
                dataType: 'JSON',
                data: {blockid: cohort.blockid}
            })
            .done(function(data) {
                $('#cohort-filters').html(data.html);
                Select2.init();
            });
        },

        save_cohort: function() {
            var btn_default_value = $('#save-cohort').html();
            $('#save-cohort').html('Saving...');
            $.ajax({
                method: "POST",
                url: M.cfg.wwwroot + '/blocks/carousel/ajax/save_cohort.php',
                dataType: 'JSON',
                data: $('.cohort-configuration-from').serialize()
            })
            .done(function() {
                $('#save-cohort').html('Saved');
                setTimeout(function() {
                    $('#save-cohort').html(btn_default_value);
                }, 3000);
            });
        }


    };
});