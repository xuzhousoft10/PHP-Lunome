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
    
    $('#invite-to-watch-movie-dialog-yes').click(function() {
        var $this = $(this);
        var loadingImage = $('<img>').attr('src', $this.attr('data-loadding-img'));
        $('#invite-to-watch-movie-dialog-message').html(loadingImage).next().hide();
        $.post('/?module=lunome&action=movie/interaction/InviteToWatchMovieSendMessage', {
            friend:$this.attr('data-friend'), 
            movie:$this.attr('data-movie'),
            comment:$('#invite-to-watch-movie-dialog-comment').val()
        }, function( response ) {
            $('#invite-to-watch-movie-dialog-comment').val('');
            $('#invite-to-watch-movie-dialog').modal('hide');
            alert('邀请已经发送给好友~~~');
        }, 'json');
    });
    
    $('.button-invite-to-watch-movie').click(function() {
        var $this = $(this);
        var message = '确定要邀请'+$this.attr('data-friend-name')+'去看《'+$this.attr('data-title')+'》吗？';
        var button = $('#invite-to-watch-movie-dialog-yes');
        button.attr('data-movie', $this.attr('data-id'));
        button.attr('data-friend', $this.attr('data-friend-id'));
        button.attr('data-loadding-img', $this.attr('data-loadding-img'));
        $('#invite-to-watch-movie-dialog-message').next().show();
        
        $('#invite-to-watch-movie-dialog-message').html(message);
        $('#invite-to-watch-movie-dialog').modal('show');
    });
});