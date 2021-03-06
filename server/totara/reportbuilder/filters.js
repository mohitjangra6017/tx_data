/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2010 onwards Totara Learning Solutions LTD
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Eugene Venter <eugene@catalyst.net.nz>
 * @package totara
 * @subpackage reportbuilder
 */
M.totara_reportbuilderfilters = M.totara_reportbuilderfilters || {

    Y: null,
    // optional php params and defaults defined here, args passed to init method
    // below will override these values
    config: {},
    loadingimg: '',
    deleteicon: '',
    upicon: '',
    downicon: '',
    spacer: '',

    /**
     * module initialisation method called by php js_init_call()
     *
     * @param object    YUI instance
     * @param string    args supplied in JSON format
     */
    init: function(Y, args){

        // save a reference to the Y instance (all of its dependencies included)
        this.Y = Y;

        // if defined, parse args into this module's config object
        if (args) {
            var jargs = Y.JSON.parse(args);
            for (var a in jargs) {
                if (Y.Object.owns(jargs, a)) {
                    this.config[a] = jargs[a];
                }
            }
        }

        // check jQuery dependency is available
        if (typeof $ === 'undefined') {
            throw new Error('M.totara_reportbuildercolumns.init()-> jQuery dependency required for this module to function.');
        }


        var that = this;
        require(['core/templates'], function (templates) {
            var iconscache = [];
            iconscache.push(templates.renderIcon('loading', M.util.get_string('saving', 'totara_reportbuilder')));
            iconscache.push(templates.renderIcon('delete', M.util.get_string('delete', 'totara_reportbuilder')));
            iconscache.push(templates.renderIcon('arrow-up', M.util.get_string('moveup', 'totara_reportbuilder')));
            iconscache.push(templates.renderIcon('arrow-down', M.util.get_string('movedown', 'totara_reportbuilder')));
            iconscache.push(templates.renderIcon('spacer'));

            $.when.apply($, iconscache).then(function (loadingicon, deleteicon, upicon, downicon, spacer) {
                that.loadingimg = loadingicon;
                that.deleteicon = deleteicon;
                that.upicon = upicon;
                that.downicon = downicon;
                that.spacer = spacer;
                // Do setup.
                that.rb_init_filter_rows();
                that.rb_init_search_column_rows();
            });
        });

    },

    rb_init_filter_rows: function() {

        var module = this;

        // Disable the new filter name field on page load.
        $('#id_newstandardcustomname').prop('disabled', true);
        $('#id_newsidebarcustomname').prop('disabled', true);

        // Disable uncustomised headers on page load.
        $('input.filter_custom_name_checkbox').not(':checked').each(function() {
            var textElement = $('input.filter_name_text', $(this).parents('tr:first'));
            textElement.prop('disabled', true);
        });

        var handleFilter = function() {
            var filterRequired = this.closest('tr').querySelector('.filter_filteringrequired_checkbox');
            window.onbeforeunload = null;
            if (filterRequired) {
                if (this.checked === true) {
                    filterRequired.checked = false;
                    filterRequired.disabled = true;
                } else {
                    filterRequired.disabled = false;
                }
            }
        };

        // Disable onbeforeunload for advanced checkbox.
        $('input.filter_advanced_checkbox').off('change');
        $('input.filter_advanced_checkbox').on('change', handleFilter);

        // now intitialise all feilds
        $('input.filter_advanced_checkbox').each(handleFilter);

        $('input.filter_filteringrequired_checkbox').off('change');
        $('input.filter_filteringrequired_checkbox').on('change', function() {
            window.onbeforeunload = null;
        });

        // Handle changes to the filter pulldowns.
        $('select.filter_selector').off('change');
        $('select.filter_selector').on('change', function() {
            window.onbeforeunload = null;
            var changedSelector = $(this).val();
            var newContent = module.config.rb_filter_headings[changedSelector];
            var textElement = $('input.filter_name_text', $(this).parents('tr:first'));

            textElement.val(newContent);  // Insert new content.
        });

        // Handle changes to the customise checkbox.
        // Use click instead of change event for IE.
        $('input.filter_custom_name_checkbox').off('click');
        $('input.filter_custom_name_checkbox').on('click', function() {
            window.onbeforeunload = null;
            var textElement = $('input.filter_name_text', $(this).parents('tr:first'));
            if ($(this).is(':checked')) {
                // Enable the textbox when checkbox isn't checked.
                textElement.prop('disabled', false);
            } else {
                // Disable the textbox when checkbox is checked.
                // And reset text contents back to default.
                textElement.prop('disabled', true);
                var changedSelector = $('select.filter_selector', $(this).parents('tr:first')).val();
                var newContent = module.config.rb_filter_headings[changedSelector];
                textElement.val(newContent);
            }
        });

        // Handle changes to the 'Add another filter...' selector.
        var handleSelector = function() {
            window.onbeforeunload = null;
            var region = $(this).attr('id').substring(6, $(this).attr('id').indexOf("filter"));
            var addbutton = module.rb_init_filter_addbutton($(this), region);
            var advancedCheck = $('#id_new' + region + 'advanced');
            var filteringrequiredCheck = $('#id_new' + region + 'filteringrequired');
            var newNameBox = $('input.filter_name_text', $(this).parents('tr:first'));
            var newCheckBox = $('input.filter_custom_name_checkbox', $(this).parents('tr:first'));
            var selectedval = $(this).val();

            if (selectedval == 0) {
                // Clean out the selections.
                advancedCheck.prop('disabled', true);
                advancedCheck.removeAttr('checked');
                if (region == 'standard') {
                    filteringrequiredCheck.prop('disabled', true);
                    filteringrequiredCheck.removeAttr('checked');
                }
                newNameBox.val('');
                newNameBox.prop('disabled', true);
                addbutton.remove();
                newCheckBox.removeAttr('checked');
                newCheckBox.prop('disabled', true);
            } else {
                // Reenable it (binding above will fill the value)
                advancedCheck.prop('disabled', false);
                if (region == 'standard') {
                    filteringrequiredCheck.prop('disabled', false);
                }
                newCheckBox.prop('disabled', false);
            }
        };
        $('select.new_standard_filter_selector, select.new_sidebar_filter_selector').on('change', handleSelector);
        $('select.new_standard_filter_selector, select.new_sidebar_filter_selector').each(handleSelector);

        // Set up delete button events.
        module.rb_init_filter_deletebuttons();
        // Set up 'move' button events.
        module.rb_init_filter_movedown_btns();
        module.rb_init_filter_moveup_btns();

        // Add aria label for standard region.
        this.rb_init_add_aria_label_to_rows('standard');

        // Add aria label for sidebar region.
        this.rb_init_add_aria_label_to_rows('sidebar');
    },

    rb_init_add_aria_label_to_rows: function (region) {
        var tr = $('#id_new'+region+'filter').parents('tr:first');
        var columnIndex = tr.prevAll().length;
        $('#id_new'+region+'filter').attr('aria-label', M.util.get_string('filtering_search_field', 'totara_reportbuilder', columnIndex));
        $('#id_new'+region+'customname').attr('aria-label', M.util.get_string('filtering_customise_field', 'totara_reportbuilder', columnIndex));
        $('#id_new'+region+'filtername').attr('aria-label', M.util.get_string('filtering_customise_name', 'totara_reportbuilder', columnIndex));
        $('#id_new'+region+'advanced').attr('aria-label', M.util.get_string('filtering_advanced', 'totara_reportbuilder', columnIndex));
        if (region == 'standard') {
            $('#id_newstandardfilteringrequired').attr('aria-label', M.util.get_string('filteringrequired_field', 'totara_reportbuilder', columnIndex));
        }

        var selectors = tr.siblings().find('select.filter_selector');
        var checkboxes = tr.siblings().find('input.filter_custom_name_checkbox:checkbox');
        var fieldNames = tr.siblings().find('input.filter_name_text');
        var advanceCheckboxes = tr.siblings().find('input.filter_advanced_checkbox:checkbox');
        if (region == 'standard') {
            var requiredCheckboxes = tr.siblings().find('input.filter_filteringrequired_checkbox:checkbox');
        }

        var index = 1;
        for (var i=0; i<selectors.length; i++) {
            $(selectors[i]).attr('aria-label', M.util.get_string('filtering_search_field', 'totara_reportbuilder', index));
            $(checkboxes[i]).attr('aria-label', M.util.get_string('filtering_customise_field', 'totara_reportbuilder', index));
            $(fieldNames[i]).attr('aria-label', M.util.get_string('filtering_customise_name', 'totara_reportbuilder', index));
            $(advanceCheckboxes[i]).attr('aria-label', M.util.get_string('filtering_advanced', 'totara_reportbuilder', index));
            if (region == 'standard') {
                $(requiredCheckboxes[i]).attr('aria-label', M.util.get_string('filteringrequired_field', 'totara_reportbuilder', index));
            }
            index++;
        }
    },

    rb_init_search_column_rows: function() {
        var module = this;

        // Handle changes to the search column pulldowns.
        $('select.search_column_selector').off('change');
        $('select.search_column_selector').on('change', function() {
            window.onbeforeunload = null;
        });

        // Handle changes to the 'Add another search column...' selector.
        $('select.new_search_column_selector').on('change', function() {
            window.onbeforeunload = null;
            var addbutton = module.rb_init_search_column_addbutton($(this));
            var selectedval = $(this).val();

            if (selectedval == 0) {
                // Clean out the selections.
                addbutton.remove();
            } else {
                // Reenable it (binding above will fill the value).
            }
        });

        // Set up delete button events.
        module.rb_init_search_column_deletebuttons();

        // Add aria label to toolbar search region.
        var tr = $('#id_newsearchcolumn').parents('tr:first');
        var columnIndex = tr.prevAll().length;
        $('#id_newsearchcolumn').attr('aria-label', M.util.get_string('filtering_search_field', 'totara_reportbuilder', columnIndex));

        var selectors = tr.siblings().find('select.search_column_selector');
        var index = 1;
        for (var i=0; i<selectors.length; i++) {
            $(selectors[i]).attr('aria-label', M.util.get_string('filtering_search_field', 'totara_reportbuilder', index));
            index++;
        }
    },

    rb_init_filter_addbutton: function(filterselector, region) {
        var module = this;
        var advancedCheck = $('#id_new' + region + 'advanced');
        var filteringrequiredCheck = $('#id_new' + region + 'filteringrequired');
        var customnameCheck = $('#id_new' + region + 'customname');
        var optionsbox = advancedCheck.closest('tr').find('td:last-child');
        var selector = filterselector.closest('td');
        var newfilterinput = filterselector.closest('tr').clone();  // Clone of current 'Add new filter...' tr.
        newfilterinput.find("input:text").val(""); // Reset value.
        var addbutton = optionsbox.find('.additembtn');
        if (addbutton.length == 0) {
            addbutton = module.rb_get_btn_add(module.config.rb_reportid);
        } else {
            // Button already initialised.
            return addbutton;
        }

        // Add save button to options.
        optionsbox.prepend(addbutton);
        addbutton.off('click');
        addbutton.on('click', function(e) {
            e.preventDefault();
            var newfiltername = $('#id_new' + region + 'filtername').val();
            $.ajax({
                url: M.cfg.wwwroot + '/totara/reportbuilder/ajax/filter.php',
                type: "POST",
                data: ({action: 'add', sesskey: module.config.user_sesskey, id: module.config.rb_reportid,
                        filter: filterselector.val(), advanced: Number(advancedCheck.is(':checked')), region: region,
                        filteringrequired: Number(filteringrequiredCheck.is(':checked')),
                        customname: Number(customnameCheck.is(':checked')), filtername: newfiltername}),
                beforeSend: function() {
                    addbutton.html(module.loadingimg);
                },
                success: function(o) {
                    if (o.length > 0) {
                        // Add action buttons to row.
                        var fid = parseInt(o);
                        var deletebutton = module.rb_get_filter_btn_delete(module.config.rb_reportid, fid);

                        var upbutton = '';
                        var uppersibling = filterselector.closest('tr').prev('tr');
                        if (uppersibling.find('select.filter_selector').length > 0) {
                            // Create an up button for the newly added filter, to be added below.
                            var upbutton = module.rb_get_filter_btn_up(module.config.rb_reportid, fid);
                        }

                        addbutton.remove();
                        optionsbox.prepend(deletebutton, upbutton);
                        module.config.rb_filters++;

                        // Set row atts.
                        $('#id_new' + region + 'filter').removeClass('new_standard_filter_selector');
                        $('#id_new' + region + 'filter').removeClass('new_sidebar_filter_selector');
                        var filterbox = selector;
                        var customname = $('#id_new' + region + 'customname');
                        var nametext = $('#id_new' + region + 'filtername');
                        filterbox.find('select.filter_selector').attr('name', 'filter'+fid);
                        filterbox.find('select optgroup[label=New]').remove();
                        filterbox.find('select.filter_selector').attr('id', 'id_filter'+fid);
                        filterbox.find('select.filter_selector').attr('aria-label', M.util.get_string('filtering_search_field', 'totara_reportbuilder', fid));
                        customname.attr('id', 'id_customname'+fid);
                        customname.attr('name', 'customname'+fid);
                        customname.attr('aria-label', M.util.get_string('filtering_customise_field', 'totara_reportbuilder', fid))
                        nametext.attr('id', 'id_filtername'+fid);
                        nametext.attr('name', 'filtername'+fid);
                        nametext.attr('aria-label', M.util.get_string('filtering_customise_name', 'totara_reportbuilder', fid))
                        advancedCheck.attr('name', 'advanced'+fid);
                        advancedCheck.attr('id', 'id_advanced'+fid);
                        advancedCheck.attr('aria-label', M.util.get_string('filtering_advanced', 'totara_reportbuilder', fid));
                        if (region == 'standard') {
                            filteringrequiredCheck.attr('name', 'filteringrequired'+fid);
                            filteringrequiredCheck.attr('id', 'id_filteringrequired'+fid);
                            filteringrequiredCheck.attr('aria-label', M.util.get_string('filteringrequired_field', 'totara_reportbuilder', fid));
                            filteringrequiredCheck.closest('tr').attr('fid', fid);
                        } else {
                            advancedCheck.closest('tr').attr('fid', fid);
                        }

                        // Append a new filter select box
                        filterbox.closest('table').append(newfilterinput);

                        module.rb_reload_filter_option_btns(uppersibling);

                        // Remove added filter from the new filter selector.
                        var filtertype = filterselector.val().split('-')[0];
                        var filterval = filterselector.val().split('-')[1];
                        $('.new_standard_filter_selector optgroup option[value=' + filtertype + '-' + filterval + ']').remove();
                        $('.new_sidebar_filter_selector optgroup option[value=' + filtertype + '-' + filterval + ']').remove();

                        module.rb_init_filter_rows();

                    } else {
                        alert('Error');
                    }

                },
                error: function(h, t, e) {
                    alert('Error');
                }
            }); // Ajax.
        }); // Click event.

        return addbutton;
    },


    rb_init_search_column_addbutton: function(searchcolumnselector) {
        var module = this;
        var optionsbox = searchcolumnselector.closest('td').next('td');
        var selector = searchcolumnselector.closest('td');
        var newsearchcolumninput = searchcolumnselector.closest('tr').clone();  // Clone of current 'Add new search column...' tr.
        newsearchcolumninput.find("input:text").val(""); // Reset value.
        var addbutton = optionsbox.find('.additembtn');
        if (addbutton.length == 0) {
            addbutton = module.rb_get_btn_add(module.config.rb_reportid);
        } else {
            // Button already initialised.
            return addbutton;
        }

        // Add save button to options.
        optionsbox.prepend(addbutton);
        addbutton.off('click');
        addbutton.on('click', function(e) {
            e.preventDefault();
            $.ajax({
                url: M.cfg.wwwroot + '/totara/reportbuilder/ajax/searchcolumn.php',
                type: "POST",
                data: ({action: 'add', sesskey: module.config.user_sesskey, id: module.config.rb_reportid,
                        searchcolumn: searchcolumnselector.val()}),
                beforeSend: function() {
                    addbutton.html(module.loadingimg);
                },
                success: function(o) {
                    if (o.length > 0) {
                        // Add action buttons to row.
                        var searchcolumnid = parseInt(o);
                        var deletebutton = module.rb_get_search_column_btn_delete(module.config.rb_reportid, searchcolumnid);

                        addbutton.remove();
                        optionsbox.prepend(deletebutton);
                        module.config.rb_filters++;

                        // Set row atts.
                        $('#id_newsearchcolumn').removeClass('new_search_column_selector');
                        var searchcolumnbox = selector;
                        searchcolumnbox.find('select.search_column_selector').attr('name', 'searchcolumn'+searchcolumnid);
                        searchcolumnbox.find('select optgroup[label=New]').remove();
                        searchcolumnbox.find('select.search_column_selector').attr('id', 'id_searchcolumn'+searchcolumnid);

                        // Set the id of the new searchcolumn tr
                        selector.closest('tr').attr('searchcolumnid', searchcolumnid);

                        // Append a new filter select box
                        searchcolumnbox.closest('table').append(newsearchcolumninput);

                        // Remove added filter from the new filter selector.
                        var searchcolumntype = searchcolumnselector.val().split('-')[0];
                        var searchcolumnval = searchcolumnselector.val().split('-')[1];
                        $('.new_search_column_selector optgroup option[value='+searchcolumntype+'-'+searchcolumnval+']').remove();

                        module.rb_init_search_column_rows();

                    } else {
                        alert('Error');
                    }

                },
                error: function(h, t, e) {
                    alert('Error');
                }
            }); // Ajax.
        }); // Click event.

        return addbutton;
    },


    rb_init_filter_deletebuttons: function() {
        var module = this;
        $('.reportbuilderform table .deletefilterbtn').off('click');
        $('.reportbuilderform table .deletefilterbtn').on('click', function(e) {
            e.preventDefault();
            var clickedbtn = $(this);

            var confirmstr = M.util.get_string('confirmfilterdelete', 'totara_reportbuilder');
            if (module.config.rb_initial_display && module.config.rb_filters == 1) {
                var a = '';
                if (module.config.rb_global_initial_display == 1) {
                    a = M.util.get_string('confirmfilterdelete_grid_enabled', 'totara_reportbuilder');
                }
                confirmstr = M.util.get_string('confirmfilterdelete_rid_enabled', 'totara_reportbuilder', a);
            }

            confirmed = confirm(confirmstr);

            if (!confirmed) {
                return;
            }
            module.config.rb_filters--;

            var filterrow = $(this).closest('tr');
            $.ajax({
                url: M.cfg.wwwroot + '/totara/reportbuilder/ajax/filter.php',
                type: "POST",
                data: ({action: 'delete', sesskey: module.config.user_sesskey, id: module.config.rb_reportid, fid: filterrow.attr('fid')}),
                beforeSend: function() {
                    clickedbtn.replaceWith(module.loadingimg);
                },
                success: function(o) {
                    if (o.length > 0) {
                        o = JSON.parse(o);

                        var uppersibling = filterrow.prev('tr');
                        var lowersibling = filterrow.next('tr');

                        // Remove filter row.
                        filterrow.remove();

                        // Fix sibling buttons.
                        if (uppersibling.find('select.filter_selector').length > 0) {
                            module.rb_reload_filter_option_btns(uppersibling);
                        }
                        if (lowersibling.find('select.filter_selector:not(.new_standard_filter_selector, .new_sidebar_filter_selector)').length > 0) {
                            module.rb_reload_filter_option_btns(lowersibling);
                        }

                        var nlabel = o.typelabel;
                        var issidebarfilter = $('#id_all_sidebar_filters').find('option[value=' + o.type + '-' + o.value+']').length > 0;

                        // Add deleted filter to new standard filter selector.
                        var standardoptgroup = $(".new_standard_filter_selector optgroup[label='" + $.escapeSelector(nlabel) + "']");
                        if (standardoptgroup.length == 0) {
                            // Create optgroup and append to select.
                            standardoptgroup = $('<optgroup label="' + nlabel + '"></optgroup>');
                            $('.new_standard_filter_selector').append(standardoptgroup);
                        }
                        if (standardoptgroup.find('option[value=' + o.type + '-' + o.value + ']').length == 0) {
                            standardoptgroup.append('<option value="' + o.type + '-' + o.value + '">' +
                                    rb_filter_headings[o.type + '-' + o.value] + '</option>');
                        }
                        // Add deleted filter to new sidebar filter selector.
                        if (issidebarfilter) {
                            var sidebaroptgroup = $(".new_sidebar_filter_selector optgroup[label='" + nlabel + "']");
                            if (sidebaroptgroup.length == 0) {
                                // Create optgroup and append to select.
                                sidebaroptgroup = $('<optgroup label="' + nlabel + '"></optgroup>');
                                $('.new_sidebar_filter_selector').append(sidebaroptgroup);
                            }
                            if (sidebaroptgroup.find('option[value=' + o.type + '-' + o.value + ']').length == 0) {
                                sidebaroptgroup.append('<option value="' + o.type + '-' + o.value + '">' +
                                        rb_filter_headings[o.type + '-' + o.value] + '</option>');
                            }
                        }

                        module.rb_init_filter_rows();

                    } else {
                        alert('Error');
                    }

                },
                error: function(h, t, e) {
                    alert('Error');
                }
            }); // Ajax.

        });

        function rb_ucwords (str) {
            return (str + '').replace(/^([a-z])|\s+([a-z])/g, function($1) {
                return $1.toUpperCase();
            });
        }
    },

    rb_init_search_column_deletebuttons: function() {
        var module = this;
        $('.reportbuilderform table .deletesearchcolumnbtn').off('click');
        $('.reportbuilderform table .deletesearchcolumnbtn').on('click', function(e) {
            e.preventDefault();
            var clickedbtn = $(this);


            var confirmstr = M.util.get_string('confirmsearchcolumndelete', 'totara_reportbuilder');
            if (module.config.rb_initial_display && module.config.rb_filters == 1) {
                var a = '';
                if (module.config.rb_global_initial_display == 1) {
                    a = M.util.get_string('confirmfilterdelete_grid_enabled', 'totara_reportbuilder');
                }
                confirmstr = M.util.get_string('confirmfilterdelete_rid_enabled', 'totara_reportbuilder', a);
            }

            confirmed = confirm(confirmstr);

            if (!confirmed) {
                return;
            }
            module.config.rb_filters--;

            var searchcolumnrow = $(this).closest('tr');
            $.ajax({
                url: M.cfg.wwwroot + '/totara/reportbuilder/ajax/searchcolumn.php',
                type: "POST",
                data: ({action: 'delete', sesskey: module.config.user_sesskey, id: module.config.rb_reportid,
                    searchcolumnid: searchcolumnrow.attr('searchcolumnid')}),
                beforeSend: function() {
                    clickedbtn.replaceWith(module.loadingimg);
                },
                success: function(o) {
                    if (o.length > 0) {
                        o = JSON.parse(o);

                        // Remove search column row.
                        searchcolumnrow.remove();

                        // Add deleted search column to new search column selector.
                        var nlabel = o.typelabel;  // Determine the optgroup label.
                        var optgroup = $(".new_search_column_selector optgroup[label='" + nlabel + "']");
                        if (optgroup.length == 0) {
                            // Create optgroup and append to select.
                            optgroup = $('<optgroup label="' + nlabel + '"></optgroup>');
                            $('.new_search_column_selector').append(optgroup);
                        }
                        if (optgroup.find('option[value=' + o.type + '-' + o.value + ']').length == 0) {
                            optgroup.append('<option value="' + o.type + '-' + o.value + '">'+
                                    rb_search_column_headings[o.type + '-' + o.value] + '</option>');
                        }

                        module.rb_init_search_column_rows();

                    } else {
                        alert('Error');
                    }

                },
                error: function(h, t, e) {
                    alert('Error');
                }
            }); // Ajax.

        });

        function rb_ucwords (str) {
            return (str + '').replace(/^([a-z])|\s+([a-z])/g, function($1) {
                return $1.toUpperCase();
            });
        }
    },

    rb_init_filter_movedown_btns: function() {
        var module = this;
        $('.reportbuilderform table .movefilterdownbtn').off('click');
        $('.reportbuilderform table .movefilterdownbtn').on('click', function(e) {
            e.preventDefault();
            var clickedbtn = $(this);

            var filterrow = $(this).closest('tr');

            var filterrowclone = filterrow.clone();
            // Set the selected option, cause for some reason this don't clone so well...
            filterrowclone.find('select.filter_selector option[value='+filterrow.find('select.filter_selector').val()+']').attr('selected', 'selected');

            var lowersibling = filterrow.next('tr');

            var lowersiblingclone = lowersibling.clone();
            // Set the selected option, cause for some reason this don't clone so well...
            lowersiblingclone.find('select.filter_selector option[value='+lowersibling.find('select.filter_selector').val()+']').attr('selected', 'selected');

            $.ajax({
                url: M.cfg.wwwroot + '/totara/reportbuilder/ajax/filter.php',
                type: "POST",
                data: ({action: 'movedown', sesskey: module.config.user_sesskey, id: module.config.rb_reportid, fid: filterrow.attr('fid')}),
                beforeSend: function() {
                    lowersibling.html(module.loadingimg);
                    filterrow.html(module.loadingimg);
                    filterrowclone.find('td *').hide();
                    lowersiblingclone.find('td *').hide();
                },
                success: function(o) {
                    if (o.length > 0) {
                        // Switch!
                        filterrow.replaceWith(lowersiblingclone);
                        lowersibling.replaceWith(filterrowclone);

                        filterrowclone.find('td *').fadeIn();
                        lowersiblingclone.find('td *').fadeIn();

                        // Fix option buttons.
                        module.rb_reload_filter_option_btns(filterrowclone);
                        module.rb_reload_filter_option_btns(lowersiblingclone);

                        module.rb_init_filter_rows();

                    } else {
                        alert('Error');
                    }

                },
                error: function(h, t, e) {
                    alert('Error');
                }
            }); // Ajax.

        });
    },


    rb_init_filter_moveup_btns: function() {
        var module = this;
        $('.reportbuilderform table .movefilterupbtn').off('click');
        $('.reportbuilderform table .movefilterupbtn').on('click', function(e) {
            e.preventDefault();
            var clickedbtn = $(this);

            var filterrow = $(this).closest('tr');
            var filterrowclone = filterrow.clone();
            // Set the selected option, cause for some reason this don't clone so well...
            filterrowclone.find('select.filter_selector option[value='+filterrow.find('select.filter_selector').val()+']').attr('selected', 'selected');

            var uppersibling = filterrow.prev('tr');

            var uppersiblingclone = uppersibling.clone();
            // Set the selected option, cause for some reason this don't clone so well...
            uppersiblingclone.find('select.filter_selector option[value='+uppersibling.find('select.filter_selector').val()+']').attr('selected', 'selected');

            $.ajax({
                url: M.cfg.wwwroot + '/totara/reportbuilder/ajax/filter.php',
                type: "POST",
                data: ({action: 'moveup', sesskey: module.config.user_sesskey, id: module.config.rb_reportid, fid: filterrow.attr('fid')}),
                beforeSend: function() {
                    uppersibling.html(module.loadingimg);
                    filterrow.html(module.loadingimg);

                    filterrowclone.find('td *').hide();
                    uppersiblingclone.find('td *').hide();
                },
                success: function(o) {
                    if (o.length > 0) {
                        // Switch!
                        filterrow.replaceWith(uppersiblingclone);
                        uppersibling.replaceWith(filterrowclone);

                        filterrowclone.find('td *').fadeIn();
                        uppersiblingclone.find('td *').fadeIn();

                        // Fix option buttons.
                        module.rb_reload_filter_option_btns(filterrowclone);
                        module.rb_reload_filter_option_btns(uppersiblingclone);

                        module.rb_init_filter_rows();

                    } else {
                        alert('Error');
                    }

                },
                error: function(h, t, e) {
                    alert('Error');
                }
            }); // Ajax.

        });
    },

    rb_reload_filter_option_btns: function(filterrow) {
        var module = this;
        var optionbox = filterrow.children('td').filter(':last');

        optionbox.empty();

        // Replace btns with updated ones.
        var fid = filterrow.attr('fid');
        var deletebtn = module.rb_get_filter_btn_delete(module.config.rb_reportid, fid);
        var upbtn = this.spacer;
        if (filterrow.prev('tr').find('select.filter_selector').length > 0) {
            upbtn = module.rb_get_filter_btn_up(module.config.rb_reportid, fid);
        }
        var downbtn = this.spacer;
        if (filterrow.next('tr').next('tr').find('select.filter_selector').length > 0) {
            downbtn = module.rb_get_filter_btn_down(module.config.rb_reportid, fid);
        }

        optionbox.append(deletebtn, upbtn, downbtn);
    },


    rb_reload_search_column_option_btns: function(searchcolumnrow) {
        var module = this;
        var optionbox = searchcolumnrow.children('td').filter(':last');

        optionbox.empty();

        // Replace btns with updated ones.
        var searchcolumnid = searchcolumnrow.attr('searchcolumnid');
        var deletebtn = module.rb_get_search_column_btn_delete(module.config.rb_reportid, searchcolumnid);

        optionbox.append(deletebtn);
    },


    rb_get_filter_btn_delete: function(reportid, fid) {
        return $('<a href=' + M.cfg.wwwroot + '/totara/reportbuilder/filters.php?id=' + reportid + '&fid=' + fid +
                '&d=1" class="deletefilterbtn action-icon">' + this.deleteicon + '</a>');
    },

    rb_get_search_column_btn_delete: function(reportid, searchcolumnid) {
        return $('<a href=' + M.cfg.wwwroot + '/totara/reportbuilder/filters.php?id=' + reportid + '&searchcolumnid=' +
                searchcolumnid + '&d=1" class="deletesearchcolumnbtn action-icon">' + this.deleteicon + '</a>');
    },

    rb_get_filter_btn_up: function(reportid, fid) {
        return $('<a href=' + M.cfg.wwwroot + '/totara/reportbuilder/filters.php?id=' + reportid + '&fid=' + fid +
                '&m=up" class="movefilterupbtn action-icon">' + this.upicon + '</a>');
    },

    rb_get_filter_btn_down: function(reportid, fid) {
        return $('<a href=' + M.cfg.wwwroot + '/totara/reportbuilder/filters.php?id=' + reportid + '&fid=' + fid +
                '&m=down" class="movefilterdownbtn action-icon">' + this.downicon + '</a>');
    },

    rb_get_btn_add: function(reportid) {
        return $('<a href=' + M.cfg.wwwroot + '/totara/reportbuilder/filters.php?id=' + reportid +
                '" class="additembtn"><input type="button" value="' + M.util.get_string('add', 'totara_reportbuilder') +
                '" /></a>');
    }

}
