<?php $vars = get_defined_vars(); ?>
<?php $posters = $vars['posters']; ?>
<?php foreach ( $posters as $poster ) : ?>
    <img    src="<?php echo $poster['url']; ?>" 
            class="img-thumbnail margin-5 lunome-image-100-150 movie-poster-item"
    >
<?php endforeach; ?>
<div>
    <nav>
        <ul class="pager">
            <li class="previous<?php echo (false === $vars['pager']['prev']) ? ' disabled' : ''; ?>">
                <?php if (false === $vars['pager']['prev']) :?>
                    <a href="#" class="movie-posters-container-pager">&larr; 上一页</a>
                <?php else:?>
                    <a  href="/?module=lunome&action=movie/poster/index&id=<?php echo $vars['id']; ?>&page=<?php echo $vars['pager']['prev'];?>"
                        class="movie-posters-container-pager"
                    >&larr; 上一页</a>
                <?php endif; ?>
            </li>
            <?php if ( $vars['isWatched'] ): ?>
            <li id="movie-posters-add">
                <a href="#" data-toggle="modal" data-target="#movie-posters-add-dialog">
                    添加海报
                </a>
            </li>
            <?php endif; ?>
            <li class="next<?php echo (false === $vars['pager']['next']) ? ' disabled' : ''; ?>">
                <?php if (false === $vars['pager']['next']) :?>
                    <a href="#" class="movie-posters-container-pager">下一页&rarr;</a>
                <?php else:?>
                    <a  href="/?module=lunome&action=movie/poster/index&id=<?php echo $vars['id']; ?>&page=<?php echo $vars['pager']['next'];?>"
                        class="movie-posters-container-pager"
                    >下一页&rarr;</a>
                <?php endif; ?>
            </li>
        </ul>
    </nav>
</div>