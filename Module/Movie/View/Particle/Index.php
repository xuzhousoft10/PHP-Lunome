<?php 
/* @var $this \X\Service\XView\Core\Util\HtmlView\ParticleView */

use X\Module\Movie\Service\Movie\Core\Instance\Movie;
$vars = get_defined_vars();
$query = $vars['query'];
$marks = $vars['marks'];
$dataURL = $vars['dataURL'];
$isDebug = $vars['isDebug'];
$pageSize = $vars['pageSize'];
$searchData = $vars['searchData'];
$currentMark = $vars['currentMark'];
$searchMaxLength = $vars['searchMaxLength'];
$maxAutoLoadTimeCount = $vars['maxAutoLoadTimeCount'];
$mediaItemWaitingImage = $vars['mediaItemWaitingImage'];
$mediaLoaderLoaddingImage = $vars['mediaLoaderLoaddingImage'];
$scriptManager = $this->getManager()->getHost()->getScriptManager();
$linkManager = $this->getManager()->getHost()->getLinkManager();

$scriptManager->add('cookie')->setSource('library/jquery/plugin/cookie.js')->setRequirements('jquery');
$scriptManager->add('movie-index')->setSource('js/movie/index.js')->setRequirements('jquery', 'bootstrap', 'cookie');
if ( Movie::MARK_WATCHED === $currentMark ) {
    $linkManager->addCSS('rate-it', 'library/jquery/plugin/rate/rateit.css');
    $scriptManager->add('rate-it')->setSource('library/jquery/plugin/rate/rateit.js')->setRequirements('jquery');
}
?>
<div class="panel panel-default">
    <!-- Media Index Header Start -->
    <div class="panel-heading padding-0">
        <nav class="navbar navbar-default navbar-static-top navbar navbar-inverse margin-bottom-0">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="/?module=movie&action=index">电影</a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                    <?php foreach ( $marks as $markCode => $mark ) :?>
                        <li class="<?php if ($mark['isActive']) :?>active<?php endif; ?>">
                            <a href="/?module=movie&action=index&mark=<?php echo $markCode; ?>">
                                <?php echo $mark['name'];?>
                                (<span id="mark-counter-<?php echo $markCode; ?>"><?php echo $mark['count']; ?></span>)
                            </a>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                    <form class="navbar-form navbar-right">
                        <div class="form-group">
                            <input  id="media-name-search-text" 
                                    type="text" 
                                    class="form-control" 
                                    placeholder="查询名称，导演，演员"
                                    maxlength="<?php echo $searchMaxLength;?>"
                            >
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
            data-init-query     = "<?php echo $query;?>"
            data-url            = "<?php echo $dataURL;?>"
            data-detail-url     = "/?module=movie&action=detail&id={id}"
            data-mark-url       = "/?module=movie&action=mark&mark={mark}&id={id}"
            data-total          = "<?php echo $marks[$currentMark]['count'];?>"
            data-container      = ".lnm-media-list-container"
            data-pagesize       = "<?php echo $pageSize; ?>"
            data-current-mark   = "<?php echo $currentMark; ?>"
            data-waiting-image  = "<?php echo $mediaItemWaitingImage; ?>"
            data-loading-image  = "<?php echo $mediaLoaderLoaddingImage;?>"
            data-is-debug       = "<?php echo $isDebug; ?>"
            data-prev-result-btn= "<?php echo htmlspecialchars('<div class="alert alert-info pull-left text-center" style="width:100%;cursor:pointer">显示之前的结果</div>');?>"
            data-load-more-btn  = "<?php echo htmlspecialchars('<div class="alert alert-info pull-left text-center" style="width:100%;cursor:pointer">显示更多</div>'); ?>"
            data-watched-mark   = "<?php echo Movie::MARK_WATCHED;?>"
            data-max-auto-load-count="<?php echo $maxAutoLoadTimeCount; ?>"
        >
        <div>
        <?php require dirname(__FILE__).DIRECTORY_SEPARATOR.'Search.php'; ?>
        <hr/>
        </div>
        <div class="clearfix lnm-media-list-container"></div>
    </div>
    <!-- Media List End -->
</div>