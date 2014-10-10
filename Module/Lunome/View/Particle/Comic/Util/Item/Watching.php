<?php 
use X\Module\Lunome\Service\Comic\Service as ComicService;
$vars   = get_defined_vars();
$comic  = $vars['comic'];
$url    = '/?module=lunome&action=comic/mark&mark=%s&id=%s';
?>
<div class="pull-left" style="text-align: center;line-height: 3em;width:200px;margin: 0px 10px;">
    <div class="movie-item" style="background-image:url('/?module=lunome&action=comic/poster&id=<?php echo $comic['id'];?>'); background-size: 200px 300px; height: 300px; width:200px;" >
        <div class="btn-group btn-group-justified" style="position: relative;top: 270px; display:none">
            <div class="btn-group btn-group-sm">
                <a class="btn btn-success" href="<?php printf($url, ComicService::MARK_WATCHED, $comic['id']);?>">看完了</a>
            </div>
            <div class="btn-group btn-group-sm">
                <a class="btn btn-default" href="<?php printf($url, ComicService::MARK_IGNORED, $comic['id']);?>">不喜欢</a>
            </div>
        </div>
    </div>
    <div style="white-space: nowrap;">
        <strong><?php echo $comic['name'];?></strong>
    </div>
</div>
<?php unset($comic); ?>
<?php unset($url); ?>
<?php extract($vars, EXTR_OVERWRITE);?>