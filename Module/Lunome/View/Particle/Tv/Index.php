<?php
use X\Module\Lunome\Service\Tv\Service as TvService;
$vars = get_defined_vars();
$movies = $vars['medias'];
$pager = $vars['pager'];
$mark = $vars['markInfo']['active'];

$particleViewUtilPath = implode(DIRECTORY_SEPARATOR, array(dirname(__FILE__), 'Util'));

/* Genereate the item template map. */
$indexItemBasePath = $particleViewUtilPath.DIRECTORY_SEPARATOR.'Item';
$itemMap = array(
    TvService::MARK_UNMARKED    => $indexItemBasePath.DIRECTORY_SEPARATOR.'Unmarked.php',
    TvService::MARK_INTERESTED  => $indexItemBasePath.DIRECTORY_SEPARATOR.'Interested.php',
    TvService::MARK_WATCHING    => $indexItemBasePath.DIRECTORY_SEPARATOR.'Watching.php',
    TvService::MARK_WATCHED     => $indexItemBasePath.DIRECTORY_SEPARATOR.'Watched.php',
    TvService::MARK_IGNORED     => $indexItemBasePath.DIRECTORY_SEPARATOR.'Ignored.php',
);
?>
<div class="panel panel-default">
    <?php require dirname(__FILE__).DIRECTORY_SEPARATOR.'Util'.DIRECTORY_SEPARATOR.'ListHeader.php'; ?>
    <!-- Movies -->
    <div class="panel-body">
        <div class="clearfix" style="width: 900px; margin-left:auto; margin-right:auto">
            <?php foreach ( $movies as $index => $tv ) : ?>
                <?php require $itemMap[$mark]; ?>
            <?php endforeach; ?>
            <?php unset($index); ?>
            <?php unset($tv); ?>
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
        <?php require $particleViewUtilPath.DIRECTORY_SEPARATOR.'Pager.php'; ?>
    </div>
</div>
