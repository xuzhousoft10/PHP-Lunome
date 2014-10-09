<?php 
use X\Module\Lunome\Service\Movie\Service as MovieService;
$vars = get_defined_vars();
$movies = $vars['medias'];
$pager = $vars['pager'];
$mark = $vars['markInfo']['active'];

$particleViewUtilPath = implode(DIRECTORY_SEPARATOR, array(dirname(__FILE__), 'Util'));

/* Genereate the item template map. */
$indexItemBasePath = $particleViewUtilPath.DIRECTORY_SEPARATOR.'Item';
$itemMap = array(
    MovieService::MARK_UNMARKED    => $indexItemBasePath.DIRECTORY_SEPARATOR.'Unmarked.php',
    MovieService::MARK_INTERESTED  => $indexItemBasePath.DIRECTORY_SEPARATOR.'Interested.php',
    MovieService::MARK_WATCHED     => $indexItemBasePath.DIRECTORY_SEPARATOR.'Watched.php',
    MovieService::MARK_IGNORED     => $indexItemBasePath.DIRECTORY_SEPARATOR.'Ignored.php',
);
?>
<div class="panel panel-default">
    <?php require dirname(__FILE__).DIRECTORY_SEPARATOR.'Util'.DIRECTORY_SEPARATOR.'ListHeader.php'; ?>
    <!-- Movies -->
    <div class="panel-body">
        <div class="clearfix" style="width: 900px; margin-left:auto; margin-right:auto">
            <?php foreach ( $movies as $index => $movie ) : ?>
                <?php require $itemMap[$mark]; ?>
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
