M.isotope_provider_mandatory_completion = M.isotope_provider_mandatory_completion || {

        init: function (Y, selector) {
            // Fetch isotope and filters elements.
            var isotope = $(selector + '.isotope.container');
            var filters = $(selector + '.isotope.filters');

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

            // Init isotope and filters.
            isotope.isotope({itemSelector: '.isotope.item'});
            filters.on('click', 'button, div', function() {
                $(this).siblings().removeClass('active');
                $(this).addClass("active");
                filterCallback();
            });

            $('.radial-progress').each(function() {
                if (typeof $(this).data('progress-value') !== 'undefined') {
                    // $(this).data() didn't seem to work for some reason.
                    $(this).attr('data-progress', $(this).data('progress-value'));
                }
            });

            filterCallback();
        }
    };
