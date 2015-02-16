<?php
$vars = get_defined_vars();
$movie = $vars['movie'];
$markActions = $vars['markActions'];
?>
<div style="text-align:center">
    <img id="movie-cover" alt="<?php echo $movie['name']; ?>" src="<?php echo $movie['cover'];?>">
    
    <div style="text-align:center">
        <?php foreach ( $markActions as $markCode => $markName ):?>
            <a  href="/?module=smartphone&action=movie/mark&mark=<?php echo $markCode ?>" 
                class="ui-btn ui-btn-inline"
            ><?php echo $markName; ?></a>
        <?php endforeach; ?>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#movie-cover').click(function(){
        location.reload(true);
    });
});
</script>