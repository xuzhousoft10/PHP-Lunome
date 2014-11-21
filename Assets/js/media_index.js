/**
 * 本文件用来实现列表页面的数据自动加载以及项目的标记。
 * 使用该对象需要定义一个ID为“media-index-parameters”的元素，
 * 并且降配置信息作为该元素的属性。
 * 以下是其他需要定义的元素：
 * #media-name-search-text   : 该元素用来保存查询项目的名称。
 * #media-name-search-button ： 当该元素被点击后， 执行查询项目名称的操作。
 * 
 * @author  Michael Luthor
 * @version 0.0.0
 */
var MediaIndex = {
    url             : null, /* 列表数据加载使用的URL地址。 */
    detailURL       : null, /* 项目详细界面的URL地址。 */
    markURL         : null, /* 用来标记项目的URL地址。 */
    loadedCount     : 0,    /* 以加载项目的数量。 */
    totalCount      : 0,    /* 所有项目的数量。 */
    container       : null, /* 用来放置列表项目的块元素选择符。 */
    pageSize        : 20,   /* 每次请求加载项目的数量。 */
    marks           : [],   /* 每个项目的标记动作列表。 */
    maxAutoLoadCount: 10,   /* 自动加载次数， 当超过时， 需要手动加载一次。 */
    currentMark     : null, /* 当前该列表里的项目处于的标记状态。 */
    _isLoading      : false,/* 标记当前时刻是否正在加载数据。 */
    _autoLoadCount  : 0,    /* 记录当前自动加载的次数。 */
    _conditions     : {},   /* 保存当前查询的条件。 */
    waitingImage    : null, /* 当项目出现较长时间的处理时，显示的等待图片。 */
};

/**
 * 初始化当前项目列表对象，并在初始化完成后加载首批项目。
 * @returns void
 */
MediaIndex.init = function() {
    var parameters      = $('#media-index-parameters');
    this.url            = parameters.attr('data-url');
    this.detailURL      = parameters.attr('data-detail-url');
    this.totalCount     = parameters.attr('data-total');
    this.container      = parameters.attr('data-container');
    this.pageSize       = parameters.attr('data-pagesize');
    this.marks          = $.parseJSON(parameters.attr('data-marks'));
    this.markURL        = parameters.attr('data-mark-url');
    this.currentMark    = parameters.attr('data-current-mark');
    this.waitingImage   = parameters.attr('data-waiting-image');
    $(window).bind('scroll', MediaIndex._windowScrollEventHandler);
    $('#media-name-search-button').click(function() {
        var name = $.trim($('#media-name-search-text').val());
        if ( 0 == name.length ) {
            if ( 'undefined' != typeof MediaIndex._conditions.name ) {
                delete MediaIndex._conditions.name;
            }
        } else {
            MediaIndex._conditions.name = '*'+name;
        }
        MediaIndex.reload();
    });
    this.load();
};

/**
 * window滚动事件的处理方法。 当滚动条距离底部达到一定距离后，将会加载下一批项目，
 * 但是如果已经全部加载或者自动加载次数达到上限时，则不执行加载动作。
 * @returns void
 */
MediaIndex._windowScrollEventHandler = function() {
    if ( MediaIndex._autoLoadCount >= MediaIndex.maxAutoLoadCount ) {
        return true;
    }
    
    if ( true == MediaIndex._isLoading ) {
        return true;
    }
    
    var lenthToEnd = this.scrollMaxY - this.scrollY;
    if ( lenthToEnd > 200 ) {
        return true;
    }
    MediaIndex.load();
};

/**
 * 刷新当前项目列表并重新加载所有项目。
 * @returns void
 */
MediaIndex.reload = function() {
    $(this.container).empty();
    this.totalCount = 0;
    this.loadedCount = 0;
    this._autoLoadCount = 0;
    this.load(true);
};

/**
 * 加载更多项目到当前列表中， 如果项目已经全部被加载， 则方法不做任何处理。
 * 该方法不建议在外部调用， 因为他会增加自动加载的次数。
 * @returns void
 */
MediaIndex.load = function( refresh ) {
    var $this = this;
    var shouldLoad = $this.totalCount*1 > $this.loadedCount*1;
    shouldLoad = shouldLoad || ( 'undefined'!=refresh && true==refresh );
    if ( !shouldLoad ) {
        return true; /* No more medias. */
    }
    
    $this._isLoading = true;
    $this._autoLoadCount ++;
    $.post($this.url, {
        condition   : $this._conditions,
        position    : $this.loadedCount,
        length      : $this.pageSize,
    }, function( response ) {
        $this.totalCount = response.count;
        $this._InsertMediasIntoContainer(response.medias);
        $this.loadedCount += response.medias.length;
        $this._isLoading = false;
    }, 'json');
};

/**
 * 将请求过来的项目列表加载到当前列表中。
 * @returns void
 */
MediaIndex._InsertMediasIntoContainer = function( medias ) {
    var sign = 'item-'+(new Date()).getTime();
    for ( var i in medias ) {
        this._InsertMediaIntoContainer(medias[i], sign);
    }
    $('.'+sign).waypoint(MediaIndex._loadMediaCoverOnVisible, {offset:'100%'});
    if ( this._autoLoadCount >= this.maxAutoLoadCount ) {
        $('<div class="alert alert-info pull-left text-center" style="width:100%;cursor:pointer"></div>')
        .html('显示更多')
        .click(function() {
            $(this).remove();
            MediaIndex._autoLoadCount = 0;
            MediaIndex.load();
        })
        .appendTo(this.container);
    }
};

/**
 * 将单个项目插入到列表中。
 * @returns void
 */
MediaIndex._InsertMediaIntoContainer = function( media, sign ) {
    var itemContainer = $('<div>')
        .attr('class', 'pull-left lnm-media-list-item-container')
        .appendTo(this.container);
    var coverContainer = $('<div>')
        .addClass('lnm-media-list-item')
        .addClass(sign)
        .attr('data-cover-url', media.cover)
        .mouseenter(function() {
            $(this).children().show();
        })
        .mouseleave(function() {
            $(this).children().hide();
        })
        .appendTo(itemContainer);
    var introContainer = $('<div>')
        .addClass('lnm-media-list-item-intro-area')
        .attr('data-detail-url', this.detailURL.replace('{id}', media.id))
        .html(media.introduction)
        .click(function() {
            window.open($(this).attr('data-detail-url'));
        })
        .appendTo(coverContainer);
    var markContainer = $('<div>')
        .attr('class', 'btn-group btn-group-justified lnm-media-list-item-mark-container')
        .appendTo(coverContainer);
    for ( var markCode in this.marks ) {
        
        this._generateMarkButton({
            code    : markCode,
            name    : this.marks[markCode].name,
            style    : this.marks[markCode].style,
        }, media).appendTo(markContainer);
    }
    var nameContainer = $('<div>')
        .addClass('white-space-nowrap')
        .html('<strong>'+media.name+'<strong>')
        .appendTo(itemContainer);
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
};

/**
 * 当项目处于可见状态的事件处理方法。
 * @returns void
 */
MediaIndex._loadMediaCoverOnVisible = function(direction) {
    var isLoaded = $(this).attr('data-cover-loaded');
    if ( 'undefined' == typeof(isLoaded) ) {
         console.log('Loading cover for :'+$(this).next().text());
         var cover = $(this).attr('data-cover-url');
         $(this).css('background-image', 'url("'+cover+'")');
         $(this).attr('data-cover-loaded', true);
    }
}

/**
 * 当文档处理完成后， 立即初始化列表。
 * @returns void
 */
$(document).ready(function() {
    MediaIndex.init();
});