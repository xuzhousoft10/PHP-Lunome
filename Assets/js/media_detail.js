$(document).ready(function() {
    /**
     * @returns void
     */
    function ResourceManager ( managerId ) {
        this.mangerId = managerId;
        this.manager = $(managerId);
        this.itemContainer = null;
        this.afterResourceLoaded = function(){};
        
        this._init();
    }
    
    /**
     * Init the resource manager.
     * @returns void
     */
    ResourceManager.prototype._init = function() {
        this.itemContainer = $('<div>').attr('id', this.mangerId+'-items');
        this.manager.append('<br>').append(this.itemContainer);
        this._loadResources(this.manager.attr('data-index-url'));
    };
    
    /**
     * Refresh the management
     * @returns void
     */
    ResourceManager.prototype.refresh = function() {
        this._loadResources(this.manager.attr('data-index-url'));
    };
    
    /**
     * Load resources by url.
     * @returns void
     */
    ResourceManager.prototype._loadResources = function( url ) {
        var $this = this;
        $this.itemContainer.empty();
        var pager = '.'+$this.mangerId.replace('#','')+'-pager';
        $(pager).unbind('click');
        var loadding = $('<img>').attr('src', $this.manager.attr('data-loadding-image'));
        $this.itemContainer.addClass('text-center').append(loadding);
        $.get(url, {}, function( response ) {
            $this.itemContainer.removeClass('text-center');
            $this.itemContainer.html(response);
            $(pager).click(function() {$this._pagerClicked(this); return false;});
            $this.afterResourceLoaded();
        }, 'text');
    };
    
    /**
     * Handler pager click event.
     * @returns void
     */
    ResourceManager.prototype._pagerClicked = function( trigger ) {
        trigger = $(trigger);
        if ( '#' == trigger.attr('href') ) {
            return;
        }
        this._loadResources(trigger.attr('href'));
    };
    
    /**
     * Defaine the poster item handler to open the poster after click.
     * @returns void
     */
    var posterItemClickHandler = function() {
        $('.movie-poster-item').click(function() {
            $('#movie-poster-view-container').attr('src', $(this).attr('src'));
            $('#movie-posters-view-dialog').modal('show');
        });
    };
    
    /**
     * Validate the data for classic dialogue.
     * @returns boolean
     */
    var classicDialogueValidator = function( data ) {
        if ( 0 >= $.trim(data.content).length ) {
            alert('内容不能为空啊～～～');
            return false;
        } else if ( 1000 <= $.trim(data.content).length ) {
            alert('太长了，字数不要超过1000， OK？');
            return false;
        }
        return true;
    }
    
    /**
     * Validate the data for add poster
     * @returns boolean
     */
    var posterValidator = function( data, file ) {
        if ( '' === file.val() ) {
            alert('没有要保存的海报文件。');
            return false;
        }
        if ( !(/(jpg|png)/.test(file.val())) ) {
            alert('海报文件仅支持JPG和PNG格式文件。');
            return false;
        }
        return true;
    };
    
    /**
     * Validate the data for add character
     * @returnes boolean
     */
    var characterValidator = function( data, file ) {
        if ( 0 == $.trim(data['character[name]']).length ) {
            alert('角色名称不能为空。');
            return false;
        }
        
        if ( !(/(jpg|png)/.test(file.val())) ) {
            alert('头像仅支持JPG和PNG格式文件。');
            return false;
        }
        
        return true;
    };
    
    /**
     * Resource managers.
     */
    var resourceManagers = {
        character : {manager:null, afterResourceLoaded:function(){}, validator:characterValidator},
        poster    : {manager:null, afterResourceLoaded:posterItemClickHandler, validator:posterValidator},
        dialogue  : {manager:new ResourceManager('#movie-classic-dialogues-container'), afterResourceLoaded:function(){}, validator:classicDialogueValidator}
    };
    
    /**
     * manage the tab, load the resource when the table is actived.
     */
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var target = $(e.target);
        var containerId = target.attr('href');
        if ( '#movie-classic-dialogues-container' == containerId ) {
            return;
        }
        
        var resourceType = $(containerId).attr('data-resource-type');
        if ( null === resourceManagers[resourceType].manager ) {
            resourceManagers[resourceType].manager = new ResourceManager(containerId);
            resourceManagers[resourceType].manager.afterResourceLoaded = resourceManagers[resourceType].afterResourceLoaded;
        }
    });
    
    /**
     * Handle the dialog save button click event
     * @returns void
     */
    $('.dialog-save-button').click(function() {
        var form = $($(this)[0].form);
        var data = form.serializeArray();
        var postData = {};
        for ( var i in data ) {
            postData[data[i].name] = data[i].value;
        }
        
        var file = form.find('input[type="file"]');
        if ( false == resourceManagers[form.attr('data-resource-type')].validator(postData, file) ) {
            return false;
        }
        
        var loaddingImage = $('<img>').attr('src', form.attr('data-loadding-image'));
        form.find('.modal-body').children().hide();
        form.find('.modal-body').addClass('text-center').append(loaddingImage);
        
        var afterFormCommited = function() {
            form.find('.modal-body').removeClass('text-center');
            loaddingImage.remove();
            form.find('.modal-body').children().show();
            form[0].reset();
            $(form.attr('data-dialog')).modal('hide');
            resourceManagers[form.attr('data-resource-type')].manager.refresh();
        }
        
        if ( 0 == file.length ) {
            $.post(form.attr('action'), postData, function( response ) {
                afterFormCommited(response);
            }, 'text');
        } else {
            $.ajaxFileUpload({
                url             : form.attr('action'),
                data            : postData,
                secureuri       : false,
                fileElementId   : file.attr('id'),
                dataType        : 'text',
                success         : function (data, status){
                    afterFormCommited(data);
                },
                error           : function (data, status, e){
                    afterFormCommited(data);
                }
            });
        }
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