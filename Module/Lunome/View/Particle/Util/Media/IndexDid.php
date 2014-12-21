<?php 
/* @var $this X\Service\XView\Core\Handler\Html */
use X\Core\X;
$assetsURL = X::system()->getConfiguration()->get('assets-base-url');
$this->addScriptFile('media-index', $assetsURL.'/js/media_index_did.js');
$this->addScriptFile('rate-it', $assetsURL.'/library/jquery/plugin/rate/rateit.js');
$this->addCssLink('rate-it', $assetsURL.'/library/jquery/plugin/rate/rateit.css');

$vars = get_defined_vars();
$marks = $vars['marks'];
$markActions = $vars['markActions'];
$pager = $vars['pager'];
$mediaType = $vars['mediaType'];
$mediaTypeName = $vars['mediaTypeName'];
$currentMark = $vars['currentMark'];
$mediaItemWaitingImage = $vars['mediaItemWaitingImage'];
?>
<div class="panel panel-default">
    <!-- Media Index Header Start -->
    <div class="panel-heading padding-0">
        <nav class="navbar navbar-default navbar-static-top navbar navbar-inverse margin-bottom-0">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="/?module=lunome&action=<?php echo $mediaType;?>/index"><?php echo $mediaTypeName; ?></a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                    <?php foreach ( $marks as $markCode => $mark ) :?>
                        <li class="<?php if ($mark['isActive']) :?>active<?php endif; ?>">
                            <a href="<?php printf('/?module=lunome&action=%s/index&mark=%s', $mediaType,$markCode); ?>">
                                <?php echo $mark['name'];?> (<span id="mark-counter-<?php echo $markCode; ?>"><?php echo $mark['count']; ?></span>)
                            </a>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                    <form class="navbar-form navbar-right">
                        <div class="form-group">
                            <input id="media-name-search-text" type="text" class="form-control" placeholder="Search">
                        </div>
                        <button id="media-name-search-button" type="button" class="btn btn-default">
                            <span class="glyphicon glyphicon-search"></span>
                        </button>
                    </form>
                </div>
            </div>
        </nav>
    </div>
    <!-- Media Index Header End -->
    
    <!-- Media List Start -->
    <div class="panel-body">
        <input 
            id                  = "media-index-parameters" 
            type                = "hidden" 
            data-url            = "<?php printf('/?module=lunome&action=%s/find&mark=%s', $mediaType, $currentMark); ?>"
            data-detail-url     = "<?php printf('/?module=lunome&action=%s/detail&id={id}', $mediaType); ?>"
            data-mark-url       = "<?php printf('/?module=lunome&action=%s/mark&mark={mark}&id={id}', $mediaType); ?>"
            data-total          = "<?php echo $marks[$currentMark]['count'];?>"
            data-container      = ".lnm-media-list-container"
            data-pagesize       = "20"
            data-marks          = "<?php echo htmlspecialchars(json_encode($markActions)); ?>"
            data-current-mark   = "<?php echo $currentMark; ?>"
            data-waiting-image  = "<?php echo $mediaItemWaitingImage; ?>"
            data-loading-image  = "<?php echo $vars['mediaLoaderLoaddingImage'];?>"
            data-is-debug       = "<?php echo X::system()->getConfiguration()->get('isDebug') ? 'true' : 'false'; ?>"
        >
        <div>
        <?php require dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR.'Movie'.DIRECTORY_SEPARATOR.'Search.php'; ?>
        <hr/>
        </div>
        <div class="clearfix lnm-media-list-container"></div>
    </div>
    <!-- Media List End -->
</div>