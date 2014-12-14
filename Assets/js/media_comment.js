(function($) {$.fn.shortCommentContailer = function() {
    var container       = $(this);
    var isGuestUser     = container.attr('data-is-guest-user');
    var userNickName    = container.attr('data-user-nickname');
    var userPhoto       = container.attr('data-user-photo');
    var mediaId         = container.attr('data-media-id');
    var editorContainer = null;
    var commentCount    = 0;
    var listContainer   = null;
    var currentPage     = 1;
    var containerID     = 'short-comment-container-'+mediaId;
    
    /**
     * 
     */
    function log( message ) {
        $.log(message, 'MediaCommentManager');
    }
    
    /* 生成用于编辑的评论编辑器。 */
    function generateCommentEditor() {
        editorContainer = $('<div>').addClass('media').appendTo(container);
        container.append('<hr>');
        
        $('<div>').addClass('pull-left').appendTo(editorContainer).append(
            $('<img>').attr('src', userPhoto).attr('width', 100).attr('height', 100)
        ).append('<br>').append('<span>'+userNickName+'</span>');
        
        $('<div>').appendTo(editorContainer).addClass('media-body').append(
            $('<textarea>').addClass('width-full')
        ).append('<br>').append(
            $('<button>').addClass('btn').addClass('btn-primary').html('发表').click(function() {
                var $this = this;
                var content = $.trim($(this).prev().prev().val());
                if ( 0 === content.length ) {
                    return;
                }
                
                $.post('/?module=lunome&action=movie/comment', {
                    id:mediaId,
                    content:content,
                }, function( response ) {
                    var time = new Date();
                    time =  time.getFullYear()+'-'+time.getMonth()+'-'+time.getDate()+' '+
                            time.getHours()+':'+time.getMinutes()+':'+time.getSeconds();
                    addCommentToContainer({userPhoto:userPhoto,userNickName:userNickName,time:time, content:content}, true);
                    $($this).prev().prev().val('');
                    log('Done Add Comment');
                }, 'text');
            })
        );
    }
    
    /* 增加评论内容到当前列表。 */
    function addCommentToContainer( comment, isFirst ) {
        var itemContainer = $('<div>').addClass('media');
        if ( isFirst ) {
            itemContainer.prependTo(listContainer);
        } else {
            itemContainer.appendTo(listContainer);
        }
        
        $('<div>').addClass('pull-left').appendTo(itemContainer).append(
            $('<img>').attr('src', comment.userPhoto).attr('width', 50).attr('height', 50)
        );
        
        $('<div>').appendTo(itemContainer).addClass('media-body').append(
            $('<strong>').text(comment.userNickName)
        ).append(
            $('<span>').html('&nbsp;&nbsp;&nbsp;')
        ).append(
            $('<small>').addClass('text-muted').text(comment.time)
        ).append('<br>').append(
            $('<span>').text(comment.content)
        );
    }
    
    /* 加载指定页评论内容。 */
    function loadComments(page, goAnchor) {
        log('Start Loadding Comment List, Page['+currentPage+']');
        $.get('/?module=lunome&action=movie/comments', {id:mediaId, page:page}, function(response) {
            if ( null != listContainer ) {
                listContainer.empty();
            }
            commentCount = response.count;
            for( var i in response.list ) {
                var comment = {
                    userPhoto    : response.list[i].user.photo,
                    userNickName : response.list[i].user.nickname,
                    time         : response.list[i].comment.commented_at, 
                    content      : response.list[i].comment.content
                };
                addCommentToContainer(comment, false);
            }
            goAnchor ? location.hash='#'+containerID : null;
            log('Done Loading Comment List. Length['+response.list.length+']');
        }, 'json');
    }
    
    /**
     * 
     */
    function generatePager() {
        var prev = $('<a>').attr('href', '#').text('上一页').click(function() {
            if ( 1 >= currentPage ) {
                return false;
            }
            loadComments(--currentPage, true);
            return false;
        });
        
        var next = $('<a>').attr('href', '#').text('下一页').click(function() {
            if ( 5*currentPage >= commentCount ) {
                return false;
            }
            loadComments(++currentPage, true);
            return false;
        });
        
        var pager = $('<nav>').addClass('pager').append(
            $('<ul>').append(
                $('<li>').append(prev)
            ).append(
                $('<li>').append(next)
            )
        )
        
        container.append(pager);
    }
    
    /* 初始化 */
    (function init() {
        container.append('<h4 id="'+containerID+'">评论：</h4>');
        if ( 'false' == isGuestUser ) {
            generateCommentEditor();
            loadComments(1, false);
        }
        listContainer = $('<div>').addClass('clearfix').appendTo(container);
        generatePager();
        log('CommentManager Initialization Done');
    })();
};})(jQuery);

/* 初始化评论列表 */
$(document).ready(function() {
    $('#movie-short-comment-container').shortCommentContailer();
});