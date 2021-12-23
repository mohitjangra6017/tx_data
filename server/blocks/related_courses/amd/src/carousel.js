define(
    ['jquery', 'block_related_courses/slick', 'block_related_courses/enrol'],
    function ($, slick, enrol) {

        var module = {};

        module.initialise = function (instanceid) {
            var block = $('#inst' + instanceid);
            var courses = block.find('.block_related_courses_courses');
            courses.slick();
            enrol.initialise(instanceid);
        };

        return module;
    }
);
