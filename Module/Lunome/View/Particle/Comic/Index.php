<?php
use X\Module\Lunome\Service\Comic\Service as ComicService;
$vars   = get_defined_vars();
$comics = $vars['medias'];
$pager  = $vars['pager'];
$mark   = $vars['markInfo']['active'];

$particleViewUtilPath = implode(DIRECTORY_SEPARATOR, array(dirname(__FILE__), 'Util'));

/* Genereate the item template map. */
$indexItemBasePath = $particleViewUtilPath.DIRECTORY_SEPARATOR.'Item';
$itemMap = array(
    ComicService::MARK_UNMARKED    => $indexItemBasePath.DIRECTORY_SEPARATOR.'Unmarked.php',
    ComicService::MARK_INTERESTED  => $indexItemBasePath.DIRECTORY_SEPARATOR.'Interested.php',
    ComicService::MARK_WATCHING    => $indexItemBasePath.DIRECTORY_SEPARATOR.'Watching.php',
    ComicService::MARK_WATCHED     => $indexItemBasePath.DIRECTORY_SEPARATOR.'Watched.php',
    ComicService::MARK_IGNORED     => $indexItemBasePath.DIRECTORY_SEPARATOR.'Ignored.php',
);
?>
<div class="panel panel-default">
    <?php require dirname(__FILE__).DIRECTORY_SEPARATOR.'Util'.DIRECTORY_SEPARATOR.'ListHeader.php'; ?>
    <!-- Movies -->
    <div class="panel-body">
        <div class="clearfix" style="width: 900px; margin-left:auto; margin-right:auto">
            <?php foreach ( $comics as $index => $comic ) : ?>
                <?php require $itemMap[$mark]; ?>
            <?php endforeach; ?>
            <?php unset($index); ?>
            <?php unset($comics); ?>
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
