<?php $vars = get_defined_vars(); ?>
<?php $dialogues = $vars['dialogues']; ?>
<?php foreach ( $dialogues as $dialogue ) : ?>
    <div class="well well-sm">
        <?php echo $dialogue['content'];?>
    </div>
<?php endforeach; ?>
<div>
    <nav>
        <ul class="pager">
            <li class="previous<?php echo (false === $vars['pager']['prev']) ? ' disabled' : ''; ?>">
                <?php if (false === $vars['pager']['prev']) :?>
                    <a href="#" class="movie-classic-dialogues-container-pager">&larr; 上一页</a>
                <?php else:?>
                    <a  href="/?module=lunome&action=movie/classicDialogue/index&id=<?php echo $vars['id']; ?>&page=<?php echo $vars['pager']['prev'];?>"
                        class="movie-classic-dialogues-container-pager"
                    >&larr; 上一页</a>
                <?php endif; ?>
            </li>
            <?php if ( $vars['isWatched'] ): ?>
            <li id="movie-posters-add">
                <a href="#" data-toggle="modal" data-target="#movie-classic-dialogues-add-dialog">
                    添加经典台词
                </a>
            </li>
            <?php endif; ?>
            <li class="next<?php echo (false === $vars['pager']['next']) ? ' disabled' : ''; ?>">
                <?php if (false === $vars['pager']['next']) :?>
                    <a href="#" class="movie-classic-dialogues-container-pager">下一页&rarr;</a>
                <?php else:?>
                    <a  href="/?module=lunome&action=movie/classicDialogue/index&id=<?php echo $vars['id']; ?>&page=<?php echo $vars['pager']['next'];?>"
                        class="movie-classic-dialogues-container-pager"
                    >下一页&rarr;</a>
                <?php endif; ?>
            </li>
        </ul>
    </nav>
</div>