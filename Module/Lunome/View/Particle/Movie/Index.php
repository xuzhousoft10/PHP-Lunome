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
        <a href="/?module=lunome&action=movie/index&mark=<?php echo $pager['mark'];?>&page=<?php echo $pager['next'];?>" class="btn btn-primary btn-sm pull-right">Next</a>
        <span class="pull-right">&nbsp;&nbsp;</span>
        <a href="/?module=lunome&action=movie/index&mark=<?php echo $pager['mark'];?>&page=<?php echo $pager['prev'];?>" class="btn btn-primary btn-sm pull-right">Prev</a>
    </div>
</div>
