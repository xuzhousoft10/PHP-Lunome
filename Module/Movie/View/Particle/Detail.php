<?php use X\Module\Movie\Service\Movie\Core\Instance\Movie; ?>
<?php use X\Service\XView\Core\Handler\Html; ?>
<?php use X\Service\XDatabase\Core\ActiveRecord\Criteria;?>
<?php /* @var $movie \X\Module\Movie\Service\Movie\Core\Instance\Movie */ ?>
<?php /* @var $currentUser \X\Module\Account\Service\Account\Core\Instance\Account */ ?>
<?php /* @var $this \X\Service\XView\Core\Util\HtmlView\ParticleView */ ?>
<?php 
$vars = get_defined_vars();
$movie = $vars['movie'];
$myMark = $vars['myMark'];
$markCount = $vars['markCount'];
$markStyles = $vars['markStyles'];
$markNames = $vars['markNames'];
$assetsURL = $vars['assetsURL'];
$shareMessageTitle = $vars['shareMessageTitle'];
$shareMessageContent = $vars['shareMessage'];
$currentUser = $vars['currentUser'];
$isGuestUser = $vars['isGuestUser'];
$currentUserProfile = $isGuestUser ? null : $currentUser->getProfileManager();

$scriptManager = $this->getManager()->getHost()->getScriptManager();
$scriptManager->add('ajaxfileupload')->setSource('library/jquery/plugin/ajaxfileupload.js');
$scriptManager->add('movie-detail')->setSource('js/movie/detail.js')->setRequirements('ajaxfileupload');
?>
<div class="row margin-top-5">
    <ol class="breadcrumb">
        <li><a href="/?module=movie&action=index">电影</a></li>
        <li class="active"><?php echo Html::HTMLEncode($movie->get('name'));?></li>
    </ol>
    
    <div class="col-md-3 padding-0">
        <img class="img-thumbnail padding-0 lunome-movie-cover-200-300" src="<?php echo $movie->getCoverURL();?>">
    </div>
    <div class="col-md-9">
        <div class="clearfix">
            <h4 class="pull-left margin-top-0">
                <?php echo Html::HTMLEncode($movie->get('name'));?>
                <small>
                     --
                     <span class="label label-<?php echo $markStyles[$myMark];?>">
                        <?php echo Html::HTMLEncode($markNames[$myMark]);?>
                     </span>
                </small>
            </h4>
        </div>
        
        <ul class="list-unstyled">
            <li>
                <strong>类型</strong>&nbsp;&nbsp;&nbsp;
                <?php $movieCategories = $movie->getCategories(); ?> 
                <?php if ( empty($movieCategories) ) : ?>
                    其他
                <?php else: ?>
                    <?php $lastMark = count($movieCategories)-1; ?>
                    <?php foreach ( $movieCategories as $index => $category ) : ?>
                        <a href="/?module=movie&action=index&query[category]=<?php echo $category->get('id');?>">
                            <?php echo Html::HTMLEncode($category->get('name'));?>
                        </a>
                        <?php if($index!==$lastMark):?>/<?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </li>
            
            <li>
                <strong>语言</strong>&nbsp;&nbsp;&nbsp;
                <?php $movieLanguage = $movie->getLanguage(); ?>
                <?php if ( null === $movieLanguage ) : ?>
                    其他
                <?php else: ?>
                    <a href="/?module=movie&action=index&query[language]=<?php echo $movieLanguage->get('id');?>">
                        <?php echo Html::HTMLEncode($movieLanguage->get('name'));?>
                    </a>
                <?php endif; ?>
            </li>
            
            <li>
                <strong>地区</strong>&nbsp;&nbsp;&nbsp;
                <?php $movieRegion=$movie->getRegion();?>
                <?php if ( null === $movieRegion ): ?>
                    其他
                <?php else : ?>
                    <a href="/?module=movie&action=index&query[region]=<?php echo $movieRegion->get('id');?>">
                        <?php echo Html::HTMLEncode($movieRegion->get('name'));?>
                    </a>
                <?php endif; ?>
            </li>
            
            <li>
                <strong>时长</strong>&nbsp;&nbsp;&nbsp;
                <?php echo intval($movie->get('length')/60);?>分钟
            </li>
            
            <li>
                <strong>导演</strong>&nbsp;&nbsp;&nbsp;
                <?php $directors = $movie->getDirectors(); ?>
                <?php $lastMark = count($directors)-1; ?>
                <?php foreach ( $directors as $index => $director ) : ?>
                    <a href="/?module=movie&action=index&query[name]=<?php echo urlencode('导演:'.$director->get('name'));?>">
                        <?php echo Html::HTMLEncode($director->get('name')); ?>
                    </a>
                    <?php if($index!==$lastMark):?>/<?php endif; ?>
                <?php endforeach; ?>
            </li>
            
            <li>
                <strong>主演</strong>&nbsp;&nbsp;&nbsp;
                <?php $actors = $movie->getActors(); ?>
                <?php $lastMark = count($actors)-1; ?>
                <?php foreach ( $actors as $index => $actor ) : ?>
                    <a href="/?module=movie&action=index&query[name]=<?php echo urlencode('演员:'.$actor->get('name'));?>">
                        <?php echo Html::HTMLEncode($actor->get('name')); ?>
                    </a>
                    <?php if($index!==$lastMark):?>/<?php endif; ?>
                <?php endforeach; ?>
            </li>
            
            <li><br></li>
            
            <li>
               <strong>想看</strong>&nbsp;&nbsp;&nbsp;
                <a  href="#"
                    class="detail-marked-account-list"
                    data-id="<?php echo $movie->get('id'); ?>"
                    data-mark="<?php echo Movie::MARK_INTERESTED; ?>"
                    data-scope="friends"
                    data-loadding-img = "<?php echo $assetsURL.'/image/loadding.gif';?>"
                >好友:<?php echo $markCount[Movie::MARK_INTERESTED]['friend'];?></a>
                /
                <a  href="#"
                    class="detail-marked-account-list"
                    data-id="<?php echo $movie->get('id'); ?>"
                    data-mark="<?php echo Movie::MARK_INTERESTED; ?>"
                    data-scope="all"
                    data-loadding-img = "<?php echo $assetsURL.'/image/loadding.gif';?>"
                >全网:<?php echo $markCount[Movie::MARK_INTERESTED]['all'];?></a>
            </li>
            
            <li>
                <strong>已看</strong>&nbsp;&nbsp;&nbsp;
                <a  href="#"
                    class="detail-marked-account-list" 
                    data-id="<?php echo $movie->get('id'); ?>"
                    data-mark="<?php echo Movie::MARK_WATCHED; ?>"
                    data-scope="friends"
                    data-loadding-img = "<?php echo $assetsURL.'/image/loadding.gif';?>"
                >好友:<?php echo $markCount[Movie::MARK_WATCHED]['friend'];?></a>
                /
                <a  href="#"
                    class="detail-marked-account-list" 
                    data-id="<?php echo $movie->get('id'); ?>"
                    data-mark="<?php echo Movie::MARK_WATCHED; ?>"
                    data-scope="all"
                    data-loadding-img = "<?php echo $assetsURL.'/image/loadding.gif';?>"
                >全网:<?php echo $markCount[Movie::MARK_WATCHED]['all'];?></a>
            </li>
            
            <li>
                <strong>忽略</strong>&nbsp;&nbsp;&nbsp;
                <a  href="#"
                    class="detail-marked-account-list" 
                    data-id="<?php echo $movie->get('id'); ?>"
                    data-mark="<?php echo Movie::MARK_IGNORED; ?>"
                    data-scope="friends"
                    data-loadding-img = "<?php echo $assetsURL.'/image/loadding.gif';?>"
                >好友:<?php echo $markCount[Movie::MARK_IGNORED]['friend'];?></a>
                /
                <a  href="#"
                    class="detail-marked-account-list" 
                    data-id="<?php echo $movie->get('id'); ?>"
                    data-mark="<?php echo Movie::MARK_IGNORED; ?>"
                    data-scope="all"
                    data-loadding-img = "<?php echo $assetsURL.'/image/loadding.gif';?>"
                >全网:<?php echo $markCount[Movie::MARK_IGNORED]['all'];?></a>
            </li>
        </ul>
        
        <br>
        <div class="clearfix">
            <div class="btn-group pull-left">
                <?php foreach ( $markNames as $markKey => $markName ) : ?>
                    <?php if ( 0 === $markKey || $myMark === $markKey ) :?>
                        <?php continue; ?>
                    <?php endif; ?>
                    <a  class="btn btn-<?php echo $markStyles[$markKey];?>" 
                        href="/?module=movie&action=mark&mark=<?php echo $markKey; ?>&id=<?php echo $movie->get('id'); ?>&redirect=true"
                    ><?php echo $markName;?></a>
                <?php endforeach; ?>
            </div>
            
            <!--  
            <div class="pull-left padding-left-10">
                <button class                       = "btn btn-default"
                        data-online-play-trigger    = "true"
                        data-player-container       = "#movie-online-play-container"
                        data-movie-name             = "<?php echo $movie->get('name');?>"
                        data-loadding-img           = "<?php echo $assetsURL.'/image/loadding.gif';?>"
                        data-assets-path            = "<?php echo $assetsURL;?>"
                        data-global-search-url      = "/?module=movie&action=globalSearch"
                >在线观看</button>
                <a href="https://www.baidu.com/s?wd=<?php echo urlencode($movie->get('name'));?>" target="_black" class="btn btn-default">百度一下</a>
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
                        summary     : <?php echo json_encode(array('message'=>$movie->get('introduction')));?>.message,
                        title       : '<?php echo $movie->get('name');?>',
                        site        : 'Lunome',
                        pics        : '<?php echo $movie->getCoverURL();?>',
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
            -->
        </div>
    </div>
</div>

<hr>
<div id="movie-online-play-container"></div>

<div class="margin-top-5">
    <p><?php echo Html::HTMLEncode($movie->get('introduction'));?></p>
</div>

<br>
<h4 class="margin-bottom-5 clearfix">
    人物角色
    <span class="pull-right">
        <a href="#">
            <small>查看所有&gt;&gt;</small>
        </a>
    </span>
</h4>
<hr class="margin-top-0">
<div class="clearfix">
    <?php $criteria = new Criteria(); ?>
    <?php $criteria->limit = 9; ?>
    <?php $characters = $movie->getCharacterManager()->find($criteria); ?>
    <?php if (empty($characters)) : ?>
        <span>
            <small>
                暂时没有该影片的角色数据～～～
                <?php if ( Movie::MARK_WATCHED === $myMark) : ?>
                    , 你可以进入
                    <a href="/?module=movie&action=character/index">
                    管理页面
                    </a>
                    添加角色。
                <?php endif; ?>
            </small>
        </span>
    <?php else:?>
    <ul class="list-inline">
        <?php foreach ( $characters as $character ): ?>
            <?php /* @var $character \ \X\Module\Movie\Service\Movie\Core\Instance\Character */ ?>
            <li>
                <?php printf('<a href="/?module=movie&action=character/detail&id=%s">', $movie->get('id'));?>
                <div class="text-center">
                    <img    class="img-rounded lunome-movie-character-photo-80-80" 
                            src="<?php echo $character->getPhotoURL(); ?>"
                            width="80"
                            height="80"
                    >
                    <br>
                    <?php echo Html::HTMLEncode($character->getName());?>
                </div>
                <?php printf('</a>'); ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
</div>
<br>
<br>
<div class="row">
    <div class="col-md-8">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#movie-classic-dialogues-container" data-toggle="tab">经典台词</a></li>
            <li><a href="#movie-posters-container" data-toggle="tab">宣传海报</a></li>
        </ul>
        
        <!-- Tab panes -->
        <div class="tab-content">
            <!-- classic dialogue Tab -->
            <div    class               = "tab-pane active" 
                    id                  = "movie-classic-dialogues-container"
                    data-resource-type  = "dialogue"
                    data-index-url      = "/?module=movie&action=classicDialogue/index&id=<?php echo $movie->get('id'); ?>"
                    data-loadding-image = "<?php echo $assetsURL.'/image/loadding.gif'?>"
                    data-movie-id       = "<?php echo $movie->get('id'); ?>"
            ></div>
            
            <!-- poster Tab -->
            <div    class               = "tab-pane" 
                    id                  = "movie-posters-container"
                    data-resource-type  = "poster"
                    data-index-url      = "/?module=movie&action=poster/index&id=<?php echo $movie->get('id'); ?>"
                    data-loadding-image = "<?php echo $assetsURL.'/image/loadding.gif'?>"
                    data-movie-id       = "<?php echo $movie->get('id'); ?>"
            ></div>
        </div>
    </div>
    <div class="col-md-4">
        <!-- comment list -->
        <div    id                  = "movie-short-comment-container"
                data-is-guest-user  = "<?php echo ($isGuestUser)?'true':'false'; ?>"
                data-user-nickname  = "<?php echo ($isGuestUser)?'': Html::HTMLEncode($currentUserProfile->get('nickname')); ?>"
                data-user-photo     = "<?php echo ($isGuestUser)?'':$currentUserProfile->get('photo'); ?>"
                data-media-id       = "<?php echo $movie->get('id'); ?>"
                data-index-url      = "/?module=movie&action=comment/index&id=<?php echo $movie->get('id'); ?>"
                data-container      = "#movie-short-comment-list-container"
                data-loadding-image = "<?php echo $assetsURL.'/image/loadding.gif'?>"
        >
            <div class="clearfix">
                <div class="col-md-4 padding-0">
                    <img src="<?php echo $isGuestUser?'':$currentUserProfile->get('photo'); ?>" width="90" height="90" class="thumbnail margin-bottom-0">
                    <span><?php echo $isGuestUser?'':Html::HTMLEncode($currentUserProfile->get('nickname')); ?></span>
                </div>
                <div class="col-md-8 padding-0">
                    <form action="/?module=movie&action=comment/add">
                        <input type="hidden" name="id" value="<?php echo $movie->get('id'); ?>" >
                        <textarea name="content" class="width-full" rows="" cols=""></textarea>
                        <br>
                        <button class="btn btn-primary" name="save" type="button">发表</button>
                    </form>
                </div>
            </div>
            <hr>
            <div class="clearfix" id="movie-short-comment-list-container"></div>
        </div>
    </div>
</div>

<!-- Classic dialogues edit modal -->
<div class="modal fade" id="movie-classic-dialogues-edit-dialog" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form   action="/?module=movie&action=classicDialogue/edit" 
                    data-dialog="#movie-classic-dialogues-edit-dialog" 
                    data-resource-type="dialogue"
                    data-loadding-image = "<?php echo $assetsURL.'/image/loadding.gif'?>"
            >
                <input type="hidden" name="id" value="<?php echo $movie->get('id'); ?>">
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
            <form   action="/?module=movie&action=poster/upload" 
                    data-dialog="#movie-posters-add-dialog" 
                    data-resource-type="poster"
                    data-loadding-image = "<?php echo $assetsURL.'/image/loadding.gif'?>"
            >
                <input type="hidden" name="id" value="<?php echo $movie->get('id'); ?>">
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
            <form   action="/?module=movie&action=character/edit" 
                    data-dialog="#movie-characters-edit-dialog" 
                    data-resource-type="character"
                    data-loadding-image = "<?php echo $assetsURL.'/image/loadding.gif'?>"
            >
                <input type="hidden" name="movie" value="<?php echo $movie->get('id'); ?>">
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