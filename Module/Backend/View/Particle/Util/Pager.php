<?php
$vars = get_defined_vars();
$totalCount = $vars['totalCount'];
$currentPage = $vars['currentPage'];
$pageSize = $vars['pageSize'];
$parameters = $vars['parameters'];
?>
<?php 
$totalPage = (0===$totalCount%$pageSize) ? $totalCount/$pageSize : ((int)($totalCount/$pageSize))+1;
$prevPage = (1 >= $currentPage) ? false : $currentPage-1;
$nextPage = ($currentPage>=$totalPage) ? false : $currentPage+1;
?>
<nav>
    <ul class="pager">
        <?php if ( false !== $prevPage ): ?>
            <li class="previous">
                <a href="/?<?php echo $parameters; ?>&page=<?php echo $prevPage; ?>"
                ><span>&larr;</span>上一页</a>
            </li>
        <?php endif; ?>
        <li><?php echo $currentPage; ?>/<?php echo $totalPage; ?></li>
        <?php if (false !== $nextPage): ?>
            <li class="next">
                <a href="/?<?php echo $parameters; ?>&page=<?php echo $nextPage; ?>"
                >下一页<span>&rarr;</span></a>
            </li>
        <?php endif; ?>
    </ul>
</nav>