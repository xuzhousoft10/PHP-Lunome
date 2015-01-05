<?php use X\Core\X; ?>
<?php $vars = get_defined_vars(); ?>
<?php $assetsURL = X::system()->getConfiguration()->get('assets-base-url'); ?>
<?php $comments = $vars['comments']; ?>
<?php if (empty($comments)) : ?>
    <div class="clearfix">
        <div class="pull-left">
            <img src="<?php echo $assetsURL;?>/image/nothing.gif" width="100" height="100">
        </div>
        <div class="margin-top-70 text-muted">
            <small>评论空空的~~~</small>
        </div>
    </div>
<?php else: ?>
    <?php foreach ( $comments as $comment ) : ?>
        <div class="media">
            <div class="pull-left">
                <img src="<?php echo $comment['user']['photo'];?>" width="50" height="50" class="thumbnail margin-bottom-0">
            </div>
            <div class="media-body">
                <strong><?php echo $comment['user']['nickname']; ?></strong>
                <span>&nbsp;&nbsp;&nbsp;</span>
                <small class="text-muted"><?php echo $comment['content']['commented_at'];?></small>
                <br>
                <span><?php echo $comment['content']['content']; ?></span>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<?php $pager = $vars['pager']; ?>
<?php if ( false !== $pager['prev'] || false !== $pager['next'] ) : ?>
<div>
    <nav>
        <ul class="pager">
            <li class="previous<?php echo (false === $pager['prev']) ? ' disabled' : ''; ?>">
                <?php if (false !== $pager['prev']) :?>
                    <a  href="/?module=lunome&action=movie/home/index&id=99cbddc4-ac78-40c6-a986-343e438d56afpage=<?php echo $pager['prev'];?>"
                        class="movie-characters-container-pager"
                    >&larr; 上一页</a>
                <?php endif; ?>
            </li>
            <li><?php echo $pager['current'];?> / <?php echo $pager['pageCount'];?></li>
            <li class="next<?php echo (false === $pager['next']) ? ' disabled' : ''; ?>">
                <?php if (false !== $pager['next']) :?>
                    <a  href="/?module=lunome&action=user/friend/index&page=<?php echo $pager['next'];?>"
                        class="movie-characters-container-pager"
                    >下一页&rarr;</a>
                <?php endif; ?>
            </li>
        </ul>
    </nav>
</div>
<?php endif; ?>