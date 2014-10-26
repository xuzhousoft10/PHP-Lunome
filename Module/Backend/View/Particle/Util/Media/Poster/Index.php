<?php 
$vars = get_defined_vars();
$mediaId = $vars['mediaId'];
$hasPoster = $vars['hasPoster'];
$type = $vars['type'];
?>
<div>
    <div>
        <h4>高:300 宽:200</h4>
        <?php if ($hasPoster) : ?>
            <a href="/index.php?module=backend&action=<?php echo $type;?>/poster/delete&id=<?php echo $mediaId?>" class="btn btn-primary btn-xs">移除</a><br>
            <img src="/index.php?module=backend&action=<?php echo $type;?>/poster/download&id=<?php echo $mediaId?>">
        <?php else :?>
            <form class="form-inline" action="/index.php?module=backend&action=<?php echo $type;?>/poster/add" method="post" enctype ="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $mediaId; ?>" >
                <div class="form-group">
                    <input type="file" class="form-control" style="padding: 0px" name="poster">
                </div>
                <button name="save" value="save" type="submit" class="btn btn-default">上传</button>
            </form>
        <?php endif;?>
    </div>
    <br/>
    <a href="/index.php?module=backend&action=<?php echo $type;?>/index" class="btn btn-primary">返回</a>
</div>