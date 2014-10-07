<?php 
$vars = get_defined_vars();
$movie = $vars['movie'];
?>
<div class="pull-left" style="text-align: center;line-height: 3em;width:200px;margin: 0px 10px;">
    <div class="movie-item" style="background-image:url('/?module=lunome&action=movie/poster&id=<?php echo $movie['id'];?>'); background-size: 200px 300px; height: 300px; width:200px;" >
        
    </div>
    <div style="white-space: nowrap;">
        <strong><?php echo $movie['name'];?></strong>
    </div>
</div>