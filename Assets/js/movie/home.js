$(document).ready(function() {
    $('.lnm-media-list-item').waypoint(function(direction) {
        var isLoaded = $(this).attr('data-cover-loaded');
        if ( 'undefined' == typeof(isLoaded) ) {
            var cover = $(this).attr('data-cover-url');
            $(this).css('background-image', 'url("'+cover+'")');
            $(this).attr('data-cover-loaded', true);
        }
    }, {offset:'100%'});
    
    $('.lnm-media-list-item').hover(function() {
        $(this).children().height(280).show();
    }, function() {
        $(this).children().hide();
    });
    
    var scoreContainers = $('.movie-item-rate-container');
    for ( var i in scoreContainers ) {
        scoreContainers.eq(i).rateit({
            max:10,
            step:1,
            resetable:false, 
            readonly:true,
            value:scoreContainers.eq(i).attr('data-score')
        });
    }
});