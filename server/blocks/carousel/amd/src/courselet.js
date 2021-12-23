define(['jquery', 'core/modal_factory'], function($, ModalFactory) {
    return {
        init: function() {
            var courselet = this;
            $('button.carousel-launch-course').on('click', function(e) {
                var clickedLink = $(e.currentTarget);
                var courseid = clickedLink.data('courseid');
                ModalFactory.create({
                    body: '<i class="fa fa-spinner fa-spin"></i>'
                })
                .then(function(modal) {
                    var root = modal.getRoot();
                    modal.show();
                    root.addClass('courselet-modal');
                    courselet.get_courselet(courseid, root);
                });
            });
        },

        get_courselet: function(courseid, modal) {
            $.ajax({
                method: "POST",
                url: M.cfg.wwwroot + '/blocks/carousel/ajax/process_courselet.php',
                dataType: 'JSON',
                data: {courseid: courseid}
            })
            .done(function(data) {
                modal.find('.modal-body').html(data.html);
            });
        }
    };
});