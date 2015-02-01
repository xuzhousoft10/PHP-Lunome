<?php
$vars = get_defined_vars();
$movies = $vars['movies'];
$marks = $vars['marks'];
$isWatched = $vars['isWatched'];
$sign = $vars['sign'];
?>
<?php foreach ( $movies as $movie ) : ?>
    <div class="pull-left lnm-media-list-item-container">
        <div class="lnm-media-list-item <?php echo $sign;?>" data-cover-url="<?php echo $movie['cover']; ?>">
            <div class="lnm-media-list-item-intro-area" data-detail-url="/?module=lunome&action=movie/detail&id=<?php echo $movie['id'];?>">
                <?php echo $movie['introduction'];?>
            </div>
            
            <div class="btn-group btn-group-justified lnm-media-list-item-mark-container">
                <?php if ($isWatched) : ?>
                    <div style="background-color:#FFFFFF">
                        <div    class="rate-it-container-<?php echo $sign;?>" 
                                id="rate-it-container-<?php echo $movie['id']; ?>"
                                data-score="<?php echo $movie['score'];?>"
                                data-media-id="<?php echo $movie['id'];?>"
                        ></div>
                    </div>
                <?php else : ?>
                    <?php foreach ( $marks as $actionMarkCode => $actionMarkDesc ) :?>
                        <a  class="btn btn-<?php echo $actionMarkDesc['style']; ?> btn-mark-action-<?php echo $sign;?>"
                            data-media-id = "<?php echo $movie['id'];?>"
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
            <a href="/?module=lunome&action=movie/detail&id=<?php echo $movie['id'];?>" target="_blank">
                <strong><?php echo $movie['name']; ?></strong>
            </a>
        </div>
    </div>
<?php endforeach; ?>