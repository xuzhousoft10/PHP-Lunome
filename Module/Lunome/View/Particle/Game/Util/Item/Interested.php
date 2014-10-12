<?php 
use X\Module\Lunome\Service\Game\Service as GameService;
$vars = get_defined_vars();
$media = $vars['media'];
$unmarkUrl = '/?module=lunome&action=game/unmark&mark=%s&id=%s';
$markUrl = '/?module=lunome&action=game/mark&mark=%s&id=%s';
?>
<div class="pull-left" style="text-align: center;line-height: 3em;width:200px;margin: 0px 10px;">
    <div class="movie-item" style="background-image:url('/?module=lunome&action=game/poster&id=<?php echo $media['id'];?>'); background-size: 200px 300px; height: 300px; width:200px;" >
        <div class="btn-group btn-group-justified" style="position: relative;top: 270px; display:none">
            <div class="btn-group btn-group-sm">
                <a class="btn btn-success" href="<?php printf($unmarkUrl, GameService::MARK_INTERESTED, $media['id']);?>">不想玩了</a>
            </div>
            <div class="btn-group btn-group-sm">
                <a class="btn btn-primary" href="<?php printf($markUrl, GameService::MARK_PLAYING, $media['id']);?>">在玩</a>
            </div>
            <div class="btn-group btn-group-sm">
                <a class="btn btn-info" href="<?php printf($markUrl, GameService::MARK_PLAYED, $media['id']);?>">已玩</a>
            </div>
      </div>
    </div>
    <div style="white-space: nowrap;">
        <strong><?php echo $media['name'];?></strong>
    </div>
</div>
<?php unset($tv); ?>
<?php unset($markUrl);?>
<?php unset($unmarkUrl);?>
<?php extract($vars, EXTR_OVERWRITE);?>