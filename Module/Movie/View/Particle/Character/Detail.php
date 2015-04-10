<?php use X\Core\X; ?>
<?php use X\Service\XView\Core\Handler\Html; ?>
<?php $vars = get_defined_vars(); ?>
<?php $assetsURL = X::system()->getConfiguration()->get('assets-base-url'); ?>
<?php /* @var $character \X\Module\Movie\Service\Movie\Core\Instance\Character */ ?>
<?php $character = $vars['character']; ?>
<?php $movie = $vars['movie']; ?>
<?php $currentAccount = $vars['currentAccount']; ?>
<ol class="breadcrumb">
    <li><a href="/?module=movie&action=index">电影</a></li>
    <li><a href="/?module=movie&action=detail&id=<?php echo $movie->get('id'); ?>"><?php echo Html::HTMLEncode($movie->get('name'));?></a></li>
    <li><a href="/?module=movie&action=character/index&movie=<?php echo $movie->get('id');?>">人物角色</a></li>
    <li class="active"><?php echo $character->get('name'); ?></li>
</ol>
    
<div class="media">
    <div class="media-left">
        <img class="media-object img-rounded lunome-movie-character-photo-80-80" 
            src="<?php echo $character->getPhotoURL(); ?>" 
            alt="<?php echo $character->get('name'); ?>"
        >
    </div>
    
    <div class="media-body">
        <h4 class="media-heading">
            <a href="/?module=movie&action=character/detail&movie=<?php echo $movie->get('id')?>&character=<?php echo $character->get('id');?>">
                <?php echo $character->get('name');?>
            </a>
        </h4>
        <?php echo $character->get('description');?>
    </div>
</div>

<div class="text-right">
    <a href="/?module=movie&action=character/vote&movie=<?php echo $movie->get('id')?>&character=<?php echo $character->get('id');?>&vote=up"
    ><span class="glyphicon glyphicon-thumbs-up" >(<?php echo $character->getVoteManager()->countVoteUp(); ?>)</span></a>
    &nbsp;
    
    <a href="/?module=movie&action=character/vote&movie=<?php echo $movie->get('id')?>&character=<?php echo $character->get('id');?>&vote=down"
    ><span class="glyphicon glyphicon-thumbs-down" >(<?php echo $character->getVoteManager()->countVoteDown();?>)</span></a>
</div>

<br>
<h4>人物评论</h4>
<hr class="margin-0">
<?php /* @var $movieAccount \X\Module\Movie\Service\Movie\Core\Instance\Account */ ?>
<?php /* @var $currentAccount \X\Module\Account\Service\Account\Core\Instance\Account */ ?>
<?php $movieAccount = $vars['movieAccount']; ?>
<?php if ( $movieAccount->isWatched($movie->get('id')) ) : ?>
    <form action="/?module=movie&action=character/comment" method="post">
        <input type="hidden" name="movie" value="<?php echo $movie->get('id');?>" >
        <input type="hidden" name="character" value="<?php echo $character->get('id'); ?>" >
        <div class="media">
            <div class="media-left">
                <img class="media-object img-rounded lunome-movie-character-photo-80-80" 
                    src="<?php echo $currentAccount->getProfileManager()->get('photo'); ?>" 
                    alt="<?php echo $currentAccount->getProfileManager()->get('nickname'); ?>"
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

<?php $comments = $character->getCommentManager()->find(); ?>
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