<?php 
use X\Service\XView\Core\Handler\Html;
$vars = get_defined_vars();
$movie = $vars['movie'];
?>
<ol class="breadcrumb">
    <li><a href="/?module=movie&action=index">电影</a></li>
    <li><a href="/?module=movie&action=detail&id=<?php echo $movie->get('id'); ?>"><?php echo Html::HTMLEncode($movie->get('name'));?></a></li>
    <li class="active">经典台词</li>
</ol>

<?php $dialogues = $vars['dialogues']; ?>
<?php if ( empty( $dialogues ) ) : ?>
    <div class="clearfix">
        <div class="pull-left">
            <?php $assetsURL = $vars['assetsURL']; ?>
            <img src="<?php echo $assetsURL;?>/image/nothing.gif" width="100" height="100">
        </div>
        <div class="margin-top-70 text-muted">
            <small>经典台词空空的~~~</small>
        </div>
    </div>
<?php else :?>
    <?php foreach ( $dialogues as $dialogue ) :?>
    <?php /* @var $dialogue X\Module\Movie\Service\Movie\Core\Instance\ClassicDialogue */ ?>
    <div class="media">
        <div class="media-left">
            <?php $character = $dialogue->getCharacter(); ?>
            <a href="/?module=movie&action=dialogue/detail&movie=<?php echo $movie->get('id')?>&dialogue=<?php echo $dialogue->get('id');?>">
            <?php if (null === $character) : ?>
                <img class="media-object img-rounded lunome-movie-character-photo-80-80" 
                    src="<?php echo $assetsURL;?>/image/movie_default_character_photo.jpg" 
                    alt="匿名"
                >
            <?php else : ?>
                <img class="media-object img-rounded lunome-movie-character-photo-80-80" 
                    src="<?php echo $character->getPhotoURL(); ?>" 
                    alt="<?php echo Html::HTMLAttributeEncode($character->get('name')); ?>"
                >
            <?php endif; ?>
            </a>
        </div>
        
        <div class="media-body">
            <blockquote class="padding-0 padding-left-10">
                <p class="margin-0">
                    <?php echo Html::HTMLEncode($dialogue->get('content')); ?>
                </p>
                <br>
                <small>
                    <?php if (null === $character) : ?>
                        匿名
                    <?php else : ?>
                        <a href="/?module=movie&action=character/detail&movie=<?php echo $movie->get('id');?>&character=<?php echo $character->get('id');?>">
                            <?php echo Html::HTMLAttributeEncode($character->get('name')); ?>
                        </a>
                    <?php endif; ?>
                </small>
            </blockquote>
        </div>
    </div>
    
    <div class="text-right">
        <?php $favouriteManager = $dialogue->getFavouriteManager(); ?>
        <?php if ( $favouriteManager->isMyFavourite() ):  ?>
            <a href="/?module=movie&action=dialogue/like&movie=<?php echo $movie->get('id')?>&dialogue=<?php echo $dialogue->get('id');?>&like=no"
            ><span class="glyphicon glyphicon-heart" >(<?php echo $dialogue->getFavouriteManager()->count(); ?>)</span></a>
        <?php else : ?>
            <a href="/?module=movie&action=dialogue/like&movie=<?php echo $movie->get('id')?>&dialogue=<?php echo $dialogue->get('id');?>&like=yes"
            ><span class="glyphicon glyphicon-heart-empty" >(<?php echo $dialogue->getFavouriteManager()->count(); ?>)</span></a>
        <?php endif; ?>
        &nbsp;
        
        <a href="/?module=movie&action=dialogue/vote&movie=<?php echo $movie->get('id')?>&dialogue=<?php echo $dialogue->get('id');?>&vote=up"
        ><span class="glyphicon glyphicon-thumbs-up" >(<?php echo $dialogue->getVoteManager()->countVoteUp(); ?>)</span></a>
        &nbsp;
        
        <a href="/?module=movie&action=dialogue/vote&movie=<?php echo $movie->get('id')?>&dialogue=<?php echo $dialogue->get('id');?>&vote=down"
        ><span class="glyphicon glyphicon-thumbs-down" >(<?php echo $dialogue->getVoteManager()->countVoteDown();?>)</span></a>
        &nbsp;
        
        <a href="/?module=movie&action=dialogue/detail&movie=<?php echo $movie->get('id')?>&dialogue=<?php echo $dialogue->get('id');?>"
        ><span>评论(<?php echo $dialogue->getCommentManager()->count();?>)</span></a>
        &nbsp;
    </div>
    <?php endforeach; ?>
<?php endif; ?>