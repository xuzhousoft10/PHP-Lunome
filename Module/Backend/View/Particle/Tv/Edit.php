<?php
$vars = get_defined_vars();
$media = $vars['media'];
$actionUrl = '/index.php?module=backend&action=tv/edit';
if ( null !== $media['id'] ) {
    $actionUrl .= ('&id='.$media['id']);
}
?>
<div>
    <form action="<?php echo $actionUrl;?>" method="post">
        <div class="form-group">
            <label>标题</label>
            <input type="text" class="form-control" name="media[name]" value="<?php echo $media['name'];?>">
        </div>
        
        <button name="save" value="save" type="submit" class="btn btn-default">保存</button>
        <a href="/index.php?module=backend&action=tv/index" class="btn btn-default">返回</a>
    </form>
</div>