$(document).ready(function() {
    $('.radial-progress').each(function() {
        if (typeof $(this).data('progress-value') !== 'undefined') {
            // $(this).data() didn't seem to work for some reason.
            $(this).attr('data-progress', $(this).data('progress-value'));
        }
    });
});