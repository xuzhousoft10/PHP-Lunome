<?php use X\Core\X; ?>
<?php use X\Service\XView\Core\Handler\Html; ?>
<?php $vars = get_defined_vars(); ?>
<?php $movie = $vars['movie']; ?>
<?php /* @var $comment \X\Module\Movie\Service\Movie\Core\Instance\ShortComment */ ?>
<?php $comment = $vars['comment']; ?>
<ol class="breadcrumb">
    <li><a href="/?module=movie&action=index">电影</a></li>
    <li><a href="/?module=movie&action=detail&id=<?php echo $movie->get('id'); ?>"><?php echo Html::HTMLEncode($movie->get('name'));?></a></li>
    <li><a href="/?module=movie&action=comment/index&movie=<?php echo $movie->get('id');?>">经典台词</a></li>
    <li class="active"><?php echo Html::HTMLEncode(mb_substr($comment->get('content'), 0, 10, 'UTF-8')); ?>...</li>
</ol>

<div class="media">
    <div class="media-left">
        <?php $commenter = $comment->getCommenter()->getProfileManager(); ?>
        <img class="media-object img-rounded lunome-movie-character-photo-80-80" 
            src="<?php echo $commenter->get('photo'); ?>" 
            alt="<?php echo Html::HTMLAttributeEncode($commenter->get('nickname')); ?>"
        >
    </div>
    
    <div class="media-body">
        <blockquote class=" padding-left-10">
            <p class="margin-0"><?php echo Html::HTMLEncode($comment->get('content')); ?></p>
            <br>
            <small>
                <?php echo Html::HTMLAttributeEncode($commenter->get('nickname')); ?>
            </small>
        </blockquote>
    </div>
</div>

<div class="text-right">
    <?php $favouriteManager = $comment->getFavouriteManager(); ?>
    <?php if ( $favouriteManager->isMyFavourite() ):  ?>
        <a href="/?module=movie&action=comment/like&movie=<?php echo $movie->get('id')?>&comment=<?php echo $comment->get('id');?>&like=no"
        ><span class="glyphicon glyphicon-heart" >(<?php echo $comment->getFavouriteManager()->count(); ?>)</span></a>
    <?php else : ?>
        <a href="/?module=movie&action=comment/like&movie=<?php echo $movie->get('id')?>&comment=<?php echo $comment->get('id');?>&like=yes"
        ><span class="glyphicon glyphicon-heart-empty" >(<?php echo $comment->getFavouriteManager()->count(); ?>)</span></a>
    <?php endif; ?>
    &nbsp;
    
    <a href="/?module=movie&action=comment/vote&movie=<?php echo $movie->get('id')?>&comment=<?php echo $comment->get('id');?>&vote=up"
    ><span class="glyphicon glyphicon-thumbs-up" >(<?php echo $comment->getVoteManager()->countVoteUp(); ?>)</span></a>
    &nbsp;
    
    <a href="/?module=movie&action=comment/vote&movie=<?php echo $movie->get('id')?>&comment=<?php echo $comment->get('id');?>&vote=down"
    ><span class="glyphicon glyphicon-thumbs-down" >(<?php echo $comment->getVoteManager()->countVoteDown();?>)</span></a>
</div>

<br>
<h4>人物评论</h4>
<hr class="margin-0">
<?php /* @var $movieAccount \X\Module\Movie\Service\Movie\Core\Instance\Account */ ?>
<?php /* @var $currentAccount \X\Module\Account\Service\Account\Core\Instance\Account */ ?>
<?php $currentAccount = $vars['currentAccount']; ?>
<?php $movieAccount = $vars['movieAccount']; ?>
<?php if ( $movieAccount->isWatched($movie->get('id')) ) : ?>
    <form action="/?module=movie&action=comment/comment" method="post">
        <input type="hidden" name="movie" value="<?php echo $movie->get('id');?>" >
        <input type="hidden" name="comment" value="<?php echo $comment->get('id'); ?>" >
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

<?php $comments = $comment->getCommentManager()->find(); ?>
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