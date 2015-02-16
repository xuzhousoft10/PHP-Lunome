<?php 
$vars = get_defined_vars();
$movie = $vars['movie'];
$posters = $vars['posters'];
?>
<h4>
    《<?php echo $movie['name'];?>》的宣传海报列表
    &nbsp;&nbsp;&nbsp;
    <small><a href="/?module=backend&action=movie/detail&id=<?php echo $movie['id'];?>">返回</a></small>
</h4>
<div class="clearfix">
    <?php foreach ( $posters as $poster ) : ?>
    <div class="pull-left">
        <img class="img-thumbnail padding-0 margin-5" width="200" src="<?php echo $poster['image'];?>">
    </div>
    <?php endforeach; ?>
</div>