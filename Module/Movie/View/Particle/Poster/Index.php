<?php 
use X\Service\XView\Core\Handler\Html;
$vars = get_defined_vars();
$movie = $vars['movie'];
?>
<ol class="breadcrumb">
    <li><a href="/?module=movie&action=index">电影</a></li>
    <li><a href="/?module=movie&action=detail&id=<?php echo $movie->get('id'); ?>"><?php echo Html::HTMLEncode($movie->get('name'));?></a></li>
    <li class="active">图片</li>
</ol>

<?php $posters = $vars['posters']; ?>
<?php if ( empty( $posters ) ) : ?>
    <div class="clearfix">
        <div class="pull-left">
            <?php $assetsURL = $vars['assetsURL']; ?>
            <img src="<?php echo $assetsURL;?>/image/nothing.gif" width="100" height="100">
        </div>
        <div class="margin-top-70 text-muted">
            <small>空空的~~~</small>
        </div>
    </div>
<?php else :?>
    <div class="clearfix">
    <?php foreach ( $posters as $poster ): ?>
        <?php /* @var $poster \X\Module\Movie\Service\Movie\Core\Instance\Poster */ ?>
        <div class="pull-left text-center margin-left-10 margin-bottom-10">
            <a href="/?module=movie&action=poster/detail&movie=<?php echo $movie->get('id'); ?>&poster=<?php echo $poster->get('id'); ?>">
                <img width="200" height="150" src="<?php echo $poster->getURL();?>">
            </a>
            <div>
                <?php $favouriteManager = $poster->getFavouriteManager(); ?>
                <?php if ( $favouriteManager->isMyFavourite() ):  ?>
                    <a href="/?module=movie&action=poster/like&movie=<?php echo $movie->get('id'); ?>&poster=<?php echo $poster->get('id');?>&like=no"
                    ><span class="glyphicon glyphicon-heart" >(<?php echo $poster->getFavouriteManager()->count(); ?>)</span></a>
                <?php else : ?>
                    <a href="/?module=movie&action=poster/like&movie=<?php echo $movie->get('id'); ?>&poster=<?php echo $poster->get('id');?>&like=yes"
                    ><span class="glyphicon glyphicon-heart-empty" >(<?php echo $poster->getFavouriteManager()->count(); ?>)</span></a>
                <?php endif; ?>
                &nbsp;
                
                <a href="/?module=movie&action=poster/vote&movie=<?php echo $movie->get('id'); ?>&poster=<?php echo $poster->get('id');?>&vote=up"
                ><span class="glyphicon glyphicon-thumbs-up" >(<?php echo $poster->getVoteManager()->countVoteUp(); ?>)</span></a>
                &nbsp;
                
                <a href="/?module=movie&action=poster/vote&movie=<?php echo $movie->get('id'); ?>&poster=<?php echo $poster->get('id');?>&vote=down"
                ><span class="glyphicon glyphicon-thumbs-down" >(<?php echo $poster->getVoteManager()->countVoteDown();?>)</span></a>
                &nbsp;
                
                <a href="/?module=movie&action=poster/detail&movie=<?php echo $movie->get('id'); ?>&poster=<?php echo $poster->get('id');?>"
                ><span>评论(<?php echo $poster->getCommentManager()->count();?>)</span></a>
                &nbsp;
            </div>
        </div>
    <?php endforeach; ?>
    </div>
<?php endif; ?>