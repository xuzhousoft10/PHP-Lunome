<?php
use X\Module\Lunome\Service\Book\Service as BookService;
$vars       = get_defined_vars();
$medias     = $vars['medias'];
$pager      = $vars['pager'];
$mark       = $vars['markInfo']['active'];

$particleViewUtilPath = implode(DIRECTORY_SEPARATOR, array(dirname(__FILE__), 'Util'));

/* Genereate the item template map. */
$indexItemBasePath = $particleViewUtilPath.DIRECTORY_SEPARATOR.'Item';
$itemMap = array(
    BookService::MARK_UNMARKED    => $indexItemBasePath.DIRECTORY_SEPARATOR.'Unmarked.php',
    BookService::MARK_INTERESTED  => $indexItemBasePath.DIRECTORY_SEPARATOR.'Interested.php',
    BookService::MARK_READING     => $indexItemBasePath.DIRECTORY_SEPARATOR.'Reading.php',
    BookService::MARK_READ        => $indexItemBasePath.DIRECTORY_SEPARATOR.'Read.php',
    BookService::MARK_IGNORED     => $indexItemBasePath.DIRECTORY_SEPARATOR.'Ignored.php',
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
