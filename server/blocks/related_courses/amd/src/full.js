define(
    ['jquery', 'block_related_courses/enrol'],
    function($, enrol) {

        var module = {};

        module.initialise = function(instanceid) {
            var block = $('#inst' + instanceid);
            var filters = block.find('.block_related_courses_tags .tag');
            var courses = block.find('.block_related_courses_courses .course');

            filters.click(function () {
                var filter = $(this).attr('data-filter');
                courses.hide().filter(filter).show();
            });

            enrol.initialise(instanceid);
        };

        return module;
    }
);
