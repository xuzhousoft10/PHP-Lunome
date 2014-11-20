var MediaIndex = {
	url 			: null,
	detailURL		: null,
	markURL			: null,
	loadedCount 	: 0,
	totalCount  	: 0,
	container 		: null, 
	pageSize		: 20,
	marks			: [],
	maxAutoLoadCount: 10,
	currentMark		: null,
	_isLoading		: false,
	_autoLoadCount	: 0,
};

MediaIndex.init = function() {
	var parameters	= $('#media-index-parameters');
	this.url 		= parameters.attr('data-url');
	this.detailURL	= parameters.attr('data-detail-url');
	this.totalCount	= parameters.attr('data-total');
	this.container	= parameters.attr('data-container');
	this.pageSize 	= parameters.attr('data-pagesize');
	this.marks		= $.parseJSON(parameters.attr('data-marks'));
	this.markURL	= parameters.attr('data-mark-url');
	this.currentMark= parameters.attr('data-current-mark');
	$(window).bind('scroll', MediaIndex._windowScrollEventHandler);
	this.load();
};

MediaIndex.load = function() {
	var $this = this;
	if ( $this.totalCount*1 <= $this.loadedCount*1 ) {
		return true; /* No more medias. */
	}
	$this._isLoading = true;
	$this._autoLoadCount ++;
	console.log('Loading list : position='+$this.loadedCount+' size='+$this.pageSize);
	$.post($this.url, {
		condition 	: [],
		position	: $this.loadedCount,
		length		: $this.pageSize,
	}, function( response ) {
		$this._InsertMediasIntoContainer(response);
		$this.loadedCount += response.length;
		$this._isLoading = false;
	}, 'json');
};

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
			code	: markCode,
			name	: this.marks[markCode].name,
			style	: this.marks[markCode].style,
		}, media).appendTo(markContainer);
	}
	var nameContainer = $('<div>')
		.addClass('white-space-nowrap')
		.html('<strong>'+media.name+'<strong>')
		.appendTo(itemContainer);
};

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
		$.get(url, {}, function() {
			button.parent().parent().parent().empty().remove();
			$('#mark-counter-'+markCode).html($('#mark-counter-'+markCode).text()*1+1);
			$('#mark-counter-'+MediaIndex.currentMark).html($('#mark-counter-'+MediaIndex.currentMark).text()*1-1);
		}, 'text');
		return false;
	});
};

MediaIndex._loadMediaCoverOnVisible = function(direction) {
	var isLoaded = $(this).attr('data-cover-loaded');
    if ( 'undefined' == typeof(isLoaded) ) {
         console.log('Loading cover for :'+$(this).next().text());
         var cover = $(this).attr('data-cover-url');
         $(this).css('background-image', 'url("'+cover+'")');
         $(this).attr('data-cover-loaded', true);
    }
}

$(document).ready(function() {
	MediaIndex.init();
});