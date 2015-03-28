<?php 
$vars = get_defined_vars();
$assetsURL = $vars['assetsURL'];
$comments = $vars['comments']; 
$pager = $vars['pager'];
?>
<div>
    <ul class="nav nav-tabs nav-justified">
        <li class="<?php if('friends'===$vars['scope']):?>active<?php endif;?>">
            <a  href="/?module=movie&action=comment/index&id=<?php echo $vars['id'];?>&scope=friends"
                class="movie-comments-container-pager"
            >好友</a>
        </li>
        <li class="<?php if('all'===$vars['scope']):?>active<?php endif;?>">
            <a  href="/?module=movie&action=comment/index&id=<?php echo $vars['id'];?>&scope=all"
                class="movie-comments-container-pager"
            >全网</a>
        </li>
    </ul>
</div>
<?php if (empty($comments)) : ?>
    <div class="clearfix">
        <div class="pull-left">
            <img src="<?php echo $assetsURL;?>/image/nothing.gif" width="100" height="100">
        </div>
        <div class="margin-top-70 text-muted">
            <small>评论空空的~~~</small>
        </div>
    </div>
<?php else: ?>
    <br>
    <div>
    <?php foreach ( $comments as $comment ) : ?>
    <?php /* @var $comment \X\Module\Movie\Service\Movie\Core\Instance\ShortComment */ ?>
    <?php $commenter = $comment->getCommenter()->getProfileManager(); ?>
        <div class="media">
            <div class="pull-left">
                <img src="<?php echo $commenter->get('photo');?>" width="50" height="50" class="thumbnail margin-bottom-0">
            </div>
            <div class="media-body">
                <strong><?php echo $commenter->get('nickname'); ?></strong>
                <span>&nbsp;&nbsp;&nbsp;</span>
                <small class="text-muted"><?php echo $comment->get('commented_at');?></small>
                <br>
                <span><?php echo $comment->get('content'); ?></span>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
    
    <?php $vars['pager']->setPrevPageButtonClass('movie-comments-container-pager'); ?>
    <?php $vars['pager']->setNextPageButtonClass('movie-comments-container-pager'); ?>
    <?php $vars['pager']->show(); ?>
<?php endif; ?>