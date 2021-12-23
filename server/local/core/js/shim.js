document.addEventListener('DOMContentLoaded', function () {
    window.requirejs.config({
        paths: {
            "select2": M.cfg.wwwroot + '/local/core/js/select2.min',
            "slick": M.cfg.wwwroot + '/local/core/libs/slick/slick.min',
        },
        shim: {
            'select2': {exports: 'select2'},
            'slick': {exports: 'slick'},
        }
    });
});