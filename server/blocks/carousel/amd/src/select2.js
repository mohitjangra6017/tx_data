define(['jquery', M.cfg.wwwroot + '/blocks/carousel/js/select2/js/select2.js'], function ($) {
    return {
        init: function () {
            return $('.select2').select2();
        }
    };
});