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
    
    $('.button-invite-to-watch-movie').click(function() {
        var $this = $(this);
        var dialog = $('#invite-to-watch-movie-dialog');
        
        var message = '确定要邀请'+dialog.attr('data-name-list')+'去看《'+$this.attr('data-title')+'》吗？';
        var button = $('#invite-to-watch-movie-dialog-yes');
        button.attr('data-movie', $this.attr('data-id'));
        button.attr('data-loadding-img', $this.attr('data-loadding-img'));
        $('#invite-to-watch-movie-dialog-message').next().show();
        
        $('#invite-to-watch-movie-dialog-message').html(message);
        dialog.modal('show');
    });
    
    $('#invite-to-watch-movie-dialog-yes').click(function() {
        var $this = $(this);
        var loadingImage = $('<img>').attr('src', $this.attr('data-loadding-img'));
        $('#invite-to-watch-movie-dialog-message').html(loadingImage).next().hide();
        $.post('/?module=movie&action=interaction/inviteFriendsToWatchMovieSendMessage', {
            friends : $('#invite-to-watch-movie-dialog').attr('data-id-list'), 
            movie   : $this.attr('data-movie'),
            comment : $('#invite-to-watch-movie-dialog-comment').val()
        }, function( response ) {
            $('#invite-to-watch-movie-dialog-comment').val('');
            $('#invite-to-watch-movie-dialog').modal('hide');
            alert('邀请已经发送给好友们~~~');
        }, 'json');
    });
});