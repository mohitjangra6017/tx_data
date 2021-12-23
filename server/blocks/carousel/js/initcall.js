if (!M) {
    M = {};
}

if (!M.kineo_carousel) {
    M.kineo_carousel = {};
}

M.kineo_carousel.init_color_picker = function(Y, textid) {
    $('#'+textid).minicolors({
        theme: 'bootstrap',
        position: 'bottom left',
        defaultValue: ''
    });
};