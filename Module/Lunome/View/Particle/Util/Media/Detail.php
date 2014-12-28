<?php 
use X\Core\X;
use X\Module\Lunome\Service\Movie\Service as MovieService;

$assetsURL = X::system()->getConfiguration()->get('assets-base-url');
$this->addScriptFile('ajaxfileupload', $assetsURL.'/library/jquery/plugin/ajaxfileupload.js');
$this->addScriptFile('detail-comments', $assetsURL.'/js/media_comment.js');
$this->addScriptFile('detail-common', $assetsURL.'/js/media_detail.js');

$vars = get_defined_vars();
$media = $vars['media'];
$mediaType = $vars['mediaType'];
$mediaName = $vars['mediaName'];
$markCount = $vars['markCount'];
$myMark = $vars['myMark'];
$markStyles = $vars['markStyles'];
$markNames = $vars['markNames'];
$markUrlFormat = sprintf('/?module=lunome&action=%s/mark&mark=%%s&id=%%s&redirect=true', strtolower($mediaType));
$shareMessageContent = str_replace('{name}', $media['name'], $vars['shareMessage']);
if ( MovieService::MARK_INTERESTED == $myMark ) {
    $shareMessageTitle = '求包养';
} else if ( MovieService::MARK_WATCHED == $myMark ) {
    $shareMessageTitle = '推荐给好友';
} else {
    $shareMessageTitle = '分享';
}
?>
<div class="row margin-top-5">
    <ol class="breadcrumb">
        <li><a href="/?module=lunome&action=<?php echo strtolower($mediaType)?>/index"><?php echo $mediaName;?></a></li>
        <li class="active"><?php echo $media['name'];?></li>
    </ol>
    
    <div class="col-md-2 padding-0">
        <a class="thumbnail" href="http://v.baidu.com/v?word=<?php echo urlencode(mb_convert_encoding($media['name'],'gb2312','utf-8' )); ?>" target="_black">
            <img src="<?php echo $media['cover'];?>" width="200" height="300">
        </a>
    </div>
    <div class="col-md-10">
        <div class="clearfix">
            <h4 class="pull-left">
                <?php echo $media['name'];?>
                <small>
                     --
                     <span class="label label-<?php echo $markStyles[$myMark];?>">
                        <?php echo $markNames[$myMark];?>
                     </span>
                </small>
            </h4>
        </div>
        
        <br>
        <table class="table table-bordered">
            <?php require dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR.$mediaType.DIRECTORY_SEPARATOR.'Detail.php';?>
        </table>
        <div class="clearfix">
            <div class="btn-group pull-left">
                <?php foreach ( $markNames as $markKey => $markName ) : ?>
                    <?php if ( 0 === $markKey || $myMark === $markKey ) :?>
                        <?php continue; ?>
                    <?php endif; ?>
                    <a  class="btn btn-<?php echo $markStyles[$markKey];?>" 
                        href="<?php printf($markUrlFormat, $markKey, $media['id']); ?>"
                    ><?php echo $markName;?></a>
                <?php endforeach; ?>
            </div>
            
            <div class="pull-left padding-left-10">
                <button class                       = "btn btn-default"
                        data-online-play-trigger    = "true"
                        data-player-container       = "#movie-online-play-container"
                        data-movie-name             = "<?php echo $media['name'];?>"
                        data-loadding-img           = "<?php echo $assetsURL.'/image/loadding.gif';?>"
                        data-assets-path            = "<?php echo $assetsURL;?>"
                        data-global-search-url      = "http://lunome.kupoy.com/?module=lunome&action=movie/globalSearch"
                >在线观看</button>
            </div>
            
            <div class="pull-right text-right">
                <span class="pull-left" style="line-height: 30px;"><?php echo $shareMessageTitle; ?>&nbsp;<span class="glyphicon glyphicon-hand-right"></span>&nbsp;</span>
                <div class = "pull-left lnm-qzone-share-container" >
                    <script type="text/javascript">
                    (function(){
                    var p = {
                        url         : location.href,
                        showcount   : '0',
                        desc        : '<?php echo $shareMessageContent;?>',
                        summary     : <?php echo json_encode(array('message'=>$media['introduction']));?>.message,
                        title       : '<?php echo $media['name'];?>',
                        site        : 'Lunome',
                        pics        : '<?php echo $media['cover'];?>',
                        style       : '202',
                        width       : 24,
                        height      : 24
                    };
                    
                    var s = [];
                    for(var i in p){
                    s.push(i + '=' + encodeURIComponent(p[i]||''));
                    }
                    document.write(['<a version="1.0" class="qzOpenerDiv" href="http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?',s.join('&'),'" target="_blank">分享</a>'].join(''));
                    })();
                    </script>
                    <script src="http://qzonestyle.gtimg.cn/qzone/app/qzlike/qzopensl.js#jsdate=20111201" charset="utf-8"></script>
                </div>
            </div>
        </div>
    </div>
</div>

<hr>
<div id="movie-online-play-container"></div>

<div class="margin-top-5">
    <p><?php echo $media['introduction'];?></p>
</div>
<hr>

<div class="row">
    <div class="col-md-8">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#movie-classic-dialogues-container" data-toggle="tab">经典台词</a></li>
            <li><a href="#movie-posters-container" data-toggle="tab">宣传海报</a></li>
            <li><a href="#movie-characters-container" data-toggle="tab">人物角色</a></li>
        </ul>
        
        <!-- Tab panes -->
        <div class="tab-content">
            <!-- classic dialogue Tab -->
            <div    class               = "tab-pane active" 
                    id                  = "movie-classic-dialogues-container"
                    data-resource-type  = "dialogue"
                    data-index-url      = "/?module=lunome&action=movie/classicDialogue/index&id=<?php echo $media['id']; ?>"
                    data-loadding-image = "<?php echo $assetsURL.'/image/loadding.gif'?>"
                    data-movie-id       = "<?php echo $media['id']; ?>"
            ></div>
            
            <!-- poster Tab -->
            <div    class               = "tab-pane" 
                    id                  = "movie-posters-container"
                    data-resource-type  = "poster"
                    data-index-url      = "/?module=lunome&action=movie/poster/index&id=<?php echo $media['id']; ?>"
                    data-loadding-image = "<?php echo $assetsURL.'/image/loadding.gif'?>"
                    data-movie-id       = "<?php echo $media['id']; ?>"
            ></div>
            
            <!-- character Tab -->
            <div    class               = "tab-pane"
                    id                  = "movie-characters-container"
                    data-resource-type  = "character"
                    data-index-url      = "/?module=lunome&action=movie/character/index&id=<?php echo $media['id']; ?>"
                    data-loadding-image = "<?php echo $assetsURL.'/image/loadding.gif'?>"
                    data-movie-id       = "<?php echo $media['id']; ?>"
            ></div>
        </div>
    </div>
    <div class="col-md-4">
        <!-- comment list -->
        <div    id                  = "movie-short-comment-container"
                data-is-guest-user  = "<?php echo (null===$vars['currentUser'])?'true':'false'; ?>"
                data-user-nickname  = "<?php echo (null===$vars['currentUser'])?'':$vars['currentUser']->nickname; ?>"
                data-user-photo     = "<?php echo (null===$vars['currentUser'])?'':$vars['currentUser']->photo; ?>"
                data-media-id       ="<?php echo $media['id']; ?>"
        ></div>
    </div>
</div>

<!-- Classic dialogues edit modal -->
<div class="modal fade" id="movie-classic-dialogues-edit-dialog" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form   action="/?module=lunome&action=movie/classicDialogue/edit" 
                    data-dialog="#movie-classic-dialogues-edit-dialog" 
                    data-resource-type="dialogue"
                    data-loadding-image = "<?php echo $assetsURL.'/image/loadding.gif'?>"
            >
                <input type="hidden" name="id" value="<?php echo $media['id']; ?>">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">添加经典台词</h4>
                </div>
                <div class="modal-body">
                    <textarea class="width-full" name="content"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary dialog-save-button">保存</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- poster edit model -->
<div class="modal fade" id="movie-posters-add-dialog" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form   action="/?module=lunome&action=movie/poster/upload&" 
                    data-dialog="#movie-posters-add-dialog" 
                    data-resource-type="poster"
                    data-loadding-image = "<?php echo $assetsURL.'/image/loadding.gif'?>"
            >
                <input type="hidden" name="id" value="<?php echo $media['id']; ?>">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">添加海报</h4>
                </div>
                <div class="modal-body">
                    <input type="file" name="poster" id="movie-poster-file">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary dialog-save-button">保存</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- character edit dialog -->
<div class="modal fade" id="movie-characters-edit-dialog" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form   action="/?module=lunome&action=movie/character/edit" 
                    data-dialog="#movie-characters-edit-dialog" 
                    data-resource-type="character"
                    data-loadding-image = "<?php echo $assetsURL.'/image/loadding.gif'?>"
            >
                <input type="hidden" name="movie" value="<?php echo $media['id']; ?>">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">增加人物角色</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>角色名称</label>
                        <input name="character[name]" type="text" class="form-control" >
                    </div>
                    
                    <div class="form-group">
                        <label>角色描述</label>
                        <textarea name="character[description]" class="form-control" rows="" cols=""></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>角色头像</label>
                        <input id="movie-character-image" type="file" name="image">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary dialog-save-button">保存</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- poster view dialog -->
<div class="modal fade" id="movie-posters-view-dialog" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">查看海报</h4>
            </div>
            <div class="modal-body">
                <img src="#" class="img-thumbnail" id="movie-poster-view-container" >
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            </div>
        </div>
    </div>
</div>