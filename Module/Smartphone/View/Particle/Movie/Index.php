<?php
$vars = get_defined_vars();
$movie = $vars['movie'];
?>
<div style="text-align:center">
    <img alt="<?php echo $movie['name']; ?>" src="<?php echo $movie['cover'];?>">
    
    <div style="text-align:center">
        <a href="#" class="ui-btn ui-btn-inline">想看</a>
        <a href="#" class="ui-btn ui-btn-inline">已看</a>
        <a href="#" class="ui-btn ui-btn-inline">忽略</a>
    </div>
</div>