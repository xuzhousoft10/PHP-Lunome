<?php use X\Core\X; ?>
<?php use X\Service\XView\Core\Handler\Html; ?>
<?php $vars = get_defined_vars(); ?>
<?php $assetsURL = X::system()->getConfiguration()->get('assets-base-url'); ?>
<?php $characters = $vars['characters']; ?>
<?php $movie = $vars['movie']; ?>
<ol class="breadcrumb">
    <li><a href="/?module=movie&action=index">电影</a></li>
    <li><a href="/?module=movie&action=detail&id=<?php echo $movie->get('id'); ?>"><?php echo Html::HTMLEncode($movie->get('name'));?></a></li>
    <li class="active">人物角色</li>
</ol>
<?php if ( empty($characters) ): ?>
    <div class="clearfix">
        <div class="pull-left">
            <img src="<?php echo $assetsURL;?>/image/nothing.gif" width="100" height="100">
        </div>
        <div class="margin-top-70 text-muted">
            <small>角色空空的~~~</small>
        </div>
    </div>
<?php else : ?>
    <?php foreach ( $characters as $character ) :?>
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
        <a href="/?module=movie&action=character/voteup&movie=<?php echo $movie->get('id')?>&character=<?php echo $character->get('id');?>">
            <span class="glyphicon glyphicon-thumbs-up" >(<?php echo rand(10, 10000000);?>)</span>
        </a>
        &nbsp;
        
        <a href="/?module=movie&action=character/votedown&movie=<?php echo $movie->get('id')?>&character=<?php echo $character->get('id');?>">
            <span class="glyphicon glyphicon-thumbs-down" >(<?php echo rand(10, 10000000);?>)</span>
        </a>
        &nbsp;
        
        <a href="/?module=movie&action=character/detail&movie=<?php echo $movie->get('id')?>&character=<?php echo $character->get('id');?>">
            <span>评论(<?php echo rand(10, 10000000);?>)</span>
        </a>
        &nbsp;
    </div>
    <?php endforeach; ?>
<?php endif; ?>


  
  
  
