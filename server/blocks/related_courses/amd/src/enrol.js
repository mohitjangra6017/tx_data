define(
    ["jquery", "core/ajax", "core/notification", "core/str"],
    function ($, ajax, notification, str) {
        var module = {};

        module.initialise = function (instanceid, callback) {
            var block = $('#inst' + instanceid);
            var enrol = block.find('.enrol');
            enrol.removeAttr("disabled");

            enrol.click(function () {
                var id = $(this).attr("data-course-id");
                ajax.call([
                    {
                        methodname: "block_related_courses_enrol",
                        args: {"id": id},
                        done: function (result) {
                            if (result == true) {
                                var button = enrol.filter("[data-course-id=" + id + "]");
                                button.attr("disabled", true);
                                str.get_string("enrolled", "block_related_courses").done(function (string) {
                                    button.text(string);
                                });
                            } else {
                                str.get_string("error:enrol", "block_related_courses").done(function (string) {
                                    notification.exception(new Error(string));
                                });
                            }
                        },
                        fail: notification.exception
                    }
                ]);
            })
        };

        return module;
    }
);
