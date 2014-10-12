<?php 
use X\Module\Lunome\Service\Book\Service as BookService;
$vars = get_defined_vars();
$media = $vars['media'];
$url = '/?module=lunome&action=book/mark&mark=%s&id=%s';
?>
<div class="pull-left" style="text-align: center;line-height: 3em;width:200px;margin: 0px 10px;">
    <div    class="movie-item" 
            style="background-image:url('/?module=lunome&action=book/poster&id=<?php echo $media['id'];?>'); background-size: 200px 300px; height: 300px; width:200px;" >
        <div class="btn-group btn-group-justified" style="position: relative;top: 270px; display:none">
            <div class="btn-group btn-group-sm">
                <a class="btn btn-success" href="<?php printf($url, BookService::MARK_INTERESTED, $media['id']); ?>">想读</a>
            </div>
            <div class="btn-group btn-group-sm">
                <a class="btn btn-primary" href="<?php printf($url, BookService::MARK_READING, $media['id']); ?>">在读</a>
            </div>
            <div class="btn-group btn-group-sm">
                <a class="btn btn btn-info" href="<?php printf($url, BookService::MARK_READ, $media['id']); ?>">已读</a>
            </div>
            <div class="btn-group btn-group-sm">
                <a class="btn btn-default" href="<?php printf($url, BookService::MARK_IGNORED, $media['id']); ?>">不喜欢</a>
            </div>
      </div>
    </div>
    <div style="white-space: nowrap;">
        <strong><?php echo $media['name'];?></strong>
    </div>
</div>
<?php unset($media); ?>
<?php unset($url); ?>
<?php extract($vars, EXTR_OVERWRITE);?>