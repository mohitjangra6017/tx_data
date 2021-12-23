M.isotope_provider_bookings = M.isotope_provider_bookings || {

    init: function (Y, selector) {
        // Fetch isotope and filters elements.
        var isotope = $(selector + '.isotope.container');
        var filters = $(selector + '.isotope.filters');

        // Init isotope and filters.
        isotope.isotope({itemSelector: '.isotope.item'});
        filters.on('click', 'button', function() {
            isotope.isotope({filter: $(this).attr('data-filter')});
        });
    }
};