M.isotope_provider_record_of_learning = M.isotope_provider_record_of_learning || {

    tableCollapsed : true,
    tableCollapseLink : null,
    maximumTableSize : 7,

        init: function (Y, selector) {
            // Fetch isotope and filters elements.
            var isotope = $(selector + '.isotope.container');
            var filters = $(selector + '.isotope.filters .filter-button, ' + selector + '.isotope.filters .radial-progress');
            var tableContainer = $(selector + '.learning.isotope.container.display-type-table');
            var self = this;

            var filterCallback = function () {
                var selectors = [];
                filters.each(function () {
                    if ($(this).hasClass('active') && $(this).attr('data-filter') !== "*") {
                        selectors.push($(this).attr('data-filter'));
                    }
                });
                self.filterTable(tableContainer, selectors);
                selectors = selectors.length ? selectors.join("") : '*';
                isotope.isotope({filter: selectors});
                tableContainer.find('tr').css({position: ''});
                tableContainer.css({height: 'auto'});

            };

            // Init isotope and filters.
            isotope.isotope({itemSelector: '.isotope.item'});
            tableContainer.find('tr').css({position: ''});
            tableContainer.css({height: 'auto'});
            filters.on('click', function() {
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

            this.collapseTable(tableContainer);
        },

    collapseTable: function (tableContainer) {
        var $table = tableContainer.find("table.isotope-recordoflearning-table");
        var $tr = $table.find('tbody tr');
        var self = this;
        if ($tr.length > self.maximumTableSize) {
            self.tableCollapseLink = $('<a href="javascript:void(0)">More...</a>').click(function () {
                self.tableCollapsed = !self.tableCollapsed;
                self.showHideRows($tr);
                $(this).text(self.tableCollapsed ? 'More...' : 'Less');
            });
            $tr.parents('.isotope.container:first').append(self.tableCollapseLink);
        }
    },

    showHideRows: function ($tr) {
        var self = this;
        $tr.filter(function (i, item) {
            return !item.classList.contains('filtered');
        }).each(function (i, item) {
            if (self.tableCollapsed && i >= self.maximumTableSize) {
                item.classList.add('collapsed');
            } else {
                item.classList.remove('collapsed');
            }
        });
    },

    filterTable: function (tableContainer, selectors) {
        var $tr = tableContainer.find('tbody tr');
        $tr.each(function (i, item) {
            item.classList.remove('collapsed', 'filtered');
            if (selectors.length === 0) {
                return;
            }
            var classList = item.className.split(/\s+/);
            var selected = selectors.every(function (value) {
                return classList.indexOf(value.substring(1)) !== -1;
            });
            if (!selected) {
                item.classList.add('collapsed', 'filtered');
            }
        });
        this.showHideRows($tr);
    }
};
