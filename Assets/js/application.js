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
});