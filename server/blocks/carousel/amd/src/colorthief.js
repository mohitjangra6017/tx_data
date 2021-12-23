define(['jquery', M.cfg.wwwroot + '/blocks/carousel/js/colorthief/color-thief.umd.js'], function($, ColorThief) {
    return {
        init: function() {
            return new ColorThief();
        },

        rgbCode: function(rgb, transparency) {
            return 'rgb(' + rgb[0] + ',' + rgb[1] + ',' + rgb[2] + ',' + transparency + ')';
        }
    };
});