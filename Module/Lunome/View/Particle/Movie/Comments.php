<?php use X\Core\X; ?>
<?php $vars = get_defined_vars(); ?>
<?php $assetsURL = X::system()->getConfiguration()->get('assets-base-url'); ?>
<?php $comments = $vars['comments']; ?>
<div>
    <ul class="nav nav-tabs nav-justified">
        <li class="<?php if('friends'===$vars['scope']):?>active<?php endif;?>">
            <a  href="/?module=lunome&action=movie/comment/index&id=<?php echo $vars['id'];?>&scope=friends"
                class="movie-comments-container-pager"
            >好友</a>
        </li>
        <li class="<?php if('all'===$vars['scope']):?>active<?php endif;?>">
            <a  href="/?module=lunome&action=movie/comment/index&id=<?php echo $vars['id'];?>&scope=all"
                class="movie-comments-container-pager"
            >全网</a>
        </li>
    </ul>
</div>
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
    <br>
    <div>
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
    </div>
<?php endif; ?>
<?php $pager = $vars['pager']; ?>
<?php if ( false !== $pager['prev'] || false !== $pager['next'] ) : ?>
<div>
    <nav>
        <ul class="pager">
            <li class="previous<?php echo (false === $pager['prev']) ? ' disabled' : ''; ?>">
                <?php if (false !== $pager['prev']) :?>
                    <a  href="/?module=lunome&action=movie/comment/index&id=<?php echo $vars['id'];?>&page=<?php echo $pager['prev'];?>&scope=<?php echo $vars['scope'];?>"
                        class="movie-comments-container-pager"
                    >&larr; 上一页</a>
                <?php endif; ?>
            </li>
            <li><?php echo $pager['current'];?> / <?php echo $pager['pageCount'];?></li>
            <li class="next<?php echo (false === $pager['next']) ? ' disabled' : ''; ?>">
                <?php if (false !== $pager['next']) :?>
                    <a  href="/?module=lunome&action=movie/comment/index&id=<?php echo $vars['id'];?>&page=<?php echo $pager['next'];?>&scope=<?php echo $vars['scope'];?>"
                        class="movie-comments-container-pager"
                    >下一页&rarr;</a>
                <?php endif; ?>
            </li>
        </ul>
    </nav>
</div>
<?php endif; ?>