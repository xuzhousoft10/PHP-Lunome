<?php use X\Module\Movie\Service\Movie\Core\Instance\Movie; ?>
<?php 
$vars = get_defined_vars();
$assetsURL = $vars['assetsURL']; 
$marks = $vars['marks']; 
$accountID = $vars['accountID'];
/* @var $movie \X\Module\Movie\Service\Movie\Core\Instance\Movie */
$movies = $vars['movies']; 
$movieAccount = $vars['movieAccount'];

/* @var $this \X\Service\XView\Core\Util\HtmlView\ParticleView */
$this->getManager()->getHost()->getScriptManager()->add('movie-home')->setSource('js/movie/home.js');
if (Movie::MARK_WATCHED === $vars['currentMark'] ) {
    $this->getManager()->getHost()->getLinkManager()->addCSS('rate-it', 'library/jquery/plugin/rate/rateit.css');
    $this->getManager()->getHost()->getScriptManager()->add('rate-it')->setSource('library/jquery/plugin/rate/rateit.js');
}
?>
<div class="btn-group btn-group-justified">
    <?php foreach ( $marks['data'] as $key => $value ) : ?>
        <?php $buttonStatus = ($key*1 === $marks['actived']) ? 'btn-primary' : '';?>
        <a  href="/?module=movie&action=home/index&id=<?php echo $accountID; ?>&mark=<?php echo $key;?>" 
            class="btn btn-default <?php echo $buttonStatus; ?>"
        ><?php echo $value; ?></a>
    <?php endforeach; ?>
</div>
<?php if (empty($movies)) : ?>
    <div class="clearfix">
        <div class="pull-left">
            <img src="<?php echo $assetsURL;?>/image/nothing.gif" width="100" height="100">
        </div>
        <div class="margin-top-70 text-muted">
            <small>Ta~ 还没有标记成<?php echo $marks['data'][$marks['actived']];?>的电影~~~</small>
        </div>
    </div>
<?php else: ?>
    <br>
    <div class="clearfix padding-left-35">
        <?php foreach ( $movies as $index => $movie ) :?>
        <?php printf('<a href="/?module=movie&action=detail&id=%s" target="_blank">', $movie->get('id')); ?>
        <div class="pull-left lnm-media-list-item-container">
            <div data-cover-url="<?php echo $movie->getCoverURL(); ?>" class="lnm-media-list-item thumbnail padding-0 margin-bottom-0" >
                <div class="lnm-media-list-item-intro-area">
                    <?php echo $movie->get('introduction'); ?>
                </div>
            </div>
            <div class="white-space-nowrap lnm-media-list-item-container-name">
                <?php if (Movie::MARK_WATCHED === $marks['actived']) : ?>
                    <div class="movie-item-rate-container" data-score="<?php echo $movieAccount->getScore($movie->get('id')); ?>"></div>
                    <br>
                <?php endif; ?>
                <strong><?php echo $movie->get('name'); ?></strong>
            </div>
        </div>
        <?php echo '</a>'; ?>
        <?php endforeach; ?>
    </div>
    
    <?php $vars['pager']->show(); ?>
<?php endif; ?>