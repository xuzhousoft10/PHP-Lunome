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
                        alt="<?php echo $character['name']?>"
                        src="<?php echo $character['imageURL'];?>"
                >
            </div>
            <div class="col-md-10">
                <p><strong><?php echo $character['name'];?></strong></p>
                <p><?php echo $character['description'];?></p>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<div>
    <nav>
        <ul class="pager">
            <li class="previous<?php echo (false === $vars['pager']['prev']) ? ' disabled' : ''; ?>">
                <?php if (false !== $vars['pager']['prev']) :?>
                    <a  href="/?module=lunome&action=movie/character/index&id=<?php echo $vars['id']; ?>&page=<?php echo $vars['pager']['prev'];?>"
                        class="movie-characters-container-pager"
                    >&larr; 上一页</a>
                <?php endif; ?>
            </li>
            <?php if ( $vars['isWatched'] ): ?>
            <li id="movie-characters-add">
                <a href="#" data-toggle="modal" data-target="#movie-characters-edit-dialog">
                    添加角色
                </a>
            </li>
            <?php endif; ?>
            <li class="next<?php echo (false === $vars['pager']['next']) ? ' disabled' : ''; ?>">
                <?php if (false !== $vars['pager']['next']) :?>
                    <a  href="/?module=lunome&action=movie/character/index&id=<?php echo $vars['id']; ?>&page=<?php echo $vars['pager']['next'];?>"
                        class="movie-characters-container-pager"
                    >下一页&rarr;</a>
                <?php endif; ?>
            </li>
        </ul>
    </nav>
</div>