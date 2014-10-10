<?php 
use X\Module\Lunome\Service\Comic\Service as ComicService;
$vars       = get_defined_vars();
$comic      = $vars['comic'];
$unmarkUrl  = '/?module=lunome&action=comic/unmark&mark=%s&id=%s';
$markUrl    = '/?module=lunome&action=comic/mark&mark=%s&id=%s';
?>
<div class="pull-left" style="text-align: center;line-height: 3em;width:200px;margin: 0px 10px;">
    <div class="movie-item" style="background-image:url('/?module=lunome&action=comic/poster&id=<?php echo $comic['id'];?>'); background-size: 200px 300px; height: 300px; width:200px;" >
        <div class="btn-group btn-group-justified" style="position: relative;top: 270px; display:none">
            <div class="btn-group btn-group-sm">
                <a class="btn btn-success" href="<?php printf($unmarkUrl, ComicService::MARK_INTERESTED, $comic['id']);?>">不想看了</a>
            </div>
            <div class="btn-group btn-group-sm">
                <a class="btn btn-primary" href="<?php printf($markUrl, ComicService::MARK_WATCHING, $comic['id']);?>">在看</a>
            </div>
            <div class="btn-group btn-group-sm">
                <a class="btn btn-info" href="<?php printf($markUrl, ComicService::MARK_WATCHED, $comic['id']);?>">已看</a>
            </div>
      </div>
    </div>
    <div style="white-space: nowrap;">
        <strong><?php echo $comic['name'];?></strong>
    </div>
</div>
<?php unset($comic); ?>
<?php unset($markUrl);?>
<?php unset($unmarkUrl);?>
<?php extract($vars, EXTR_OVERWRITE);?>