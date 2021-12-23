/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon Thornett <simon.thornett@kineo.com>
 */
define(['jquery', 'select2'], function ($, select2) {

    return {

        customAppearance : false,
        showcase : false,

        init: function ()
        {
            var that = this;

            that.customAppearance = $('select[name="cs_custom_appearance[]"]');
            that.showcase = $('.custom-appearance-showcase');

            if (!that.customAppearance) {
                return;
            }

            if (that.customAppearance) {
                that.customAppearance.select2({
                    templateSelection: function (item) {
                        return item.element.parentElement.getAttribute('label') + ': ' + item.text;
                    },
                    templateResult: function (item) {
                        if (item.element && item.element.getAttribute('data-type') === 'colour') {
                            return $('<span class="' + item.element.getAttribute('data-class') + '"></span>').text(item.text);
                        }
                        return item.text;
                    },
                    allowClear: true,
                    placeholder: '',
                    width: '50%'
                });

                that.customAppearance.on('select2:selecting', function (event) {
                    var data = event.params.data || event.params.args.data;
                    var selected = that.customAppearance.select2('data');

                    // Remove any currently selected item that shares the same optgroup as the item we are trying to select.
                    var newSelection = selected
                        .filter(function (item) {
                            return item.element.parentElement.label !== data.element.parentElement.label;
                        })
                        .map(function (item) {
                            return item.element.value;
                        })
                    ;
                    that.customAppearance.val(newSelection);
                });
                that.customAppearance.on('select2:select select2:unselect', function (event) {
                    that.setClasses();
                });
            }

            that.setClasses();
        },
        setClasses : function ()
        {
            this.showcase.removeClass().addClass('custom-appearance-showcase '
                + this.customAppearance.select2('data').map(function (item) {
                    return item.id;
                }).join(' ')
            );
        }
    };
});