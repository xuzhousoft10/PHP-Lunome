/**
 * 本文件用来实现列表页面的数据自动加载以及项目的标记。
 * 使用该对象需要定义一个ID为“media-index-parameters”的元素，
 * 并且降配置信息作为该元素的属性。
 * 以下是其他需要定义的元素：
 * #media-name-search-text   : 该元素用来保存查询项目的名称。
 * #media-name-search-button ： 当该元素被点击后， 执行查询项目名称的操作。
 * 
 * @author  Michael Luthor
 * @license LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 */
var MediaIndex = {
    url                    : null, /* 列表数据加载使用的URL地址, 即查询电影的处理地址。 */
    detailURL              : null, /* 项目详细界面的URL地址。 */
    markURL                : null, /* 用来标记项目的URL地址。 */
    loadedCount            : 0,    /* 以加载项目的数量。 */
    totalCount             : 0,    /* 所有项目的数量。 */
    container              : null, /* 用来放置列表项目的块元素选择符。 */
    pageSize               : 20,   /* 每次请求加载项目的数量。 */
    maxAutoLoadCount       : 3,   /* 自动加载次数， 当超过时， 需要手动加载一次。 */
    currentMark            : null, /* 当前该列表里的项目处于的标记状态。 */
    _isLoading             : false,/* 标记当前时刻是否正在加载数据。 */
    _autoLoadCount         : 0,    /* 记录当前自动加载的次数。 */
    _conditions            : {},   /* 保存当前查询的条件。 */
    _autoLoadTriggerLenght : 200,
    waitingImage           : null, /* 当项目出现较长时间的处理时，显示的等待图片。 */
    loaddingImage          : null, /* 加载项目时显示的图片链接。 */
    isDebug                : false,/* 是否处于调试模式。 */ 
    loadMoreBtnTemplate    : null, /* 加载更多按钮的显示模板。 */
    prevResultBtnTemplate  : null, /* 显示之前搜索结果按钮的模板 */
    watchedMark            : null, /* 已看标记的标记码。 */
};

/**
 * 初始化当前项目列表对象，并在初始化完成后加载首批项目。
 * @returns void
 */
MediaIndex.init = function() {
    var parameters             = $('#media-index-parameters');
    this.url                   = parameters.attr('data-url');
    this.detailURL             = parameters.attr('data-detail-url');
    this.totalCount            = parameters.attr('data-total');
    this.container             = parameters.attr('data-container');
    this.pageSize              = parameters.attr('data-pagesize');
    this.markURL               = parameters.attr('data-mark-url');
    this.currentMark           = parameters.attr('data-current-mark');
    this.waitingImage          = parameters.attr('data-waiting-image');
    this.loaddingImage         = parameters.attr('data-loading-image');
    this.isDebug               = parameters.attr('data-is-debug');
    this.loadMoreBtnTemplate   = parameters.attr('data-load-more-btn');
    this.prevResultBtnTemplate = parameters.attr('data-prev-result-btn');
    this.watchedMark           = parameters.attr('data-watched-mark');
    this.maxAutoLoadCount      = parameters.attr('data-max-auto-load-count');
    
    /* 检查初始化查询参数。 */
    var initQuery = $.parseJSON(parameters.attr('data-init-query'));
    var conditionInited = false;
    for( var i in initQuery ) {
        this._conditions[i] = initQuery[i];
        conditionInited = true;
    }
    
    /* 如果没有查询参数， 则尝试使用上次查询的条件。 */
    if ( !conditionInited && MediaIndex.hasHistory() ) {
        var history = MediaIndex.getHistory();
        this._conditions = history.condition;
        this.loadedCount = history.loadedCount-(this.pageSize/2);
        if ( 0 > this.loadedCount ) {
            this.loadedCount = 0;
        }
        /* 如果存在历史查询结果， 则显示显示全部结果按钮来显示之前的查询按钮。 */
        if ( 0 < this.loadedCount ) {
            $(this.prevResultBtnTemplate).click(function() {
                MediaIndex.reload();
            }).appendTo(this.container);
        }
    }
    
    /* 绑定窗口滚动事件用于实现封面的延迟加载。 */
    $(window).bind('scroll', MediaIndex._windowScrollEventHandler);
    
    /* 当搜索按钮点击时，执行查询。 */
    $('#media-name-search-button').click(function() {
        var name = $.trim($('#media-name-search-text').val());
        if ( 0 == name.length ) {
            /* 如果名称为空， 则清空名称查询条件。 */
            if ( 'undefined' != typeof MediaIndex._conditions.name ) {
                delete MediaIndex._conditions.name;
            }
        } else {
            MediaIndex._conditions.name = name;
        }
        MediaIndex.reload();
    });
    this.load();
};

/**
 * 检查是否存在查询历史。
 * @returns boolean
 */
MediaIndex.hasHistory = function() {
    var history = $.cookie('search-condition-'+this.currentMark);
    return 'undefined' != typeof(history);
};

/**
 * 获取查询条件历史。
 * @returns object|null
 */
MediaIndex.getHistory = function() {
    var history = $.cookie('search-condition-'+this.currentMark);
    return ('undefined' == typeof(history)) ? null : $.parseJSON(history);
};

/**
 * 更新查询历史记录。 保存当前查询条件到历史记录。
 * @returns void
 */
MediaIndex._updateHistory = function() {
    var history = {
        condition   : this._conditions,
        loadedCount : this.loadedCount,
    };
    $.cookie('search-condition-'+this.currentMark,JSON.stringify(history),{ expires:1});
};

/**
 * window滚动事件的处理方法。 当滚动条距离底部达到一定距离后，将会加载下一批项目，
 * 但是如果已经全部加载或者自动加载次数达到上限时，则不执行加载动作。
 * @returns void
 */
MediaIndex._windowScrollEventHandler = function() {
    var lengthToEnd = this.scrollMaxY - this.scrollY;
    
    if ( (MediaIndex._autoLoadCount>=MediaIndex.maxAutoLoadCount)
    || (true==MediaIndex._isLoading)
    || (lengthToEnd>MediaIndex._autoLoadTriggerLenght)
    ) {
        return true;
    }
    
    MediaIndex.load();
};

/**
 * 刷新当前项目列表并重新加载所有项目。
 * @returns void
 */
MediaIndex.reload = function() {
    this.clear();
    this.load(true);
};

/**
 * 清楚搜索结果和历史。
 * @returns void
 */
MediaIndex.clear = function() {
    this.totalCount = 0;
    this.loadedCount = 0;
    this._autoLoadCount = 0;
    $(this.container).empty();
    this._updateHistory();
};

/**
 * 加载更多项目到当前列表中， 如果项目已经全部被加载， 则方法不做任何处理。
 * 该方法不建议在外部调用， 因为他会增加自动加载的次数。
 * 刷新的原因是在外部改变了加载计数或者以加载数量， 这种情况下可以强制加载。
 * @param bool refresh 是否强制刷新结果。 默认为false。
 * @returns void
 */
MediaIndex.load = function( refresh ) {
    var $this = this;
    var shouldLoad = $this.totalCount*1 > $this.loadedCount*1;
    shouldLoad = shouldLoad || ( 'undefined'!=refresh && true==refresh );
    if ( !shouldLoad ) {
        return true;
    }
    
    $this._isLoading = true;
    $this._autoLoadCount ++;
    MediaIndex._log('Start To Load Medias [Position: '+$this.loadedCount+' Condition:'+JSON.stringify(this._conditions)+']');
    var loaddingBar = $('<div>').addClass('row').addClass('pull-left').width('100%').addClass('text-center').addClass('padding-20').appendTo(this.container);
    $('<img>').attr('src', this.loaddingImage).appendTo(loaddingBar);
    
    $.post($this.url, {
        condition   : $this._conditions,
        position    : $this.loadedCount,
        length      : $this.pageSize,
    }, function( response ) {
        $this.totalCount = response.count;
        MediaIndex._log('Done Loading Medias. ('+response.medias.length+' Loaded)');
        
        $($this.container).append(response.medias);
        MediaIndex._log('Done Render Medias.');
        
        $('.'+response.sign)
        .mouseenter(function() {
            $(this).children().show();
        })
        .mouseleave(function() {
            $(this).children().hide();
        })
        .waypoint(MediaIndex._loadMediaCoverOnVisible, {offset:'100%'});
        
        $('.'+response.sign).children('.lnm-media-list-item-intro-area').click(function() {
            window.open($(this).attr('data-detail-url'));
        });
        
        /* 绑定标记按钮。 */
        $('.btn-mark-action-'+response.sign).click(function() {
            var button = $(this);
            var markCode = button.attr('data-mark-code');
            var mediaId  = button.attr('data-media-id');
            var url = MediaIndex.markURL.replace('{id}', mediaId).replace('{mark}', markCode);
            var container = button.parent().parent().parent().empty();
            container.append($('<img>').attr('src', MediaIndex.waitingImage).height(300).width(200));
            $.get(url, {}, function() {
                container.fadeOut(500, function() {
                    $('#mark-counter-'+markCode).html($('#mark-counter-'+markCode).text()*1+1);
                    $('#mark-counter-'+MediaIndex.currentMark).html($('#mark-counter-'+MediaIndex.currentMark).text()*1-1);
                    /* 如果剩余的项目过少， 则加载更多项目。 */
                    $(window).trigger('scroll');
                    /* 刷新waypoints以解决原本不在显示区，在用户标记一个项目后，因位置提前而应该加载封面的问题。 */
                    $.waypoints('refresh');
                });
            }, 'text');
            return false;
        });
        
        /* 绑定评分 */
        $('.rate-it-container-'+response.sign).each(function() {
            $(this)
            .rateit({max:10,step:1,resetable:false, value:$(this).attr('data-score')})
            .bind('over', function (event, value) {
                value = parseInt(value);
                if ( 0 >= value ) {
                    value = 1;
                }
                var titles = ['没救了','太差','很差','差','还行','很棒','非常棒','棒级了','超级棒','极品'];
                $(this).attr('title', titles[value-1]); 
            })
            .bind('rated', function (e) {
                var ri = $(this);
                $.get('/?module=lunome&action=movie/rate', {
                    id:ri.attr('data-media-id'),
                    score:ri.rateit('value'),
                }, function( response ) {
                    
                }, 'json');
            });
        });
        
        /* 当到达最大自动加载次数时， 显示手动加载按钮。 */
        if ( $this._autoLoadCount >= $this.maxAutoLoadCount ) {
            $(MediaIndex.loadMoreBtnTemplate).click(function() {
                $(this).remove();
                MediaIndex._autoLoadCount = 0;
                MediaIndex.load();
            }).appendTo($this.container);
        }
        
        $this.loadedCount += response.mediaCount;
        loaddingBar.remove();
        MediaIndex._updateHistory();
        
        $this._isLoading = false;
    }, 'json');
};

/**
 * 生成一个标记按钮。
 * @returns jQuery
 */
MediaIndex._generateMarkButton = function(mark, media) {
    return $('<a>')
    .addClass('btn')
    .addClass('btn-'+mark.style)
    .html(mark.name)
    .attr('data-media-id', media.id)
    .attr('data-mark-code', mark.code)
    .attr('href', '#')
    .click(function() {
        
    });
};

/**
 * 当项目处于可见状态的事件处理方法。 这里主要是加载电影封面图片。
 * @param direction
 * @returns void
 */
MediaIndex._loadMediaCoverOnVisible = function(direction) {
    var isLoaded = $(this).attr('data-cover-loaded');
    if ( 'undefined' == typeof(isLoaded) ) {
        MediaIndex._log('Load Cover For ['+$.trim($(this).next().text())+']'); 
        var cover = $(this).attr('data-cover-url');
        $(this).css('background-image', 'url("'+cover+'")');
        $(this).attr('data-cover-loaded', true);
    }
};

/**
 * 增加搜索条件并刷新项目列表。
 * @returns void
 */
MediaIndex.addCondition = function( attr, value ) {
    MediaIndex._conditions[attr] = value;
    MediaIndex.reload();
};

/**
 * 删除搜索条件并刷新项目列表。
 * @returns void
 */
MediaIndex.deleteCondition = function (attr, value) {
    delete MediaIndex._conditions[attr];
    MediaIndex.reload();
};

/**
 * 记录日志到控制台。 该方法仅在调试模式下起作用。
 * @returns void
 */
MediaIndex._log = function ( message ) {
    if ( 'true' != this.isDebug ) {
        return;
    }
    $.log(message, 'MediaIndexLoader');
};

/**
 * 当文档处理完成后， 立即初始化列表。
 * @returns void
 */
$(document).ready(function() {
    $('html,body').animate({scrollTop:'0px'});
    var doSearch = false;
    /* 当标签被点击时， 执行查询。 */
    $('.media-search-condition-label').click(function() {
        var label = $(this);
        var attr = label.attr('data-attr');
        var value = label.attr('data-value');
        
        $('.media-search-condition-label[data-attr="'+attr+'"]').removeClass('label').removeClass('label-primary');
        $('.media-search-condition-select[data-attr="'+attr+'"]').val('');
        label.addClass('label').addClass('label-primary');
        
        if ( !doSearch ) {
            return;
        }
        
        if ( '' == value ) {
            MediaIndex.deleteCondition(attr, value);
        } else {
            MediaIndex.addCondition(attr, value);
        }
    }).css('cursor', 'pointer');
    
    /* 默认情况下为所有 */
    $('.media-search-condition-label[data-value=""]').addClass('label').addClass('label-primary');
    
    /* 当条件选择改变时， 执行查询。 */
    $('.media-search-condition-select').change(function() {
        var selector = $(this);
        var attr = selector.attr('data-attr');
        var value = selector.val();
        $('.media-search-condition-label[data-attr="'+attr+'"]').removeClass('label').removeClass('label-primary');
        
        if ( !doSearch ) {
            return;
        }
        
        if ( '' == value ) {
            $('.media-search-condition-label[data-value=""][data-attr="'+attr+'"]').addClass('label').addClass('label-primary');
            MediaIndex.deleteCondition(attr, value);
        } else {
            MediaIndex.addCondition(attr, value);
        }
    });
    
    /* 初始化搜索条件, 如果url中的搜索条件不为空， 则使用URL中的条件， 并清空历史查询。 */
    var parameters      = $('#media-index-parameters');
    var initQuery = $.parseJSON(parameters.attr('data-init-query'));
    var isConditionInited = false;
    MediaIndex.clear();
    for( var i in initQuery ) {
        isConditionInited = true;
        if ( 'name' != i ) {
            var item = $('.media-search-condition-label[data-value="'+initQuery[i]+'"][data-attr="'+i+'"]');
            if ( 0 == item.length ) {
                $('.media-search-condition-select[data-attr="'+i+'"]').val(initQuery[i]).trigger('change');
            } else {
                item.trigger('click');
            }
        } else {
            $('#media-name-search-text').val(initQuery.name);
        }
    }
    
    MediaIndex.init();
    if ( !isConditionInited && MediaIndex.hasHistory() ) {
        var history = MediaIndex.getHistory();
        for ( var i in history.condition ) {
            if ( 'name' != i ) {
                var item = $('.media-search-condition-label[data-value="'+history.condition[i]+'"][data-attr="'+i+'"]');
                if ( 0 == item.length ) {
                    $('.media-search-condition-select[data-attr="'+i+'"]').val(history.condition[i]).trigger('change');
                } else {
                    item.trigger('click');
                }
            } else {
                $('#media-name-search-text').val(history.condition.name);
            }
        }
    }
    
    doSearch = true;
});