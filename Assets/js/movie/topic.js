$(document).ready(function() {
    $('.lunome-movie-poster').waypoint(function(direction) {
        var isLoaded = $(this).attr('data-cover-loaded');
        if ( 'undefined' == typeof(isLoaded) ) {
            var cover = $(this).attr('data-cover-url');
            $(this).css('background-image', 'url("'+cover+'")');
            $(this).attr('data-cover-loaded', true);
        }
    }, {offset:'100%'});
    
    $('.lunome-movie-poster').children().hide();
    $('.lunome-movie-poster').hover(function() {
        $(this).children().show();
    }, function() {
        $(this).children().hide();
    });
});