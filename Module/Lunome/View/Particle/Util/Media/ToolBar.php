<?php 
$vars = get_defined_vars();
$mediaType = $vars['mediaType'];
?>
<div class="media-index-tool-bar">
    <div class="media-index-tool-bar-item">
        <a href="/?module=lunome&action=<?php echo $mediaType;?>/top"><strong>排行榜</strong></a>
    </div>
</div>