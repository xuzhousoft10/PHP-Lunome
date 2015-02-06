$(document).ready(function() {
    /**
     * 评论管理。
     */
    function ShortCommentManager ( containerId ) {
        this.container     = $(containerId);
        this.isGuestUser   = this.container.attr('data-is-guest-user');
        this.userNickName  = this.container.attr('data-user-nickname');
        this.userPhoto     = this.container.attr('data-user-photo');
        this.mediaId       = this.container.attr('data-media-id');
        this.listContainer = $(this.container.attr('data-container'));
        this.loadingImage  = $('<div>').addClass('width-full').addClass('text-center').append($('<img>').attr('src', this.container.attr('data-loadding-image')));
        
        this._init();
    }
    
    /**
     * Init the comment list.
     * @returns void
     */
    ShortCommentManager.prototype._init = function() {
        var button = this.container.find('button');
        var $this = this;
        button.click(function() {
            var form = $($(this)[0].form);
            var data = form.serializeArray();
            var postData = {};
            for ( var i in data ) {
                postData[data[i].name] = data[i].value;
            }
            
            $.post(form.attr('action'), postData, function( response ) {
                form[0].reset();
                $this.load($this.container.attr('data-index-url'));
            }, 'text');
        });
        this.load(this.container.attr('data-index-url'));
    };
    
    /**
     * Load comments
     * @returns void
     */
    ShortCommentManager.prototype.load = function( url ) {
        var $this = this;
        $this.listContainer.empty().append($this.loadingImage);
        $.get(url, {}, function(response) {
            $('.movie-comments-container-pager').unbind('click');
            $this.listContainer.empty();
            $this.listContainer.html(response);
            $('.movie-comments-container-pager').click(function() {
                $this.load($(this).attr('href'));
                return false;
            });
        }, 'text');
    };
    
    /**
     * 初始化评论列表。
     * @returns void
     */
    var shortCommentManager = new ShortCommentManager('#movie-short-comment-container');
    
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
    
    /**
     *  绑定事件到在线观看按钮 
     */
    var playerTrigger = $('[data-online-play-trigger="true"]');
    $(playerTrigger.attr('data-player-container')).hide();
    playerTrigger.click(function() {
        var playerStatus = playerTrigger.attr('data-player-status');
        var container = $(playerTrigger.attr('data-player-container'));
        
        if ( 'undefined' != typeof playerStatus && 'open' == playerStatus ) {
            /* 如果播放器处于打开状态， 则关闭。*/
            container.empty().hide();
            container.next().remove();
            playerTrigger.attr('data-player-status', 'closed');
            playerTrigger.text('在线观看');
            return;
        }
        
        playerTrigger.attr('data-player-status', 'open');
        playerTrigger.text('关闭播放器');
        
        /* 显示等待控制条。 */
        var loaddingImg = playerTrigger.attr('data-loadding-img');
        var assetsPath = playerTrigger.attr('data-assets-path');
        
        container
        .height(500)
        .append('<img src="'+loaddingImg+'">')
        .addClass('text-center')
        .css({
            'line-height'   : '500px', 
            'box-shadow'    : '0px 1px 2px rgba(0, 0, 0, 0.075)',
            'border'        : '1px solid #DDD',
            'border-radius' : '4px',
            'transition'    : 'all 0.2s ease-in-out 0s'
        })
        .show()
        .after('<hr>');
        
        $.get(playerTrigger.attr('data-global-search-url'), {
            name:playerTrigger.attr('data-movie-name')
        }, function( response ) {
            $('.global-search-result-item').unbind('click');
            container.html(response).css('line-height','1em');
            $('.global-search-result-item').click(function() {
                globalSearchResultItem(this, container);
                return false;
            });
        }, 'text');
    });
    
    /**
     * Handle the click event of result items.
     * @returns void
     */
    var globalSearchResultItem = function( trigger, container ) {
        var $this = trigger;
        $.get('/?module=lunome&action=movie/play', {
            source : $(trigger).attr('data-source'),
            link   : $(trigger).attr('data-link')
        }, function( response ) {
            container.children().hide();
            
            var playerContainer = $('<div>');
            var returnBackToSearchResult = $('<a>')
            .addClass('pull-left')
            .attr('href', '#')
            .html('<span class="glyphicon glyphicon-hand-left"></span>返回搜索结果')
            .click(function() {
                playerContainer.empty().remove();
                container.children().show();
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
    };
    
    /**
     * 初始化标记帐号列表
     */
    var popovers = $('.detail-marked-account-list');
    for ( var i in popovers ) {
        popovers.eq(i).attr('attr-target', '#detail-marked-account-list-'+i)
        .popover({
            content   : '<div id="detail-marked-account-list-'+i+'" class="text-center" style="width:200px"><img src="'+popovers.eq(i).attr('data-loadding-img')+'"></div>',
            placement : 'bottom',
            html      : true,
        })
        .on('shown.bs.popover', function () {
            var $this = $(this);
            $.get('/?module=lunome&action=movie/markedUserList', {
                id    : $this.attr('data-id'),
                mark  : $this.attr('data-mark'),
                scope : $this.attr('data-scope'),
            }, function( response ) {
                var container = $($this.attr('attr-target'));
                container.removeClass('text-center').html(response);
                container.parent().css('padding','0');
            }, 'text');
        });
    }
});