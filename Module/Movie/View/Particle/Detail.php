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
<div class="clearfix margin-top-5">
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
        <a href="/?module=movie&action=character/index&movie=<?php echo $movie->get('id');?>">
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
                <?php printf('<a href="/?module=movie&action=character/detail&character=%s&movie=%s">', $character->get('id'), $movie->get('id'));?>
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

<h4 class="margin-bottom-5 clearfix">
    相关图片
    <span class="pull-right">
        <a href="/?module=movie&action=poster/index&movie=<?php echo $movie->get('id');?>">
            <small>查看所有&gt;&gt;</small>
        </a>
    </span>
</h4>
<hr class="margin-top-0">
<div class="clearfix">
    <?php $criteria = new Criteria(); ?>
    <?php $criteria->limit = 9; ?>
    <?php $posters = $movie->getPosterManager()->find($criteria); ?>
    <?php if (empty($posters)) : ?>
        <span>
            <small>
                暂时没有该影片的图片数据数据～～～
                <?php if ( Movie::MARK_WATCHED === $myMark) : ?>
                    , 你可以进入
                    <a href="/?module=movie&action=poster/index">
                    管理页面
                    </a>
                    添加图片。
                <?php endif; ?>
            </small>
        </span>
    <?php else:?>
    <ul class="list-inline">
        <?php foreach ( $posters as $poster ): ?>
            <?php /* @var $poster \X\Module\Movie\Service\Movie\Core\Instance\Poster */ ?>
            <li>
                <?php printf('<a href="/?module=movie&action=poster/detail&id=%s&poster=%d">', $movie->get('id'), $poster->get('id'));?>
                <div class="text-center">
                    <img    class="img-rounded lunome-movie-character-photo-80-80" 
                            src="<?php echo $poster->getURL(); ?>"
                            width="80"
                            height="80"
                    >
                </div>
                <?php printf('</a>'); ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
</div>

<br>
<h4 class="margin-bottom-5 clearfix">
    经典台词
    <span class="pull-right">
        <a href="/?module=movie&action=dialogue/index&movie=<?php echo $movie->get('id');?>">
            <small>查看所有&gt;&gt;</small>
        </a>
    </span>
</h4>
<hr class="margin-top-0">
<div class="clearfix">
    <?php $criteria = new Criteria(); ?>
    <?php $criteria->limit = 9; ?>
    <?php $dialogues = $movie->getClassicDialogueManager()->find($criteria); ?>
    <?php if ( empty($dialogues) ) : ?>
        <span>
            <small>
                暂时没有该影片的台词数据数据～～～
                <?php if ( Movie::MARK_WATCHED === $myMark) : ?>
                    , 你可以进入
                    <a href="/?module=movie&action=dialogue/index&movie=<?php echo $movie->get('id');?>">
                    管理页面
                    </a>
                    添加图片。
                <?php endif; ?>
            </small>
        </span>
    <?php else:?>
        <?php foreach ( $dialogues as $dialogue ): ?>
            <?php /* @var $dialogue \X\Module\Movie\Service\Movie\Core\Instance\ClassicDialogue */ ?>
            <p>
                <a class="text-muted" href="/?module=movie&action=dialogue/detail&movie=<?php echo $movie->get('id'); ?>&dialogue=<?php echo $dialogue->get('id'); ?>">
                    <?php echo $dialogue->get('content');?>
                </a>
            </p>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<br>

<h4 class="margin-bottom-5 clearfix">
    一句话点评
    <span class="pull-right">
        <a href="/?module=movie&action=comment/index&movie=<?php echo $movie->get('id');?>">
            <small>查看所有&gt;&gt;</small>
        </a>
    </span>
</h4>
<hr class="margin-top-0">
<div class="clearfix">
    <?php $criteria = new Criteria(); ?>
    <?php $criteria->limit = 9; ?>
    <?php $shortComments = $movie->getShortCommentManager()->find($criteria); ?>
    <?php if (empty($shortComments)) : ?>
        <span>
            <small>
                暂时没有该影片的图片数据数据～～～
                <?php if ( Movie::MARK_WATCHED === $myMark) : ?>
                    , 你可以进入
                    <a href="/?module=movie&action=poster/index">
                    管理页面
                    </a>
                    添加图片。
                <?php endif; ?>
            </small>
        </span>
    <?php else:?>
    <ul class="list-inline">
        <?php foreach ( $shortComments as $shortComment ): ?>
            <?php /* @var $poster \X\Module\Movie\Service\Movie\Core\Instance\ShortComment */ ?>
            <li>
                <p>
                    <?php echo $shortComment->getCommenter()->getProfileManager()->get('nickname'); ?> 
                    <small class="text-muted">(<?php echo $shortComment->get('commented_at');?>)</small> : 
                    <a class="text-muted" href="/?module=movie&action=comment/detail&movie=<?php echo $movie->get('id'); ?>&comment=<?php echo $shortComment->get('id'); ?>">
                        <?php echo $shortComment->get('content');?>
                    </a>
                </p>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
</div>
<br>

<h4 class="margin-bottom-5 clearfix">
    相关新闻
    <span class="pull-right">
        <a href="/?module=movie&action=news/index&movie=<?php echo $movie->get('id');?>">
            <small>查看所有&gt;&gt;</small>
        </a>
    </span>
</h4>
<hr class="margin-top-0">
<div class="clearfix">
    <?php $criteria = new Criteria(); ?>
    <?php $criteria->limit = 10; ?>
    <?php $news = $movie->getNewsManager()->find($criteria); ?>
    <?php if (empty($news)) : ?>
        <span>
            <small>
                暂时没有该影片的新闻数据数据～～～
                <?php if ( Movie::MARK_WATCHED === $myMark) : ?>
                    , 你可以进入
                    <a href="/?module=movie&action=poster/index">
                    管理页面
                    </a>
                    添加新闻。
                <?php endif; ?>
            </small>
        </span>
    <?php else:?>
    <ul class="list-unstyled">
        <?php foreach ( $news as $newsItem ): ?>
            <?php /* @var $newsItem \X\Module\Movie\Service\Movie\Core\Instance\News */ ?>
            <li>
                <p>
                    <a class="text-muted" href="<?php echo $newsItem->get('link');?>" target="_blank">
                        <?php echo Html::HTMLEncode($newsItem->get('title')); ?>
                    </a>
                    <small>
                        <img src="<?php echo $newsItem->get('logo');?>">
                        <?php echo Html::HTMLEncode($newsItem->get('source'));?>
                        <?php echo $newsItem->get('time');?>
                    </small>
                </p>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
</div>

<br>
<h4 class="margin-bottom-5 clearfix">
    影评
    <span class="pull-right">
        <a href="/?module=movie&action=criticism/index&movie=<?php echo $movie->get('id');?>">
            <small>查看所有&gt;&gt;</small>
        </a>
    </span>
</h4>
<hr class="margin-top-0">
<div class="clearfix">
    <?php $criteria = new Criteria(); ?>
    <?php $criteria->limit = 10; ?>
    <?php $criticisms = $movie->getCriticismManager()->find($criteria); ?>
    <?php if (empty($criticisms)) : ?>
        <span>
            <small>
                暂时没有该影片的影评数据～～～
                <?php if ( Movie::MARK_WATCHED === $myMark) : ?>
                    , 你可以进入
                    <a href="/?module=movie&action=poster/index">
                    管理页面
                    </a>
                    添加影评。
                <?php endif; ?>
            </small>
        </span>
    <?php else:?>
    <ul class="list-unstyled">
        <?php foreach ( $criticisms as $criticism ): ?>
            <?php /* @var $criticism \X\Module\Movie\Service\Movie\Core\Instance\Criticism */ ?>
            <li>
                <p>
                    <a class="text-muted" href="/?module=movie&action=criticism/detail&movie=<?php echo $movie->get('id'); ?>&criticism=<?php echo $criticism->get('id'); ?>">
                        <?php echo Html::HTMLEncode($criticism->get('title')); ?>
                    </a>
                    <small>
                        <?php $profile = $criticism->getCommenter()->getProfileManager(); ?>
                        <img src="<?php echo $profile->get('photo');?>" width="16" height="16">
                        <?php echo Html::HTMLEncode($profile->get('nickname'));?>
                        <?php echo $criticism->getTime();?>
                    </small>
                </p>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
</div>