/**
 * Created by james.ballard on 13/08/2014.
 */
$(document).ready(function() {
    show_stars();
    //$(".datepicker").datepicker( { dateFormat: 'dd/mm/yy' } );
    $(".rating").rating({
        showClear: false,
        showCaption: false
    });
    $(".userselect2").select2({
        placeholder: "Search for a user",
        minimumInputLength: 1,
        ajax: {
            url: "/blocks/rate_course/get_users.php",
            dataType: 'json',
            data: function (term, page) {
                return {
                    q: term,
                    page_limit: 10,
                    courseid: location.search.split("id=")[1]
                };
            },
            results: function (data, page) {
                return {results: data};
            }
        },
        escapeMarkup: function (m) { return m; }
    });
    $('.dropdown-menu, #ui-datepicker-div').click(function(e) {
        e.stopPropagation();
    });
});

function block_rate_course_like_review(review_id) {
    $.post('/blocks/rate_course/like_review.php', {
        reviewid: review_id,
        action: 'add'
    }, function(data){
        if (data == 'failed') {
            alert(data);
        } else {
            $('.course-reviews-list').html(data);
            show_stars();
            $('.course-reviews-list').scrollTop(0);
        }
    });
}

function block_rate_course_like_course(course_id,like) {
    $.post('/blocks/rate_course/like_course.php', {
        courseid: course_id,
        like: like
    }, function(data){
        if (data == 'failed') {
            alert(data);
        } else {
            $('.rate-course-like-course').html(data);
        }
    });
}

function show_stars() {
    $(".block_rate_course_show_rating").rating({
        showClear: false,
        showCaption: false,
        readonly: true
    });
}