<?php 
$vars = get_defined_vars();
$mediaType = $vars['mediaType'];
?>
<div class="media-index-tool-bar">
    <div class="media-index-tool-bar-item">
        <a href="/?module=lunome&action=<?php echo $mediaType;?>/top" title="标记排行榜"><strong>排行榜</strong></a>
    </div>
    <div class="media-index-tool-bar-item" id="goto-top">
        <a href="#" title="返回顶端">
            <img src="http://lunome-assets.qiniudn.com/image/goup.png" width="50">
        </a>
    </div>
    <div class="media-index-tool-bar-item" id="goto-top">
        <a href="/?module=lunome&action=movie/edit" id="toolbar-add-new" title="添加新电影信息">
            <img src="http://lunome-assets.qiniudn.com/image/add.png" width="50">
        </a>
    </div>
</div>