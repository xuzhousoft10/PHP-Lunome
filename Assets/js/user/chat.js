$(document).ready(function() {
    var chatContentContainer = $('#user-chat-container-container');
    var friendID = chatContentContainer.attr('data-friend-id');
    var sendMessageContainer = $('#user-chat-content');
    
    $(window).resize(function() {
        chatContentContainer.height($(window).height()-350)
    }).trigger('resize');
    
    function readChatMessages() {
        $.get('/?module=account&action=chat/read', {friend:friendID}, function( response ) {
            chatContentContainer.append(response);
            scrollMessagesToButtom();
            setTimeout(readChatMessages, 3000);
        }, 'text');
    }
    
    function scrollMessagesToButtom() {
        chatContentContainer[0].scrollTop=chatContentContainer[0].scrollHeight;
    }
    
    function sendMessage() {
        var message = $.trim(sendMessageContainer.val());
        if ( '' == message ) {
            return;
        }
        
        $.post('/?module=account&action=chat/write', {
            friend:friendID,
            content:sendMessageContainer.val()
        }, function( response ) {
            chatContentContainer.append(response);
            scrollMessagesToButtom();
            sendMessageContainer.val('');
        }, 'text');
    }
    
    readChatMessages();
    
    $('#user-chat-send-button').click(sendMessage);
    
    sendMessageContainer.keydown(function(event) {
        if (event.keyCode == 13){
            sendMessage();
            return false;
        }
    });
    
    $(window).bind('beforeunload', function() {
        $.get('/?module=account&action=chat/stop', {id:friendID}, function( response ) {
        }, 'text');
    } );
});