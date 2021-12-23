define(['jquery', 'core/templates', 'core/modal_factory', 'block_carousel/modal_curated', 'block_carousel/select2', 'block_carousel/slick', 'block_carousel/colorthief'],
function($, Templates, ModalFactory, ModalCurated, Select2, slick, colorthief) {

    function init_curated() {
        var curated = {

            blockinstanceid: null,
            carouseltype: null,
            curatedid: 0,
            edit_cog: null,
            "delete": null,
            loadingcarousel: false,
            spinner: '<i class="fa fa-spinner fa-spin spinner"></i>',

            /**
             * Init carousels
             *
             */
            init: function(blockinstanceid) {
                curated.blockinstanceid = blockinstanceid;
                curated.init_modal();
                $(document).on('click', '#btn-curated-config-trigger', function() {
                    curated.carouseltype = $('#id_config_carouseltype').val();
                    curated.curatedid = $('#curated-configuration-from').find('#curatedid').val();
                    curated.add_search_fields();
                });
                $(document).on('change', 'select.select2', function() {
                    curated.preview_cluster();
                });
                $(document).on('click', '#save-cluster', function() {
                    curated.save_cluster();
                });
                $(document).on('click', '.edit-cluster', function() {
                    curated.edit_cog = $(this);
                    curated.edit_cog.addClass('fa-spin');
                    curated.curatedid = $(this).data('curatedid');
                    curated.add_search_fields();
                });
                $(document).on('click', '#add-new-cluster', function() {
                    curated.curatedid = 0;
                    $('#curated-configuration-from').find('#curatedid').val(0);
                    curated.add_search_fields();
                });
                $(document).on('click', '.delete-cluster', function() {
                    curated.delete = $(this);
                    curated.delete_cluster();
                });
            },

            init_cluster: function(blockinstanceid) {
                // TODO: update so that once cluster slides has been loaded
                // this is only toggled and not re-fetched from server again
                // The current method was implemented due to time constraint
                // and the previous method of loading all data was glitchy.
                curated.blockinstanceid = blockinstanceid;
                $(document).on('click', '#carousel-nested-' + curated.blockinstanceid + ' .toggle-card-details', function() {
                    if ($(this).hasClass('hasContent')) {
                        $('#carousel-nested-clusters-' + curated.blockinstanceid).html('');
                        $('#carousel-nested-' + curated.blockinstanceid + ' .main-cluster').removeClass('showing-nested');
                        $(this).removeClass('hasContent');
                        return false;
                    }
                    if (!curated.loadingcarousel) {
                        $('#carousel-nested-' + curated.blockinstanceid + ' .toggle-card-details').removeClass('hasContent');
                        curated.loadingcarousel = true;
                        var courseids = $(this).data('courseids');
                        var cardcount = $(this).data('cardcount');
                        curated.get_cluster_carousel(courseids, cardcount, $(this));
                        $('#carousel-nested-' + curated.blockinstanceid + ' .main-cluster').addClass('showing-nested');
                    }
                });
            },

            get_cluster_carousel: function(courseids, cardcount, toggle_btn) {
                $('#carousel-nested-clusters-' + curated.blockinstanceid).addClass('loading').html(curated.spinner);
                $.ajax({
                        method: "POST",
                        url: M.cfg.wwwroot + '/blocks/carousel/ajax/get_cluster_carousel.php',
                        dataType: 'HTML',
                        data: {
                            blockinstanceid: curated.blockinstanceid,
                            courseids: courseids,
                            cardcount: cardcount
                        }
                    })
                    .done(function(html) {
                        $('#carousel-nested-clusters-' + curated.blockinstanceid).removeClass('loading').html(html);
                        toggle_btn.addClass('hasContent');
                        curated.initslick(cardcount);
                        curated.loadingcarousel = false;
                    });
            },

                initslick: function (cardcount) {
                    var mainsettings = {
                        asNavFor: '#carousel-nav-' + curated.blockinstanceid,
                        slidesToShow: cardcount,
                        slidesToScroll: 1,
                        centerMode: false,
                        focusOnSelect: true,
                        responsive: [{
                            breakpoint: 1200,
                            settings: {
                                slidesToShow: 3,
                                slidesToScroll: 1,
                            }
                        },
                            {
                                breakpoint: 768,
                                settings: {
                                    slidesToShow: 2,
                                    slidesToScroll: 1
                                }
                            },
                            {
                                breakpoint: 600,
                                settings: {
                                    slidesToShow: 1,
                                    slidesToScroll: 1
                                }
                            }
                        ]
                    };

                    var navsettings = {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        arrows: false,
                        fade: true,
                        draggable: false,
                        accessibility: false,
                    };

                    var nav = slick.init('#carousel-nav-' + curated.blockinstanceid, navsettings);
                    var main = slick.init('#carousel-' + curated.blockinstanceid, mainsettings);

                    nav.hide();

                $('#carousel-' + curated.blockinstanceid + ' article.learning .toggle-card-details').click(function() {
                    nav.show();
                    $('#carousel-' + curated.blockinstanceid).addClass('showing-details');
                    $(window).trigger('resize');
                    // Sometime the nav slides resize fails
                    // This is a fallback check
                    var track_width = $('#carousel-nav-' + curated.blockinstanceid + ' .slick-track').css('width');
                    if (track_width == '0px') {
                        $('#carousel-nav-' + curated.blockinstanceid + ' .slick-track').css('width', '100%');
                        $('#carousel-nav-' + curated.blockinstanceid + ' .slick-track .slick-slide.slick-active').css('width', '100%');
                    }
                });

                $('#carousel-' + curated.blockinstanceid + ' article.learning figure.tile').click(function(e) {
                    if (!$(this).data('displayinmodal')) {
                        e.stopPropagation();
                        window.location.href = $(this).data('url');
                        return false;
                    }
                    // VODHAS-1949
                    // else open details
                    $(this).siblings('.toggle-card-details').trigger('click');
                });

                $('#carousel-nav-' + curated.blockinstanceid + ' .close-slide-details').click(function() {
                    nav.hide("fast");
                    $('#carousel-' + curated.blockinstanceid).removeClass('showing-details');
                    $(window).trigger('resize');
                });


                $('#carousel-nav-' + curated.blockinstanceid + ' article').each(function() {
                    const article = $(this);
                    const imageUrl = $(this).find("img.hidden:first").attr('src');
                    const image = new Image();

                    image.onload = function() {
                        const colorThief = colorthief.init();
                        const primaryColor = colorThief.getColor(image);
                        var r = primaryColor[0],
                            g = primaryColor[1],
                            b = primaryColor[2];
                        // Take the RGB and convert that into the perceived brightness of the primary colour on a 0 - 1 scale.
                        // More info: https://stackoverflow.com/q/596216
                        const luminance = Math.sqrt((0.299 * r * r) + (0.587 * g * g) + (0.114 * b * b)) / 255;
                        article.addClass(luminance > 0.6 ? 'dark-text' : 'light-text');

                        const dominantColor = colorthief.rgbCode(primaryColor, 1);
                        $(this).css('background-image', 'linear-gradient(to right,' + dominantColor + ' 0% 15%, ' + dominantColor + ' 15% 58%, transparent 70% 100%), url(' + imageUrl + ')');
                    }.bind(this);
                    image.src = imageUrl;
                });

                $('#carousel-nav-' + curated.blockinstanceid + ' article.learning-details .launch-courselet').click(function() {
                    $.ajax({
                        method: "POST",
                        url: '/blocks/carousel/ajax/process_courselet.php',
                        dataType: 'JSON',
                        data: {courseid: $(this).data('courseid'), nodisplay: 1}
                    })
                    .done(function() {
                        // do nothing
                    });
                });
            },

            init_modal: function() {
                // Button defined in
                // edit_forms/curated_content_settings.php
                var trigger = $('#btn-curated-config-trigger');

                ModalFactory.create({
                        type: ModalCurated.TYPE
                    }, trigger)
                    .done(function(modal) {
                        var root = modal.getRoot();
                        root.addClass('carousel-curated-modal');
                    });
            },

            add_search_fields: function() {
                $.ajax({
                        method: "POST",
                        url: M.cfg.wwwroot + '/blocks/carousel/ajax/curated_filters.php',
                        dataType: 'JSON',
                        data: {
                            blockinstanceid: curated.blockinstanceid,
                            carouseltype: curated.carouseltype,
                            curatedid: curated.curatedid
                        }
                    })
                    .done(function(data) {
                        $('#curated-filters').html(data.html);
                        Select2.init();
                        if (curated.carouseltype == 'cluster') {
                            $('#add-new-cluster').removeClass('hide');
                        }
                        // Get preview courses if any
                        curated.preview_cluster();
                    });
            },

            preview_cluster: function() {
                $.ajax({
                        method: "POST",
                        url: M.cfg.wwwroot + '/blocks/carousel/ajax/preview_cluster.php',
                        dataType: 'JSON',
                        data: $('#curated-configuration-from').serialize() + "&carouseltype=" + curated.carouseltype + "&blockinstanceid=" + curated.blockinstanceid
                    })
                    .done(function(data) {
                        $('#curated-contents').html(data.html);
                        if (curated.edit_cog) {
                            curated.edit_cog.removeClass('fa-spin');
                        }
                    });
            },

            save_cluster: function() {
                var btn_default_value = $('#save-cluster').html();
                $('#save-cluster').html('Saving...');
                var form = $('#curated-configuration-from');
                var formData = new FormData(form[0]);
                formData.append('blockinstanceid', curated.blockinstanceid);

                $.ajax({
                        method: "POST",
                        url: M.cfg.wwwroot + '/blocks/carousel/ajax/save_cluster.php',
                        data: formData,
                        enctype: 'multipart/form-data',
                        contentType: false,
                        cache: false,
                        processData: false
                    })
                    .done(function() {
                        $('#save-cluster').html('Saved');
                        setTimeout(function() {
                            $('#save-cluster').html(btn_default_value);
                        }, 3000);
                    });
            },

            delete_cluster: function() {
                var curatedid = curated.delete.data('curatedid');
                curated.delete.parent().siblings().html('Deleting...');

                $.ajax({
                        method: "POST",
                        url: M.cfg.wwwroot + '/blocks/carousel/ajax/delete_cluster.php',
                        dataType: 'JSON',
                        data: {
                            blockinstanceid: curated.blockinstanceid,
                            curatedid: curatedid
                        }
                    })
                    .done(function() {
                        // Clear from data
                        curated.curatedid = 0;
                        $('#curated-configuration-from').find('#curatedid').val(0);
                        curated.add_search_fields();
                    });
            }
        };
        return curated;
    }

    /** Don't over complicate this bit. It simply expose the init_* function of the curated object. mod_interface object is assigned all the init_* function
     *  of the curated one. Within each method, it will call the init_curated to create a new curated object and call the corresponding function.
     * The reason for doing this is to initiate a new curated object each time the module is called.
     * Note that requirejs do not reevaluate the module each time it is called.
     */
    var mod_interface = {};
    var curated = init_curated();
    for (var prop in curated) {
        if (curated[prop] instanceof Function && prop.substr(0, 4) == 'init') {
            mod_interface[prop] = (function(prop) {
                return function() {
                    var curated_obj = init_curated();
                    curated_obj[prop].apply(curated_obj, arguments);
                };
            }(prop));
        }
    }
    return mod_interface;

});