/**
 * It's easier to provide a link than an explanation here: http://stackoverflow.com/a/28608806
 */
var media_handler = {
    init: function() {
        this._process();
    },

    _process: function() {
        $('.block_banner').each(function() {
            var style = $('<style />');

            var media = $(this).find('img[alt="media"]');
            var instanceid = $(this).data('instanceid');
            if (!instanceid) {
                // data-instanceid might not exist, so use the ID instead, removing the 'inst' prefix.
                instanceid = this.id.substring('inst'.length);
            }
            var mediaclass = 'on-the-fly-media-' +  instanceid;
            var selectorclass = '';
            var html = '';

            if ($(this).attr('selector').length > 0 ) {
                selectorclass = $(this).attr('selector');

                if ($(this).attr('textcolour').length > 0) {
                    $(selectorclass).addClass($(this).attr('textcolour'));
                }

                html = selectorclass + ' {' + "\n";
                html += 'background-image: ' + $(this).css('background-image') + ';' + "\n";
                var colour = $(this).attr('colour');
                if (colour && colour.length > 0) {
                    html += 'background-color: #' + $(this).attr('colour') + ';' + "\n";
                }
                html += '}' + "\n";
            }
            else {
                selectorclass = '';
                html = '.' + mediaclass + ' {' + "\n";
                var instance = instanceid;
                html += 'background-image: ' + $(this).css('background-image') + ';' + "\n";
                html += '}' + "\n";
            }

            var images = [];
            $(media).each(function() {
                html += '@media (min-width: '+ $(this).attr('id') +'px) {' + "\n";

                if (selectorclass != "") {
                    html += selectorclass + '{' + "\n";
                } else {
                    html += '.' + mediaclass + '{' + "\n";
                }

                html += 'background-image: url(' + $(this).attr('src') + ');' + "\n";
                html += '}' + "\n";
                html += '}' + "\n";
                $(this).remove();
            });

            // Append to style tag not supported in IE8, so write out in 1 line.
            $('head').append('<style type="text/css">' + html + '</style>');

            $(this).addClass(mediaclass);
            $(this).attr('style', '');
            $(selectorclass).attr('style', '');
        });
    },

    applyCSS: function() {
        if (typeof banner_instances === 'undefined') {
            return false;
        }

        for (var i = 0; i < banner_instances.length; i++) {
            try {
                window[banner_instances[i]]();
            } catch (e) {
                // Nothing.
            }
        }
    }
};

$(document).ready(function() {
    media_handler.init();
    media_handler.applyCSS();
});

