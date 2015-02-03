<?php use X\Module\Lunome\Service\Movie\Service as MovieService; ?>
<?php 
$vars = get_defined_vars();
$assetsURL = $vars['assetsURL']; 
$marks = $vars['marks']; 
$accountID = $vars['accountID'];
$movies = $vars['movies']; 
?>
<div class="btn-group btn-group-justified">
    <?php foreach ( $marks['data'] as $key => $value ) : ?>
        <?php $buttonStatus = ($key*1 === $marks['actived']) ? 'btn-primary' : '';?>
        <a  href="/?module=lunome&action=movie/home/index&id=<?php echo $accountID; ?>&mark=<?php echo $key;?>" 
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
        <?php printf('<a href="/?module=lunome&action=movie/detail&id=%s" target="_blank">', $movie['id']); ?>
        <div class="pull-left lnm-media-list-item-container">
            <div data-cover-url="<?php echo $movie['cover']; ?>" class="lnm-media-list-item thumbnail padding-0 margin-bottom-0" >
                <div class="lnm-media-list-item-intro-area">
                    <?php echo $movie['introduction']; ?>
                </div>
            </div>
            <div class="white-space-nowrap lnm-media-list-item-container-name">
                <?php if (MovieService::MARK_WATCHED === $marks['actived']) : ?>
                    <div class="movie-item-rate-container" data-score="<?php echo $movie['score']; ?>"></div>
                    <br>
                <?php endif; ?>
                <strong><?php echo $movie['name']; ?></strong>
            </div>
        </div>
        <?php echo '</a>'; ?>
        <?php endforeach; ?>
    </div>
    
    <?php $pager = $vars['pager']; ?>
    <?php if ( false !== $pager['prev'] || false !== $pager['next'] ) : ?>
    <div>
        <nav>
            <ul class="pager">
                <li class="previous<?php echo (false === $pager['prev']) ? ' disabled' : ''; ?>">
                    <?php if (false !== $pager['prev']) :?>
                        <a  href="/?module=lunome&action=movie/home/index&id=<?php echo $accountID; ?>&mark=<?php echo $marks['actived']; ?>&page=<?php echo $pager['prev'];?>"
                        >&larr; 上一页</a>
                    <?php endif; ?>
                </li>
                <li><?php echo $pager['current'];?> / <?php echo $pager['total'];?></li>
                <li class="next<?php echo (false === $pager['next']) ? ' disabled' : ''; ?>">
                    <?php if (false !== $pager['next']) :?>
                        <a  href="/?module=lunome&action=movie/home/index&id=<?php echo $accountID; ?>&mark=<?php echo $marks['actived']; ?>&page=<?php echo $pager['next'];?>"
                            class="movie-characters-container-pager"
                        >下一页&rarr;</a>
                    <?php endif; ?>
                </li>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
<?php endif; ?>