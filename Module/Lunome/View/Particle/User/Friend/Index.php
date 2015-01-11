<?php 
/* @var $this \X\Service\XView\Core\Handler\Html */
$vars = get_defined_vars();
$friends = $vars['friends'];
?>
<?php $sexMap = array(''=>'＊', '0'=>'＊','1'=>'♂','2'=>'♀','3'=>'？'); ?>
<?php $sexNameMap = array(''=>'保密', '0'=>'保密','1'=>'男','2'=>'女','3'=>'其他'); ?>
<?php $sexualitysMap = array(''=>'保密', '0'=>'保密','1'=>'异性','2'=>'同性','3'=>'双性','4'=>'无性','5'=>'二禁'); ?>
<?php $emotionStatusMap = array(''=>'保密', '0'=>'保密','1'=>'单身','2'=>'热恋中','3'=>'同居','4'=>'已订婚','5'=>'已婚','6'=>'分居','7'=>'离异','8'=>'很难说','9'=>'其他'); ?>
<div class="col-md-9">
<strong>我的好友</strong>
<hr class="margin-top-10">
<div class="clearfix">
<?php foreach ( $friends as $index =>  $friend ) :?>
    <div class="well-sm padding-0 clearfix col-md-5 padding-left-10">
        <div class="well well-sm">
            <div class="clearfix">
                <div class="pull-left">
                    <img    class="img-thumbnail"
                            alt="<?php echo $friend->nickname;?>" 
                            src="<?php echo $friend->photo;?>"
                            width="80"
                            height="80"
                    >
                </div>
                <div class="pull-left padding-left-5 user-information-text-area">
                    <p>
                        <strong>
                            <span class="text-info" title="性别:<?php echo $sexNameMap[$friend->sex]; ?>"><?php echo $sexMap[$friend->sex];?></span>
                            <?php echo $friend->nickname;?>
                        </strong>
                    </p>
                    <p>
                        <span class="glyphicon glyphicon-heart"></span>
                        <?php echo $sexualitysMap[$friend->sexuality];?>
                        <span class="glyphicon glyphicon-user"></span>
                        <?php echo $emotionStatusMap[$friend->emotion_status];?>
                    </p>
                    <p>
                        <?php echo $friend->living_country;?>
                        <?php echo $friend->living_province;?>
                        <?php echo $friend->living_city;?>
                    </p>
                </div>
            </div>
            
            <div class="text-right">
                <a class="btn btn-primary btn-xs" target="_blank" href="/?module=lunome&action=user/interaction/index&id=<?php echo $friend->account_id;?>">互动</a>
                <a class="btn btn-primary btn-xs" target="_blank" href="/?module=lunome&action=user/chat/index&friend=<?php echo $friend->account_id;?>">聊天</a>
                <a class="btn btn-primary btn-xs" target="_blank" href="/?module=lunome&action=user/home/index&id=<?php echo $friend->account_id;?>">主页</a>
            </div>
        </div>
    </div>
    <?php if (0 === ($index%2)) : ?>
        <div class="col-md-1"></div>
    <?php endif; ?>
<?php endforeach; ?>
</div>
<?php $pager = $vars['pager']; ?>
<?php if ( false !== $pager['prev'] || false !== $pager['next'] ) : ?>
<div>
    <nav>
        <ul class="pager">
            <li class="previous<?php echo (false === $pager['prev']) ? ' disabled' : ''; ?>">
                <?php if (false !== $pager['prev']) :?>
                    <a  href="/?module=lunome&action=user/friend/index&page=<?php echo $pager['prev'];?>"
                        class="movie-characters-container-pager"
                    >&larr; 上一页</a>
                <?php endif; ?>
            </li>
            <li><?php echo $pager['current'];?> / <?php echo $pager['pageCount'];?></li>
            <li class="next<?php echo (false === $pager['next']) ? ' disabled' : ''; ?>">
                <?php if (false !== $pager['next']) :?>
                    <a  href="/?module=lunome&action=user/friend/index&page=<?php echo $pager['next'];?>"
                        class="movie-characters-container-pager"
                    >下一页&rarr;</a>
                <?php endif; ?>
            </li>
        </ul>
    </nav>
</div>
<?php endif; ?>
</div>