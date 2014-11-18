/**
 * This part use to set the footer are stay in the button if
 * the page content is not high enough.
 * 
 * @author Michael Luthor <michelluthor@163.com>
 */
$(document).ready(function() {
    /* 如果页面不够长， 则强制footer到页面底部。 */
    var top = $('#main-footer')[0].offsetTop;
    var height = $('#main-footer')[0].clientHeight;
    var windowHeight = $(window).height();
    if ( top + height < windowHeight ) {
       $('#main-footer').addClass('navbar-fixed-bottom');
    }
    
    /* 针对Media列表页面， 当鼠标移动到项目上时，显示操作按钮。 */
    $('.lnm-media-list-item').mouseenter(function() {
        $(this).children().show();
    });
    $('.lnm-media-list-item').mouseleave(function() {
        $(this).children().hide();
    });
    
    /* 针对Media列表页面，如果鼠标点击非标记按钮则进入详细界面。 */
    $('.lnm-media-list-item-empty-area').click(function() {
        window.location = $(this).attr('data-detail-url');
    });
    
    /* 仅当media处于可视区域时加载背景图片。 */
    $('.lnm-media-list-item').waypoint(function(direction) {
        if ( 'up' == direction ){
            return;
        }
        var isLoaded = $(this).attr('data-poster-loaded');
        if ( 'undefined' == typeof(isLoaded) ) {
             var poster = $(this).attr('data-poster');
             $(this).css('background-image', 'url("'+poster+'")');
             $(this).attr('data-poster-loaded', true);
        }
    }, { offset: '100%' });
    
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
});