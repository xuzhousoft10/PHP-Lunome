<?php 
$vars = get_defined_vars();
$config = $vars['configurations'];
?>
<form action="/?module=lunome&action=user/setting/sns" method="post">
<div class="col-md-9 thumbnail">
    <div class="row">
        <div class="col-md-9"><span class="lead"><small>同步信息到其他社交平台：</small></span></div>
        <div class="col-md-3 text-right">
            <input name="config[auto_share]" type="hidden" value="0">
            <?php $autoShareStatus = ('1'==$config['auto_share']) ? 'checked' : ''; ?>
            <input name="config[auto_share]" id="sns-auto-share" type="checkbox" value="1" <?php echo $autoShareStatus; ?>>
        </div>
    </div>
    <hr class="margin-top-0 margin-bottom-10">
    <div>
        <small class="text-muted">
        当开启信息同步时， 小伙伴会第一时间了解到你在本站进行的标记行为，运气好的话， 说不定那些任性的土豪小伙伴会请你看场电影哦！
        </small>
    </div>
    <hr class="margin-top-0 margin-bottom-10">
    <button type="submit" class="btn btn-primary">保存</button>
</div>
</form>