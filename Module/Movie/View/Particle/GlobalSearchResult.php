<?php 
$vars = get_defined_vars();
$assetsURL = $vars['assetsURL'];
$movies = $vars['movies']; 
?>
<?php if ( empty($movies) ) : ?>
    <p class="margin-top-100">找不到相关视频~~~</p>
<?php else :?>
    <?php foreach ( $movies as $movie ) : ?>
        <<?php echo 'a'?>  
            href="#" 
            class="global-search-result-item"
            data-name="<?php echo $movie['name']; ?>"
            data-link="<?php echo $movie['link'];?>"
            data-source="<?php echo $movie['source'];?>"
        >
            <div class="margin-5 thumbnail pull-left">
                <div class="lunome-height-150">
                    <img class="lunome-image-100-150" alt="<?php echo $movie['name']; ?>" src="<?php echo $movie['thumb'];?>">
                </div>
                <div>
                    <img src="<?php echo $assetsURL; ?>/image/<?php echo $movie['source']; ?>.png" >
                </div>
                <div>
                    <span><?php echo $movie['name']; ?></span>
                </div>
            </div>
        </<?php echo 'a';?>>
    <?php endforeach; ?>
<?php endif; ?>