M.isotope_provider_programs = M.isotope_provider_programs || {

    init: function (Y, selector) {

        // Some strings we'll need.
        var str_unabletoloadprog = M.util.get_string('error:unabletoloadprogramdata', 'isotopeprovider_programs');

        // Fetch isotope and filters elements.
        var isotope = $(selector + '.isotope.container');
        var filters = $(selector + '.isotope.filters');

        // Fetch isotope and filters elements for the coursesets.
        var isotope_courseset = $(selector + '.isotope.coursesetcontainer');
        var filters_courseset = $(selector + '.isotope.coursesetfilters');

        // Define the program selectors/filters.
        var filters_program = $(selector + '.isotope.progfilters');

        var toggleNoContentText = function () {
            const criteria = isotope_courseset.find('.courseset-criteria'),
                description = isotope_courseset.find('.courseset-description'),
                items = isotope.isotope('getFilteredItemElements');

            if (items.length === 0) {
                criteria.hide();
                description.show();
            } else {
                criteria.show();
                description.hide();
            }
        };

        var filterCallback = function () {
            var selectors = [];
            filters.find('.active').each(function () {
                if ($(this).attr('data-filter') !== "*") {
                    selectors.push($(this).attr('data-filter'));
                }
            });
            selectors = selectors.length ? selectors.join("") : '*';
            isotope.isotope({filter: selectors});
        };

        var filterCourseSetCallback = function () {
            var selectors = [];
            filters_courseset.find('.active').each(function () {
                if ($(this).attr('data-filter') !== "*") {
                    selectors.push($(this).attr('data-filter'));
                }
            });
            selectors = selectors.length ? selectors.join("") : '*';
            isotope_courseset.isotope({filter: selectors});
            $('.courseset').css({position: 'static'});
        };

        // Init isotope and filters.
        isotope.isotope({itemSelector: '.isotope.item'});
        filters.on('click', 'button, div', function() {
            $(this).siblings().removeClass('active');
            $(this).addClass('active');
            filterCallback();
            isotope_courseset.isotope('layout'); // Refresh the layout of the course sets.
            toggleNoContentText();
        });

        // Init isotope and filters for the course sets.
        isotope_courseset.isotope({
            itemSelector: '.isotope.courseset'
        });
        filters_courseset.on('click', 'button, div', function() {
            $(this).siblings().removeClass('active');
            $(this).addClass('active');
            filterCourseSetCallback();
            isotope.isotope('layout'); // Refresh the layout of the courses.
            isotope_courseset.isotope('layout'); // Refresh the layout of the course sets.
            toggleNoContentText();
        });

        // Define a function to be called whenever one of the program filters is selected.
        filters_program.on('click', 'button, div', function(e) {
            e.preventDefault();
            var $button = $(this);

            $button.siblings().removeClass('active');
            $button.addClass('active');

            var filterValue = $button.attr('data-filter');
            var instanceId = $button.parents('.block_isotope').attr('data-instanceid');

            // Load the data for the selected program and retrieve the html.
            if (filterValue > 0) {
                var url = M.cfg.wwwroot + '/local/isotope/providers/programs/ajax/loadprogram.php?programid=' + filterValue + '&instanceid=' + instanceId;
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function (data) {
                        try {
                            var parsedData = JSON.parse(data);
                            var containerItemsHtml = parsedData.items;
                            var coursesetsFilterHtml = parsedData.coursesetsfilter;
                        } catch (e) {
                            alert(str_unabletoloadprog);
                            return;
                        }

                        $(selector + '.isotope.coursesetcontainer').replaceWith(containerItemsHtml);
                        $(selector + '.isotope.coursesetfilters').replaceWith(coursesetsFilterHtml);

                        // Reset the isotope container and filter elements.
                        isotope = $(selector + '.isotope.container');
                        filters = $(selector + '.isotope.filters');
                        filters.on('click', 'button, div', function() {
                            $(this).siblings().removeClass('active');
                            $(this).addClass('active');
                            filterCallback();
                            isotope_courseset.isotope('layout'); // Refresh the layout of the course sets.
                        });

                        // Reset the isotope and filters elements for the coursesets.
                        isotope_courseset = $(selector + '.isotope.coursesetcontainer');
                        filters_courseset = $(selector + '.isotope.coursesetfilters');
                        filters_courseset.on('click', 'button, div', function() {
                            $(this).siblings().removeClass('active');
                            $(this).addClass('active');
                            filterCourseSetCallback();
                            isotope.isotope('layout'); // Refresh the layout of the courses.
                            isotope_courseset.isotope('layout'); // Refresh the layout of the course sets.
                            toggleNoContentText();
                        });

                        // Re-initialise the filters.
                        filterCallback();
                        filterCourseSetCallback();
                    },
                    error: function () {
                        alert(str_unabletoloadprog);
                        return;
                    }
                })
            }

        });

        $('.radial-progress').each(function() {
            if (typeof $(this).data('progress-value') !== 'undefined') {
                // $(this).data() didn't seem to work for some reason.
                $(this).attr('data-progress', $(this).data('progress-value'));
            }
        });

        filterCallback();
        filterCourseSetCallback();
        isotope.isotope('layout'); // Refresh the layout of the courses.
        isotope_courseset.isotope('layout'); // Refresh the layout of the course sets.
        toggleNoContentText();
    }
};
