<?php 
use X\Service\XView\Core\Handler\Html;
$vars = get_defined_vars();
$assetsURL = $vars['assetsURL'];
$comments = $vars['comments'];
$movie = $vars['movie'];
?>
<ol class="breadcrumb">
    <li><a href="/?module=movie&action=index">电影</a></li>
    <li><a href="/?module=movie&action=detail&id=<?php echo $movie->get('id'); ?>"><?php echo Html::HTMLEncode($movie->get('name'));?></a></li>
    <li class="active">一句话点评</li>
</ol>
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
        <div class="media-left">
            <?php #$character = $dialogue->getCharacter(); ?>
            <a href="/?module=movie&action=comment/detail&movie=<?php echo $movie->get('id')?>&comment=<?php echo $commenter->get('id');?>">
                <img class="media-object img-rounded lunome-movie-character-photo-80-80" 
                    src="<?php echo $commenter->get('photo'); ?>" 
                    alt="<?php echo Html::HTMLAttributeEncode($commenter->get('nickname')); ?>"
                >
            </a>
        </div>
        
        <div class="media-body">
            <blockquote class="padding-0 padding-left-10">
                <p class="margin-0">
                    <a href="/?module=movie&action=comment/detail&movie=<?php echo $movie->get('id'); ?>&comment=<?php echo $comment->get('id');?>">
                        <?php echo Html::HTMLEncode($comment->get('content')); ?>
                    </a>
                </p>
                <small>
                    <?php echo Html::HTMLAttributeEncode($commenter->get('nickname')); ?>
                </small>
            </blockquote>
        </div>
    </div>
    
    <div class="text-right">
        <?php $favouriteManager = $comment->getFavouriteManager(); ?>
        <?php if ( $favouriteManager->isMyFavourite() ):  ?>
            <a href="/?module=movie&action=comment/like&movie=<?php echo $movie->get('id'); ?>&comment=<?php echo $comment->get('id');?>&like=no"
            ><span class="glyphicon glyphicon-heart" >(<?php echo $comment->getFavouriteManager()->count(); ?>)</span></a>
        <?php else : ?>
            <a href="/?module=movie&action=comment/like&movie=<?php echo $movie->get('id'); ?>&comment=<?php echo $comment->get('id');?>&like=yes"
            ><span class="glyphicon glyphicon-heart-empty" >(<?php echo $comment->getFavouriteManager()->count(); ?>)</span></a>
        <?php endif; ?>
        &nbsp;
        
        <a href="/?module=movie&action=comment/vote&movie=<?php echo $movie->get('id'); ?>&comment=<?php echo $comment->get('id');?>&vote=up"
        ><span class="glyphicon glyphicon-thumbs-up" >(<?php echo $comment->getVoteManager()->countVoteUp(); ?>)</span></a>
        &nbsp;
        
        <a href="/?module=movie&action=comment/vote&movie=<?php echo $movie->get('id'); ?>&comment=<?php echo $comment->get('id');?>&vote=down"
        ><span class="glyphicon glyphicon-thumbs-down" >(<?php echo $comment->getVoteManager()->countVoteDown();?>)</span></a>
        &nbsp;
        
        <a href="/?module=movie&action=comment/detail&movie=<?php echo $movie->get('id'); ?>&comment=<?php echo $comment->get('id');?>"
        ><span>评论(<?php echo $comment->getCommentManager()->count();?>)</span></a>
        &nbsp;
    </div>
    <?php endforeach; ?>
    </div>
<?php endif; ?>