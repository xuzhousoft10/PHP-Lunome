<?php use X\Core\X; ?>
<?php use X\Service\XView\Core\Handler\Html; ?>
<?php $vars = get_defined_vars(); ?>
<?php $movie = $vars['movie']; ?>
<?php /* @var $dialogue \X\Module\Movie\Service\Movie\Core\Instance\ClassicDialogue */ ?>
<?php $dialogue = $vars['dialogue']; ?>
<ol class="breadcrumb">
    <li><a href="/?module=movie&action=index">电影</a></li>
    <li><a href="/?module=movie&action=detail&id=<?php echo $movie->get('id'); ?>"><?php echo Html::HTMLEncode($movie->get('name'));?></a></li>
    <li><a href="/?module=movie&action=dialogue/index&movie=<?php echo $movie->get('id');?>">经典台词</a></li>
    <li class="active"><?php echo Html::HTMLEncode(mb_substr($dialogue->get('content'), 0, 10, 'UTF-8')); ?>...</li>
</ol>

<div class="media">
    <div class="media-left">
        <?php $character = $dialogue->getCharacter(); ?>
        <?php if (null === $character) : ?>
            <img class="media-object img-rounded lunome-movie-character-photo-80-80" 
                src="<?php echo $vars['assetsURL'];?>/image/movie_default_character_photo.jpg" 
                alt="匿名"
            >
        <?php else : ?>
            <a href="/?module=movie&action=character/detail&movie=<?php echo $movie->get('id');?>&character=<?php echo $character->get('id');?>">
                <img class="media-object img-rounded lunome-movie-character-photo-80-80" 
                    src="<?php echo $character->getPhotoURL(); ?>" 
                    alt="<?php echo Html::HTMLAttributeEncode($character->get('name')); ?>"
                >
            </a>
        <?php endif; ?>
    </div>
    
    <div class="media-body">
        <blockquote class=" padding-left-10">
            <p class="margin-0"><?php echo Html::HTMLEncode($dialogue->get('content')); ?></p>
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
</div>

<br>
<h4>人物评论</h4>
<hr class="margin-0">
<?php /* @var $movieAccount \X\Module\Movie\Service\Movie\Core\Instance\Account */ ?>
<?php /* @var $currentAccount \X\Module\Account\Service\Account\Core\Instance\Account */ ?>
<?php $currentAccount = $vars['currentAccount']; ?>
<?php $movieAccount = $vars['movieAccount']; ?>
<?php if ( $movieAccount->isWatched($movie->get('id')) ) : ?>
    <form action="/?module=movie&action=dialogue/comment" method="post">
        <input type="hidden" name="movie" value="<?php echo $movie->get('id');?>" >
        <input type="hidden" name="dialogue" value="<?php echo $dialogue->get('id'); ?>" >
        <div class="media">
            <div class="media-left">
                <img class="media-object img-rounded lunome-movie-character-photo-80-80" 
                    src="<?php echo $currentAccount->getProfileManager()->get('photo'); ?>" 
                    alt="<?php echo Html::HTMLAttributeEncode($currentAccount->getProfileManager()->get('nickname')); ?>"
                >
            </div>
            
            <div class="media-body">
                <div class="input-group">
                    <textarea name="content" class="form-control" style="height: 80px;width:680px"></textarea>
                    <span class="input-group-btn">
                        <button class="btn btn-default lunome-height-80" style="width:80px;" type="submit">发表</button>
                    </span>
                </div>
            </div>
        </div>
    </form>
    <hr>
<?php endif; ?>

<?php $comments = $dialogue->getCommentManager()->find(); ?>
<?php if (empty($comments)) : ?>
    <p>暂无任何评论信息～～～</p>
<?php else : ?>
    <?php foreach ( $comments as $comment ):?>
        <blockquote class="padding-0 padding-left-10">
            <p class="margin-0"><?php echo Html::HTMLEncode($comment->getContent()); ?></p>
            <small>
                <a href="/?module=account&action=home/index&id=<?php echo $comment->getAuthor()->getID()?>"
                ><?php echo Html::HTMLEncode($comment->getAuthor()->getProfileManager()->get('nickname')); ?></a>
                &nbsp;
                <?php echo $comment->getTime(); ?>
            </small>
        </blockquote>
    <?php endforeach; ?>
<?php endif; ?>