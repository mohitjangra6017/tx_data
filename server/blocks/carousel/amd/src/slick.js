define(['jquery', M.cfg.wwwroot + '/blocks/carousel/js/slick/slick.js'], function ($) {
    return {
        init: function ($frameid, $settings) {
            return $($frameid).slick($settings);
        }
    };
});