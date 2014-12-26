$(document).ready(function() {
    /* 角色管理开始 */
    function getCharacters() {
        $.get('/?module=lunome&action=movie/character/index', {
            id   : $('#movie-characters-container').attr('data-movie-id'),
            page : $('#movie-characters-container').attr('data-page')
        }, function( response ) {
            if (0 == response.length ) {
                $('#movie-characters-prev-page').trigger('click');
                return;
            }
            
            $('#movie-characters-container-items').empty();
            
            for( var i in response ) {
                $('#movie-characters-container-items').append(
                    $('<div>')
                    .addClass('thumbnail')
                    .addClass('clearfix')
                    .append(
                        $('<div>')
                        .addClass('col-md-2')
                        .html(  $('<img>')
                                .addClass('img-thumbnail')
                                .width(60)
                                .height(60)
                                .attr('src', response[i].imageURL)
                                .attr('alt',response[i].name)
                        )
                    )
                    .append(
                        $('<div>')
                        .addClass('col-md-10')
                        .append(
                            $('<p>').html(response[i].name)
                        )
                        .append(
                            $('<p>').html(response[i].description)
                        )
                    )
                );
            }
        }, 'json');
    }
    
    $('#movie-characters-prev-page').addClass('disabled');
    
    $('#movie-characters-prev-page').click(function() {
        var currentPage = $('#movie-characters-container').attr('data-page')*1;
        if ( 0>=currentPage-1 ) {
            return false;
        }
        
        if ( 1>=currentPage-1 ) {
            $(this).addClass('disabled');
        }
        
        $('#movie-characters-container').attr('data-page',currentPage-1);
        getPosters();
        return false;
    });
    
    $('#movie-characters-next-page').click(function() {
        $('#movie-characters-container').attr('data-page', 
            $('#movie-characters-container').attr('data-page')*1+1
        );
        $('#movie-characters-prev-page').removeClass('disabled');
        getPosters();
        return false;
    });
    
    $('#movie-character-save').click(function() {
        var movieId = $('#movie-characters-container').attr('data-movie-id');
        var name = $.trim($('#movie-character-name').val());
        
        if ( 0 == name.length ) {
            alert('角色名称不能为空。');
            return false;
        }
        
        if ( !(/(jpg|png)/.test($('#movie-character-image').val())) ) {
            alert('头像仅支持JPG和PNG格式文件。');
            return;
        }
        
        var postData = {
            movie:movieId,
            'character[name]':name,
            'character[description]':$('#movie-character-description').val()
        };
        
        var afterSaved = function() {
            $('#movie-character-name').val('');
            $('#movie-character-description').val('');
            $('#movie-character-image').replaceWith('<input type="file" name="image" id="movie-character-image">');
            $('#movie-characters-edit-dialog').modal('hide');
        };
        
        if ( 0 === $.trim($('#movie-character-image').val()) ) {
            $.post('/?module=lunome&action=movie/character/edit', postData, function(response) {
                afterSaved();
            }, 'text');
        } else {
            $.ajaxFileUpload({
                url             : '/?module=lunome&action=movie/character/edit',
                data            : postData,
                secureuri       : false,
                fileElementId   : 'movie-character-image',
                dataType        : 'text',
                success         : function (data, status){
                    afterSaved();
                },
                error           : function (data, status, e){
                    afterSaved();
                }
            });
        }
    });
    
    /* 角色管理结束 */
    
    /* 海报管理开始 */
    $('#movie-poster-save').click(function() {
        if ( '' === $('#movie-poster-file').val() ) {
            alert('没有要保存的海报文件。');
            return;
        }
        if ( !(/(jpg|png)/.test($('#movie-poster-file').val())) ) {
            alert('海报文件仅支持JPG和PNG格式文件。');
            return;
        }
        
        var movieId = $('#movie-posters-container').attr('data-movie-id');
        $('#movie-poster-file').parent().children().hide();
        $('#movie-poster-file').parent().append('<img src="http://7sbnm1.com1.z0.glb.clouddn.com/image/loadding.gif">');
        $.ajaxFileUpload({
            url             : '/?module=lunome&action=movie/poster/upload&id='+movieId,
            secureuri       : false,
            fileElementId   : 'movie-poster-file',
            dataType        : 'text',
            success         : function (data, status){
                $('#movie-poster-file').parent()
                .empty()
                .html('<input type="file" name="poster" id="movie-poster-file">');
                $('#movie-posters-add-dialog').modal('hide');
                $('#movie-posters-container').attr('data-page',2);
                $('#movie-posters-prev-page').trigger('click');
            },
            error           : function (data, status, e){
                $('#movie-posters-add-dialog').modal('hide');
            }
        });
    });
    
    function getPosters( ) {
        $.get('/?module=lunome&action=movie/poster/index', {
            id   : $('#movie-posters-container').attr('data-movie-id'),
            page : $('#movie-posters-container').attr('data-page')
        }, function( response ) {
            if (0 == response.length ) {
                $('#movie-posters-prev-page').trigger('click');
                return;
            }
            $('#movie-posters-container-items').empty();
            for( var i in response ) {
                $('#movie-posters-container-items').append(
                    $('<img>')
                    .attr('src', response[i].url)
                    .addClass('img-thumbnail')
                    .addClass('margin-5')
                    .width(100)
                    .height(150)
                    .click(function() {
                        $('#movie-poster-view-container').attr('src', $(this).attr('src'));
                        $('#movie-posters-view-dialog').modal('show');
                    })
                );
            }
        }, 'json');
    }
    
    $('#movie-postes-prev-page').addClass('disabled');
    
    $('#movie-posters-prev-page').click(function() {
        var currentPage = $('#movie-posters-container').attr('data-page')*1;
        if ( 0>=currentPage-1 ) {
            return false;
        }
        
        if ( 1>=currentPage-1 ) {
            $(this).addClass('disabled');
        }
        
        $('#movie-posters-container').attr('data-page',currentPage-1);
        getPosters();
        return false;
    });
    
    $('#movie-posters-next-page').click(function() {
        $('#movie-posters-container').attr('data-page', 
            $('#movie-posters-container').attr('data-page')*1+1
        );
        $('#movie-posters-prev-page').removeClass('disabled');
        getPosters();
        return false;
    });
    /* 海报管理结束 */
    
    /**
     * @returns
     */
    function getClassicDialogues( ) {
        $.get('/?module=lunome&action=movie/classicDialogue/index', {
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
    
    $('#movie-classic-dialogues-save').click(function() {
        var content = $('#movie-classic-dialogues-content').val();
        $.post('/?module=lunome&action=movie/classicDialogue/add', {
            id : $('#movie-classic-dialogues-container').attr('data-movie-id'),
            content : content
        }, function( response ) {
            $('#movie-classic-dialogues-content').val('');
            $('#movie-classic-dialogues-add-dialog').modal('hide');
            $('#movie-classic-dialogues-container').attr('data-page',2);
            $('#movie-classic-dialogues-prev-page').trigger('click');
        }, 'json');
    });
    
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var target = $(e.target);
        var containerId = target.attr('href');
        if ( '#movie-classic-dialogues-container' == containerId ) {
            return;
        }
        
        console.log(containerId);
        
        switch ( containerId ) {
        case '#movie-posters-container' :
            var isInited = $('#movie-posters-container').attr('data-is-inited');
            if ( 'undefined' == typeof(isInited) ) {
                getPosters();
                $('#movie-posters-container').attr('data-is-inited', 'true');
            }
            break;
        case '#movie-characters-container' :
            var isInited = $('#movie-characters-container').attr('data-is-inited');
            if ( 'undefined' == typeof(isInited) ) {
                getCharacters();
                $('#movie-characters-container').attr('data-is-inited', 'true');
            }
            break;
        default:
            break;
        }
    })
    
    
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