define(['jquery', 'core/notification', 'core/str', 'core/templates', 'totara_form/form'], function($, notification, str, templates, Form) {
    return {
        initialize : function() {

            var $buttonclicked;
            var formsubmitted = false;
            var formupdated = false;
            var $form = $('#tf_fid_mod_assessment_form_page');

            $form.attr('name', 'active_assessment_form');
            $form.find('.tf_element_input input').change(function(e) {
                formupdated = true;
            });

            $form.find("input[type=submit]").click(function(e) {
                if ("save" !== $(this).attr("name") && !$form[0].checkValidity()) {
                    var strings =[
                        {key: 'error:missingrequired', component: 'mod_assessment'},
                    ];
                    str.get_strings(strings).then(function (strings) {
                        var context = { message: strings[0], announce: true, closebutton: true };
                        templates.render('core/notification_error', context)
                            .then(function(html, js) {
                                if ($form.find(':first-child').hasClass('alert')) {
                                    return;
                                }
                                $form.prepend(html);
                            }).fail(notification.exception);
                    });
                } else {
                    $form.find('input').removeAttr('required');
                }
                $buttonclicked = $(this);
            });

            $form.submit(function(e) {
                if ($buttonclicked.attr('name') != 'cancel') {
                    formsubmitted = true;
                }
            });

            window.onbeforeunload = function (e) {
                if (formupdated && !formsubmitted) {
                    return true;
                }
            };
        }
    };
});
