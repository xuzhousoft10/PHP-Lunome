<?php
$vars = get_defined_vars();
$media = $vars['media'];
$actionUrl = '/index.php?module=backend&action=movie/edit';
if ( isset($media['id']) && null !== $media['id'] ) {
    $actionUrl .= ('&id='.$media['id']);
}
?>
<div><div class="col-sm-10">
    <form action="<?php echo $actionUrl;?>" method="post" class="form-horizontal">
        <div class="form-group">
            <label class="col-sm-2 control-label">标题</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="media[name]" value="<?php echo isset($media['name']) ? $media['name'] : '';?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">长度</label>
            <div class="col-sm-2">
                <input type="text" class="form-control" name="media[length]" value="<?php echo isset($media['length']) ? $media['length'] : '';?>" placeholder="分钟">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">年代</label>
            <div class="col-sm-2">
                <input type="text" class="form-control" name="media[year]" value="<?php echo isset($media['year']) ? $media['year'] : '';?>" placeholder="年份">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">地区</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="media[region]" value="<?php echo isset($media['region']) ? $media['region'] : '';?>"  placeholder="多个区域以空格分割">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">分类</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="media[category]" value="<?php echo isset($media['category']) ? $media['category'] : '';?>" placeholder="多个分类以空格分割">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">语言</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="media[language]" value="<?php echo isset($media['language']) ? $media['language'] : '';?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">导演</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="media[director]" value="<?php echo isset($media['director']) ? $media['director'] : '';?>" placeholder="多个导演以空格分割">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">编剧</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="media[writer]" value="<?php echo isset($media['writer']) ? $media['writer'] : '';?>" placeholder="多个编剧以空格分割">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">制片人</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="media[producer]" value="<?php echo isset($media['producer']) ? $media['producer'] : '';?>" placeholder="多个制片人以空格分割">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">监制</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="media[executive]" value="<?php echo isset($media['executive']) ? $media['executive'] : '';?>" placeholder="多个监制以空格分割">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">演员</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="media[actor]" value="<?php echo isset($media['actor']) ? $media['actor'] : '';?>" placeholder="多个演员以空格分割">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">简介</label>
            <div class="col-sm-10">
                <textarea class="form-control" name="media[introduction]"><?php echo isset($media['introduction']) ? $media['introduction'] : '';?></textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button name="save" value="save" type="submit" class="btn btn-default">保存</button>
                <a href="/index.php?module=backend&action=movie/index" class="btn btn-default">返回</a>
            </div>
        </div>
    </form>
</div></div>