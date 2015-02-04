$(document).ready(function() {
    $('.friend-list-item').click(function() {
        $(this).children('input')[0].checked=!($(this).children('input')[0].checked);
        
        var peopleCount = $('.lunome-friends-interaction-friends-container').attr('data-people-count')*1;
        if ( $('.friend-selector:checked').length > peopleCount ) {
            $(this).children('input')[0].checked=!($(this).children('input')[0].checked);
            alert('参与互动的好友数量不能超过'+peopleCount+'个~~~');
            return false;
        }
    });
});