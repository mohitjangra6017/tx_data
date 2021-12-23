define(['jquery'], function($) {
    $.init_dialog_link = function(modalattributes) {
        var $parent = $('body');
        if ($parent.data('dialog_init')) {
            // already initialised, do nothing
            return;
        }

        // mark as initialised for dialog box
        $parent.data('dialog_init', true);
        $parent.on('click', '.modal-dialogbox-link', function(ev) {
            var $this = $(this);
            ev.preventDefault();

            var content = $this.data('content');
            var size = $this.data('size');
            var header = $this.data('header');
            var options = {
                header: header,
                size: size ? size : 'medium'
            };
            if ($this.data('header')) {
                options.header = $this.data('header');
            }
            if (content) {
                options.content = content;
            } else {
                options.url = $this.data('url');
            }

            $.showDialog(options, modalattributes);
        });
    };

    /**
     * Show a dialog.
     * @param {object} options option include
     *      header: the header of the dialog
     *      url: url for the content
     *      content: html for the content
     *      element: the jQuery element to set the content
     *      callback: callback when done
     *      size: 'large', 'medium' or 'small', default to large
     *  @param {object} modalattributes the modal attribute
     *  @param {string} mode either 'center' or top
     * */
    $.showDialog = function(options, modalattributes, mode) {
        var dialog = $('#modal_dialog');
        var modalclass = '', outermodalclass = '';
        if (!modalattributes) {
            modalattributes = {};
        }
        modalclass = modalattributes['class'] ? modalattributes['class'] : '';
        outermodalclass = modalattributes['outerclass'] ? modalattributes['outerclass'] : '';
        if (dialog.length===0) {    // create a dialog if it hasn't exist yet
            dialog = $(
                '<div id="modal_dialog" class="kineomodal modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">'+
                    '<div id="innermodal" class="modal-dialog modal-lg">'+
                        '<div class="modal-content">'+
                            '<div class="modal-header">'+
                                '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                                '<h4 class="modal-title" id="myModalLabel">Modal title</h4>'+
                            '</div>'+
                            '<div class="modal-body">'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                    '<div class="modal-backdrop fade in"></div>' +
                '</div>');

            dialog.on('click', function(evt) {
                if ($(evt.target).is('#innermodal', '.modal-backdrop') || $(evt.target.parentNode).is(':button', '.close')) {
                    dialog.hide();
                    $('body').removeClass('modal-open');
                }
            });
            $('body').append(dialog);
            dialog = $('#modal_dialog');
        }

        if (!mode) {
            mode = 'center';
        }
        if (mode==='center') {
            dialog.css('display', 'table');
            dialog.removeClass('topmode');
        } else if (mode==='top') {
            dialog.css('display', 'block');
            dialog.addClass('topmode');
        }

        dialog.addClass('modal fade '+outermodalclass);
        dialog.find('#innermodal').attr('class', 'modal-dialog '+modalclass);
        // set the header
        display_header(dialog, options.header ? options.header : false);

        var dialogsize = {large: 'modal-lg', medium: 'modal-md', small: 'modal-sm'};
        if (!options.size || !dialogsize[options.size]) {    // default to large
            options.size = 'medium';
        }
        dialog.addClass(dialogsize[options.size]);
        dialog.find('#innermodal').removeClass('modal-lg modal-md modal-sm').addClass(dialogsize[options.size]);
        var maxheight = $(window).height()*0.85-dialog.find('.modal-header').outerHeight();
        if (modalattributes['maxheightpercentage']) {
            maxheight = maxheight * modalattributes['maxheightpercentage'];
        }
        if (!modalattributes['nomaxheight'] && mode==='center') {
            dialog.find('.modal-body').css('max-height', ''+(maxheight)+'px');
        } else {
            dialog.find('.modal-body').css('max-height', '');
        }
        if (modalattributes['allowoverflow']) {
            dialog.find('.modal-body').addClass('allowoverflow');
        }

        // take the modal option
        var modaloptions = {backdrop: true, keyboard: true, show: true};
        for (var op in modaloptions) {
            if (options[op]) {
                modaloptions[op] = options[op];
            }
        }
        dialog.addClass('fade in');
        $('body').addClass('modal-open');
        dialog.show();

        // if url is defined: load content from url
        var dialogcontent = dialog.find('.modal-body');
        if (options.url) {
            // put the load image
            var loadimg = '<img src="'+M.util.image_url('i/loading')+'" />';
            dialogcontent.addClass('loadingcenter').html(loadimg);

            // get the content
            $.get(options.url, function(data) {
                dialogcontent.html(data.content);
                dialogcontent.removeClass('loadingcenter');
                if (data.header) {
                    display_header(dialog, data.header);
                }
                if (options.callback) {
                    options.callback(data, dialogcontent);
                }
            });
        } else if (options.content) {
            dialogcontent.html(options.content);
        } else if (options.element) {
            dialogcontent.empty().append(options.element);
        }

        function display_header(dialog, header) {
            if (header) {
                dialog.find('.modal-header').show();
                dialog.find('.modal-header').children(':not(.close)').remove();
                dialog.find('.modal-header').append('<h4 class="modal-title" id="myModalLabel">'+header+'</h4>');
            } else {
                dialog.find('.modal-header').hide();
            }
        }
    };

    return {
        init_dialog_link: $.init_dialog_link
    };
});