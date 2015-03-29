<?php 
$vars = get_defined_vars();
$assetsURL = $vars['assetsURL'];
$posters = $vars['posters'];
$pager = $vars['pager'];
$id = $vars['id'];
?>
<?php if (empty($posters)) : ?>
    <div class="clearfix">
        <div class="pull-left">
            <img src="<?php echo $assetsURL;?>/image/nothing.gif" width="100" height="100">
        </div>
        <div class="margin-top-70 text-muted">
            <small>海报空空的~~~</small>
        </div>
    </div>
<?php else : ?>
    <?php foreach ( $posters as $poster ) : ?>
        <img    src="<?php echo $poster->getURL(); ?>" 
                class="img-thumbnail margin-5 lunome-image-100-150 movie-poster-item"
        >
    <?php endforeach; ?>
    
    <?php if ( $vars['isWatched'] ): ?>
        <?php ob_start(); ?>
        <?php ob_implicit_flush(false); ?>
        <li id="movie-posters-add">
            <a href="#" data-toggle="modal" data-target="#movie-posters-add-dialog">
                添加海报
            </a>
        </li>
    <?php endif; ?>
    
    <?php $vars['pager']->addViewToCenter(ob_get_clean()); ?>
    <?php $vars['pager']->setPrevPageButtonClass('movie-posters-container-pager'); ?>
    <?php $vars['pager']->setNextPageButtonClass('movie-posters-container-pager'); ?>
    <?php $vars['pager']->show(); ?>
<?php endif; ?>