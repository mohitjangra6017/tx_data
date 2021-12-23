define(
    ['jquery', M.cfg.wwwroot + '/blocks/carousel/js/ellipsis/jquery.ellipsis.js'],
    function($) {
    return {
        init: function(selector) {
            return $(selector).ellipsis({lines: 2, responsive: true});
        }
    };
});