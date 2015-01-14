$(document).ready(function() {
    $('.friend-list-item').click(function() {
        var checkbox = $(this).children('input').trigger('click');
    });
});