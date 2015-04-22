<?php 
use X\Service\XView\Core\Handler\Html;
$vars = get_defined_vars();
$assetsURL = $vars['assetsURL'];
$criticisms = $vars['criticisms'];
$movie = $vars['movie'];
?>
<ol class="breadcrumb">
    <li><a href="/?module=movie&action=index">电影</a></li>
    <li><a href="/?module=movie&action=detail&id=<?php echo $movie->get('id'); ?>"><?php echo Html::HTMLEncode($movie->get('name'));?></a></li>
    <li class="active">影评</li>
</ol>
<?php if (empty($criticisms)) : ?>
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
    <?php foreach ( $criticisms as $criticism ) : ?>
    <?php /* @var $criticism \X\Module\Movie\Service\Movie\Core\Instance\Criticism */ ?>
    <?php $commenter = $criticism->getCommenter()->getProfileManager(); ?>
    <div class="media">
        <div class="media-left">
            <a href="/?module=movie&action=criticism/detail&movie=<?php echo $movie->get('id')?>&criticism=<?php echo $criticism->get('id');?>">
                <img class="media-object img-rounded lunome-account-photo-40-40" 
                    src="<?php echo $commenter->get('photo'); ?>" 
                    alt="<?php echo Html::HTMLAttributeEncode($commenter->get('nickname')); ?>"
                >
            </a>
        </div>
        
        <div class="media-body">
            <blockquote class="padding-0 padding-left-10 margin-bottom-0">
                <p class="margin-0">
                    <a href="/?module=movie&action=criticism/detail&movie=<?php echo $movie->get('id'); ?>&criticism=<?php echo $criticism->get('id');?>">
                        <?php echo Html::HTMLEncode($criticism->get('title')); ?>
                    </a>
                </p>
                <small>
                    <?php echo Html::HTMLAttributeEncode($commenter->get('nickname')); ?>
                    <span class="pull-right">
                        
                    </span>
                </small>
            </blockquote>
        </div>
    </div>
    
    <div class="text-right">
        <?php $favouriteManager = $criticism->getFavouriteManager(); ?>
        <?php if ( $favouriteManager->isMyFavourite() ):  ?>
            <a href="/?module=movie&action=criticism/like&movie=<?php echo $movie->get('id'); ?>&criticism=<?php echo $criticism->get('id');?>&like=no"
            ><span class="glyphicon glyphicon-heart" >(<?php echo $criticism->getFavouriteManager()->count(); ?>)</span></a>
        <?php else : ?>
            <a href="/?module=movie&action=criticism/like&movie=<?php echo $movie->get('id'); ?>&criticism=<?php echo $criticism->get('id');?>&like=yes"
            ><span class="glyphicon glyphicon-heart-empty" >(<?php echo $criticism->getFavouriteManager()->count(); ?>)</span></a>
        <?php endif; ?>
        &nbsp;
        
        <a href="/?module=movie&action=criticism/vote&movie=<?php echo $movie->get('id'); ?>&criticism=<?php echo $criticism->get('id');?>&vote=up"
        ><span class="glyphicon glyphicon-thumbs-up" >(<?php echo $criticism->getVoteManager()->countVoteUp(); ?>)</span></a>
        &nbsp;
        
        <a href="/?module=movie&action=criticism/vote&movie=<?php echo $movie->get('id'); ?>&criticism=<?php echo $criticism->get('id');?>&vote=down"
        ><span class="glyphicon glyphicon-thumbs-down" >(<?php echo $criticism->getVoteManager()->countVoteDown();?>)</span></a>
        &nbsp;
        
        <a href="/?module=movie&action=criticism/detail&movie=<?php echo $movie->get('id'); ?>&criticism=<?php echo $criticism->get('id');?>"
        ><span>评论(<?php echo $criticism->getCommentManager()->count();?>)</span></a>
        &nbsp;
    </div>
    <?php endforeach; ?>
    </div>
<?php endif; ?>