<?php 
$vars = get_defined_vars();
$movies = $vars['movies'];
$pager = $vars['pager'];
$mark = $vars['mark'];
?>
<div class="panel panel-default">
    <?php require dirname(__FILE__).DIRECTORY_SEPARATOR.'Util'.DIRECTORY_SEPARATOR.'ListHeader.php'; ?>
    <!-- Movies -->
    <div class="panel-body">
        <div class="clearfix" style="width: 900px; margin-left:auto; margin-right:auto">
            <?php foreach ( $movies as $index => $movie ) : ?>
                <?php require dirname(__FILE__).DIRECTORY_SEPARATOR.'Util'.DIRECTORY_SEPARATOR.ucfirst($mark).'Item.php'; ?>
            <?php endforeach; ?>
            <?php unset($index); ?>
            <?php unset($movie); ?>
        </div>
        <script type="text/javascript">
        $(document).ready(function() {
            $('.movie-item').mouseenter(function() {
                $(this).children().show();
            });
            $('.movie-item').mouseleave(function() {
                $(this).children().hide();
            });
        });
        </script>
    </div>
  
    <!-- Pager -->
    <div class="panel-footer clearfix">
        <ul class="pagination pull-right">
        <li>
            <a  href    = "/?module=lunome&action=movie/index&mark=<?php echo $pager['params']['mark'];?>" 
                class   = "<?php if (!$pager['canPrev']):?>disabled<?php endif;?>"
            >首页</a>
        </li>
        <li>
            <a  href    = "/?module=lunome&action=movie/index&mark=<?php echo $pager['params']['mark'];?>&page=<?php echo $pager['prev'];?>" 
                class   = "<?php if (!$pager['canPrev']):?>disabled<?php endif;?>"
            >上一页</a>
        </li>
        <?php foreach ($pager['items'] as $pageItem ) : ?>
            <li <?php if ($pageItem == $pager['current']) :?>class="active"<?php endif;?>>
                <a href="/?module=lunome&action=movie/index&mark=<?php echo $pager['params']['mark'];?>&page=<?php echo $pageItem;?>"><?php echo $pageItem;?></a>
            </li>
        <?php endforeach; ?>
        <li>
            <a  href    = "/?module=lunome&action=movie/index&mark=<?php echo $pager['params']['mark'];?>&page=<?php echo $pager['next'];?>" 
                class   = "<?php if (!$pager['canNext']):?>disabled<?php endif;?>"
            >下一页</a>
        </li>
        <li>
            <a  href    = "/?module=lunome&action=movie/index&mark=<?php echo $pager['params']['mark'];?>&page=<?php echo $pager['total'];?>" 
                class   = "<?php if (!$pager['canPrev']):?>disabled<?php endif;?>"
            >尾页</a>
        </li>
        </ul>
    </div>
</div>
