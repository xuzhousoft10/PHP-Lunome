<?php 
$vars = get_defined_vars();
$pager = $vars['pager'];
?>
<ul class="pagination pull-right">
<li>
    <a  href    = "/?module=lunome&action=comic/index&mark=<?php echo $pager['params']['mark'];?>" 
        class   = "<?php if (!$pager['canPrev']):?>disabled<?php endif;?>"
    >首页</a>
</li>
<li>
    <a  href    = "/?module=lunome&action=comic/index&mark=<?php echo $pager['params']['mark'];?>&page=<?php echo $pager['prev'];?>" 
        class   = "<?php if (!$pager['canPrev']):?>disabled<?php endif;?>"
    >上一页</a>
</li>
<?php foreach ($pager['items'] as $pageItem ) : ?>
    <li <?php if ($pageItem == $pager['current']) :?>class="active"<?php endif;?>>
        <a href="/?module=lunome&action=comic/index&mark=<?php echo $pager['params']['mark'];?>&page=<?php echo $pageItem;?>"><?php echo $pageItem;?></a>
    </li>
<?php endforeach; ?>
<li>
    <a  href    = "/?module=lunome&action=comic/index&mark=<?php echo $pager['params']['mark'];?>&page=<?php echo $pager['next'];?>" 
        class   = "<?php if (!$pager['canNext']):?>disabled<?php endif;?>"
    >下一页</a>
</li>
<li>
    <a  href    = "/?module=lunome&action=comic/index&mark=<?php echo $pager['params']['mark'];?>&page=<?php echo $pager['total'];?>" 
        class   = "<?php if (!$pager['canPrev']):?>disabled<?php endif;?>"
    >尾页</a>
</li>
</ul>