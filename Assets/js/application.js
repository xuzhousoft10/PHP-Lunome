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