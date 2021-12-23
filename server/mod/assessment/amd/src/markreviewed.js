define(['jquery'], function($) {
    return {
        initialize : function() {
            $('a[data-action="markreviewed"]').click(function(event){
                event.preventDefault();
                var $button = $(this);
                $button.removeClass('btn-primary');

                $.ajax({
                    url: M.cfg.wwwroot + '/mod/assessment/ajax/markasreviewed.php',
                    data: { attemptid: $button.data('attemptid'), sesskey: M.cfg.sesskey },
                    success: function(data) {
                        $button.html(data.message);
                        if (data.status == 'OK') {
                            $button.addClass('btn-success');
                            $button.delay(600).fadeOut();
                        } else {
                            $button.addClass('btn-danger');
                        }
                    }
                });
            });
        }
    };
});
