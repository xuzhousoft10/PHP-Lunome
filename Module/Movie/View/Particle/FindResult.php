<?php
$vars = get_defined_vars();
$movies = $vars['movies'];
$marks = $vars['marks'];
$isWatched = $vars['isWatched'];
$movieAccont = $vars['movieAccount'];
$sign = $vars['sign'];
?>
<?php foreach ( $movies as $movie ) : ?>
<?php /* @var $movie \X\Module\Movie\Service\Movie\Core\Instance\Movie */ ?>
    <div class="pull-left lnm-media-list-item-container">
        <div class="lnm-media-list-item <?php echo $sign;?>" data-cover-url="<?php echo $movie->getCoverURL(); ?>">
            <div    class="lnm-media-list-item-intro-area" 
                    data-detail-url="/?module=movie&action=detail&id=<?php echo $movie->get('id');?>">
                <?php echo $movie->get('introduction');?>
            </div>
            
            <div class="btn-group btn-group-justified lnm-media-list-item-mark-container">
                <?php if ($isWatched) : ?>
                    <div style="background-color:#FFFFFF">
                        <div    class="rate-it-container-<?php echo $sign;?>" 
                                id="rate-it-container-<?php echo $movie->get('id'); ?>"
                                data-score="<?php echo $movieAccont->getScore($movie->get('id'));?>"
                                data-media-id="<?php echo $movie->get('id');?>"
                        ></div>
                    </div>
                <?php else : ?>
                    <?php foreach ( $marks as $actionMarkCode => $actionMarkDesc ) :?>
                        <a  class="btn btn-<?php echo $actionMarkDesc['style']; ?> btn-mark-action-<?php echo $sign;?>"
                            data-media-id = "<?php echo $movie->get('id');?>"
                            data-mark-code = "<?php echo $actionMarkCode; ?>"
                            href="#"
                        >
                            <?php echo $actionMarkDesc['name']; ?>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="white-space-nowrap">
            <a href="/?module=movie&action=detail&id=<?php echo $movie->get('id');?>" target="_blank">
                <strong><?php echo $movie->get('name'); ?></strong>
            </a>
        </div>
    </div>
<?php endforeach; ?>