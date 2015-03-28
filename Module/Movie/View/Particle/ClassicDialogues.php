<?php 
$vars = get_defined_vars();
$assetsURL = $vars['assetsURL'];
$dialogues = $vars['dialogues']; 
?>
<?php if ( empty( $dialogues ) ) : ?>
    <div class="clearfix">
        <div class="pull-left">
            <img src="<?php echo $assetsURL;?>/image/nothing.gif" width="100" height="100">
        </div>
        <div class="margin-top-70 text-muted">
            <small>经典台词空空的~~~</small>
        </div>
    </div>
<?php else :?>
    <?php foreach ( $dialogues as $dialogue ) : ?>
        <div class="well well-sm">
            <?php echo $dialogue->get('content');?>
        </div>
    <?php endforeach; ?>
    
    <?php if ( $vars['isWatched'] ): ?>
        <?php ob_start(); ?>
        <?php ob_implicit_flush(false); ?>
        <li id="movie-posters-add">
            <a href="#" data-toggle="modal" data-target="#movie-classic-dialogues-edit-dialog">
                添加经典台词
            </a>
        </li>
    <?php endif; ?>
    <?php $vars['pager']->addViewToCenter(ob_get_clean()); ?>
    <?php $vars['pager']->setPrevPageButtonClass('movie-classic-dialogues-container-pager'); ?>
    <?php $vars['pager']->setNextPageButtonClass('movie-classic-dialogues-container-pager'); ?>
    <?php $vars['pager']->show(); ?>
<?php endif; ?>