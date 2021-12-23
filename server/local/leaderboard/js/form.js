(function ($) {
    $(document).on('ready', function () {

        $('.settings-form select, .settings-form input:not([type="checkbox"])').change(function() {
            updatePluginConfig($(this).attr('name'), $(this).val());
        });

        $('.settings-form input[type="checkbox"]').change(function() {
            let value = 0;
            if ($(this).is(':checked')) {
                value = 1;
            }
            updatePluginConfig($(this).attr('name'), value);
        });

        function updatePluginConfig(field, value) {
            $.ajax({
                type: "POST",
                url: M.cfg.wwwroot + '/local/leaderboard/ajax.php',

                data: {
                    sesskey: M.cfg.sesskey,
                    field: field,
                    value: value
                },
                success: function(o) {
                    if (o.updated) {
                        notification(o.message, 'success');
                    } else {
                        notification(o.message, 'problem');
                    }
                }
            });
        }

        function notification(message, type) {
            require(["core/notification"], function (notification) {
                notification.addNotification({
                    message:message,
                    type: type
                });
            });
        }
    });
})(jQuery);