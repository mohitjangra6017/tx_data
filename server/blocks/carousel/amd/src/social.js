define(['jquery', 'theme_roots/bootstrap'], function ($) {
    return {
        init: function (id) {
            var block_carousel_social = this;
            $('[data-toggle="dropdown"]').dropdown();

            $(document).on('click', id + '.block_carousel .carousel-like a', function (event) {
                block_carousel_social.ajax_like_unlike($(this));
                event.preventDefault();
            });

            $(document).on('submit', id + '.block_carousel .dropdown-menu form', function (event) {
                var $form = $(this);
                var block = $(id + '.block_carousel');
                var dropdownbutton = $form.parents('ul:first').siblings('.dropdown-toggle');

                // do not submit normally
                event.preventDefault();

                // disable the submit button
                var button = $form.find('.btn-primary');
                button.prop('disabled', true);

                var img = $('<img src="' + M.util.image_url('i/loading_small') + '" />');
                button.after(img);

                if (dropdownbutton.hasClass('send-message-btn')) {
                    setTimeout(function () {
                        img.remove();
                        button.prop('disabled', false);
                        $form.find('textarea').val('');
                    }, 500);
                }

                $.post($form.prop('action'), $form.serialize() + '&ajax=1', function (data) {
                    data = $.parseJSON(data);

                    if (dropdownbutton.hasClass('follow-button')) {
                        // set the class
                        var follow_id = data.follow_id;
                        if (data.followed) {
                            block.find('.followuser-' + follow_id).addClass('event-feed-selected').html('<i class="fa fa-users fa-lg"></i> Unfollow');
                        } else {
                            block.find('.followuser-' + follow_id).removeClass('event-feed-selected').html('<i class="fa fa-users fa-lg"></i> Follow');
                        }

                        var formparent = $form.parent();
                        formparent.empty().append(data.formcontent);

                        // set the following tab to stalled so it load the next time
                        var followingtab = block.find('#followingtab');
                        followingtab.addClass('stalled');

                    }
                });
            });
        },
        ajax_like_unlike: function (element) {
            $.ajax({
                url: element.attr('href'),
                dataType: 'json'
            }).done(function (json) {
                // check if query is latest
                if (json.success == true) {
                    element.parent('.carousel-like').toggleClass('liked');
                }
            });
        },

    };
});
