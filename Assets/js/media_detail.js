$(document).ready(function() {
    /**
     * @param dialogues
     * @returns
     */
    function refreshClassicDialogues( dialogues ) {
        
    }
    
    /**
     * @returns
     */
    function getClassicDialogues( ) {
        $.get('/?module=lunome&action=movie/classicDialogues', {
            id   : $('#movie-classic-dialogues-container').attr('data-movie-id'),
            page : $('#movie-classic-dialogues-container').attr('data-page')
        }, function( response ) {
            if (0 == response.length ) {
                $('#movie-classic-dialogues-prev-page').trigger('click');
                return;
            }
            $('#movie-classic-dialogues-container-items').empty();
            for( var i in response ) {
                $('#movie-classic-dialogues-container-items').append(
                    $('<div>').addClass('well').addClass('well-sm').html(response[i].content)
                );
            }
        }, 'json');
    }
    getClassicDialogues();
    $('#movie-classic-dialogues-prev-page').addClass('disabled');
    
    $('#movie-classic-dialogues-prev-page').click(function() {
        var currentPage = $('#movie-classic-dialogues-container').attr('data-page')*1;
        if ( 0>=currentPage-1 ) {
            return false;
        }
        
        if ( 1>=currentPage-1 ) {
            $(this).addClass('disabled');
        }
        
        $('#movie-classic-dialogues-container').attr('data-page',currentPage-1);
        getClassicDialogues();
        return false;
    });
    
    $('#movie-classic-dialogues-next-page').click(function() {
        $('#movie-classic-dialogues-container').attr('data-page', 
            $('#movie-classic-dialogues-container').attr('data-page')*1+1
        );
        $('#movie-classic-dialogues-prev-page').removeClass('disabled');
        getClassicDialogues();
        return false;
    });
    
    /* 绑定事件到在线观看按钮 */
    var playerTrigger = $('[data-online-play-trigger="true"]');
    $(playerTrigger.attr('data-player-container')).hide();
    playerTrigger.click(function() {
        var playerStatus = $(this).attr('data-player-status');
        var container = $(playerTrigger.attr('data-player-container'));
        
        if ( 'undefined' != typeof playerStatus && 'open' == playerStatus ) {
            /* 如果播放器处于打开状态， 则关闭。*/
            container.empty().hide();
            container.next().remove();
            $(this).attr('data-player-status', 'closed');
            $(this).text('在线观看');
            return;
        }
        
        $(this).attr('data-player-status', 'open');
        $(this).text('关闭播放器');
        
        /* 显示等待控制条。 */
        var loaddingImg = $(this).attr('data-loadding-img');
        var assetsPath = $(this).attr('data-assets-path');
        
        container
            .height(500)
            .append('<img src="'+loaddingImg+'">')
            .addClass('text-center')
            .css({'line-height':'500px', 'box-shadow':'0px 1px 2px rgba(0, 0, 0, 0.075)','border':'1px solid #DDD','border-radius':'4px','transition':'all 0.2s ease-in-out 0s'})
            .show()
            .after('<hr>');
        
        var name = $(this).attr('data-movie-name');
        var url = 'http://lunome.kupoy.com/?module=lunome&action=movie/globalSearch';
        $.get(url, {name:name}, function( response ) {
            if ( 0 == response.length ) {
                container.html('无法找到相关在线视频～～～');
                return;
            }
            
            container.empty().css('line-height', '1em');
            var list = $('<div>').addClass('clearfix').appendTo(container);
            for ( var i in response ) {
                var link = $('<a>')
                .attr('href', '#')
                .attr('data-name', response[i].name)
                .attr('data-link',response[i].link)
                .attr('data-source',response[i].source)
                .click(function() {
                    var $this = this;
                    $.get('http://lunome.kupoy.com/?module=lunome&action=movie/play', {
                        source:$(this).attr('data-source'),
                        link:$(this).attr('data-link')
                    }, function( response ) {
                        list.hide();
                        
                        var playerContainer = $('<div>');
                        var returnBackToSearchResult = $('<a>')
                        .addClass('pull-left')
                        .attr('href', '#')
                        .html('<span class="glyphicon glyphicon-hand-left"></span>返回搜索结果')
                        .click(function() {
                            playerContainer.empty().remove();
                            list.show();
                            return false;
                        });
                        
                        var gotoVideoOwnerWebsite = $('<a>')
                        .addClass('pull-right')
                        .attr('target', '_black')
                        .attr('href', $($this).attr('data-link'))
                        .html('进入该视频主站<span class="glyphicon glyphicon-hand-right"></span>');
                        
                        playerContainer
                        .append($('<div>').html(response))
                        .append($('<div>')
                                .addClass('clearfix')
                                .append($('<div>').addClass('col-md-2').append(returnBackToSearchResult))
                                .append($('<div>').addClass('col-md-8').append($('<h5>').html($($this).attr('data-name'))))
                                .append($('<div>').addClass('col-md-2').append(gotoVideoOwnerWebsite))
                        )
                        .addClass('padding-10');
                        
                        container.append(playerContainer);
                    }, 'text');
                    return false;
                })
                .appendTo(list);
                
                $('<div>')
                .addClass('margin-5')
                .addClass('thumbnail')
                .addClass('pull-left')
                .append(
                    $('<div>').height(150).append($('<img>').attr('src', response[i].thumb).attr('alt', response[i].name).width(100).height(150))
                )
                .append(
                    $('<div>').append($('<img>').attr('src', assetsPath+'/image/'+response[i].source+'.png'))
                )
                .append(
                    $('<div>').append($('<span>').html(response[i].name))
                )
                .appendTo(link);
            }
        }, 'json');
    });
});