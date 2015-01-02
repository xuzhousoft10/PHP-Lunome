/**
 * This part use to set the footer are stay in the button if
 * the page content is not high enough.
 * 
 * @author Michael Luthor <michelluthor@163.com>
 */
(function($) {
    /**
     * 记录日志到控制台。
     */
    $.log = function(message, category) {
        if ( ('undefined'==console) || ('undefined'==console.log) ) {
            return;
        }
        console.log('['+category+']:'+message);
        return this;
    }
 
})(jQuery);

/* 定时检查新消息 */
function userNotificationChecker( setTimer ) {
    $.get('/?module=lunome&action=user/notification/check', {}, function( response ) {
        var count = response.count*1;
        if ( 0 == count ) { /* 如果为空， 则隐藏消息图标和消息列表 */
            $('#user-notification-trigger').popover('hide');
            $('#user-notification-trigger').hide();
        } else {
            $('#user-notification-trigger').show().html('('+count+')');
            /* 如果消息列表处于显示状态，并且消息数量不同， 则刷新列表 */
            if ( 0 < $('#user-notification-container').length 
            && $('#user-notification-trigger').attr('data-counter')*1 != count ) {
                $('#user-notification-trigger').popover('hide');
                setTimeout(function() {
                    $('#user-notification-trigger').popover('show');
                }, 500);
            }
        }
        $('#user-notification-trigger').attr('data-counter', count);
    }, 'json');
    
    if ( setTimer ) {
        setTimeout(function() {
            userNotificationChecker(true);
        }, 3000);
    }
}

function fixNotificationCountValue( diffValue ) {
    var oldCounter = $('#user-notification-trigger').attr('data-counter')*1;
    $('#user-notification-trigger').attr('data-counter', oldCounter+diffValue);
}

function closeNotificationByID( notificationID, handler ) {
    $.post('/?module=lunome&action=user/notification/close', {
        id:notificationID,
    }, function( response ) {
        fixNotificationCountValue(-1);
        handler(response);
    }, 'text');
}

$(document).ready(function() {
    /* 如果页面不够长， 则强制footer到页面底部。 */
    var top = $('#main-footer')[0].offsetTop;
    var height = $('#main-footer')[0].clientHeight;
    var windowHeight = $(window).height();
    if ( top + height < windowHeight ) {
       $('#main-footer').addClass('navbar-fixed-bottom');
    }
    
    /* 定位media列表页面的media工具栏。 */
    if ( 0 < $('.media-index-tool-bar').length ) {
        $(window).resize(function(){
            var winWidth = $(window).width();
            var containerWidth = $('.container').width();
            if ( containerWidth + $('#media-index-tool-bar').width() > winWidth ) {
                $('.media-index-tool-bar').hide();
            } else {
                $('.media-index-tool-bar').show();
                $('.media-index-tool-bar').css('left', winWidth/2+containerWidth/2+1);
            }
        }).trigger('resize');
    }
    
    /* 用于返回顶部时的动画效果 */
    $('#goto-top').click(function() {
        $('body,html').animate({ scrollTop: 0 }, 1500);
    });
    
    /* 声明消息框 */
    $('#user-notification-trigger').popover({
        container : 'body',
        content   : '<div id="user-notification-container"></div>',
        placement : 'bottom',
        html      : true,
    }).on('shown.bs.popover', function () {
        var loadingImg = $('#user-notification-trigger').attr('data-loadding-img');
        var img = $('<img>').attr('src', loadingImg);
        $('#user-notification-container').addClass('text-center').append(img);
        $('#user-notification-container').parent().css('padding','0');
        $.get('/?module=lunome&action=user/notification/index', {}, function( response ) {
            $('#user-notification-container').removeClass('text-center').html(response);
        }, 'text');
    });
    
    $('#user-notification-trigger').attr('data-counter', 0);
    $('#user-notification-trigger').hide();
    userNotificationChecker(true);
});