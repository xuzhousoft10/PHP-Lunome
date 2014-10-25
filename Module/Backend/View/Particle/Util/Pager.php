<?php 
use X\Library\XMath\Number;
$vars       = get_defined_vars();
$total      = $vars['total'];
$current    = $vars['current']*1;
$size       = $vars['size'];
$url        = $vars['url'];
$pageCount  = (0 === $total%$size) ? $total/$size : intval($total/$size)+1;
$items      = Number::getRound($current, 20, 1, $pageCount);
?>
<div>
    <ul class="pagination">
        <li><a href="<?php printf($url, 1);?>">首页</a></li>
        <li <?php if (1>=$current):?>class="disabled"<?php endif;?>>
            <a href="<?php printf($url, (1>=$current)?1:$current-1);?>">上一页</a>
        </li>
        <?php foreach ( $items as $pageNumber ) :?>
            <li <?php if ( $current===$pageNumber ): ?>class="active"<?php endif; ?>>
                <a href="<?php printf($url, $pageNumber);?>"><?php echo $pageNumber;?></a>
            </li>
        <?php endforeach; ?>
        <li <?php if ($pageCount<=$current):?>class="disabled"<?php endif;?>>
            <a href="<?php printf($url, ($pageCount<=$current)?$pageCount:$current+1);?>">下一页</a>
        </li>
        <li><a href="<?php printf($url, $pageCount);?>">尾页</a></li>
    </ul>
</div>