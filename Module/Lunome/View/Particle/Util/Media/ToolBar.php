<?php 
$vars = get_defined_vars();
$mediaType = $vars['mediaType'];
?>
<div class="media-index-tool-bar">
    <div class="media-index-tool-bar-item">
        <a href="/?module=lunome&action=<?php echo $mediaType;?>/top"><strong>排行榜</strong></a>
    </div>
    <div class="media-index-tool-bar-item" id="goto-top">
        <a href="#">
            <img src="http://lunome.kupoy.com/Assets/image/goup.png" width="50">
        </a>
    </div>
</div>