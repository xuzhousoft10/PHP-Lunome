<?php
use X\Module\Lunome\Service\Game\Service as GameService;
$vars       = get_defined_vars();
$medias     = $vars['medias'];
$pager      = $vars['pager'];
$mark       = $vars['markInfo']['active'];

$particleViewUtilPath = implode(DIRECTORY_SEPARATOR, array(dirname(__FILE__), 'Util'));

/* Genereate the item template map. */
$indexItemBasePath = $particleViewUtilPath.DIRECTORY_SEPARATOR.'Item';
$itemMap = array(
    GameService::MARK_UNMARKED    => $indexItemBasePath.DIRECTORY_SEPARATOR.'Unmarked.php',
    GameService::MARK_INTERESTED  => $indexItemBasePath.DIRECTORY_SEPARATOR.'Interested.php',
    GameService::MARK_PLAYING     => $indexItemBasePath.DIRECTORY_SEPARATOR.'Playing.php',
    GameService::MARK_PLAYED      => $indexItemBasePath.DIRECTORY_SEPARATOR.'Played.php',
    GameService::MARK_IGNORED     => $indexItemBasePath.DIRECTORY_SEPARATOR.'Ignored.php',
);
?>
<div class="panel panel-default">
    <?php require dirname(__FILE__).DIRECTORY_SEPARATOR.'Util'.DIRECTORY_SEPARATOR.'ListHeader.php'; ?>
    <!-- Movies -->
    <div class="panel-body">
        <div class="clearfix" style="width: 900px; margin-left:auto; margin-right:auto">
            <?php foreach ( $medias as $index => $media ) : ?>
                <?php require $itemMap[$mark]; ?>
            <?php endforeach; ?>
            <?php unset($index); ?>
            <?php unset($media); ?>
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
