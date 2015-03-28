<?php use X\Core\X; ?>
<?php $vars = get_defined_vars(); ?>
<?php $assetsURL = X::system()->getConfiguration()->get('assets-base-url'); ?>
<?php $characters = $vars['characters']; ?>
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
        <div class="thumbnail clearfix">
            <div class="col-md-2">
                <img    class="img-thumbnail lunome-image-60" 
                        alt="<?php echo $character->get('name'); ?>"
                        src="<?php echo $character->getPhotoURL(); ?>"
                >
            </div>
            <div class="col-md-10">
                <p><strong><?php echo $character->get('name');?></strong></p>
                <p><?php echo $character->get('description');?></p>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if ( $vars['isWatched'] ): ?>
        <?php ob_start(); ?>
        <?php ob_implicit_flush(false); ?>
        <li id="movie-characters-add">
            <a href="#" data-toggle="modal" data-target="#movie-characters-edit-dialog">
                添加角色
            </a>
        </li>
    <?php endif; ?>
    
    <?php $vars['pager']->addViewToCenter(ob_get_clean()); ?>
    <?php $vars['pager']->setPrevPageButtonClass('movie-characters-container-pager'); ?>
    <?php $vars['pager']->setNextPageButtonClass('movie-characters-container-pager'); ?>
    <?php $vars['pager']->show(); ?>
<?php endif; ?>