<?php
$vars = get_defined_vars();
$media = $vars['media'];
$actionUrl = '/index.php?module=backend&action=tv/edit';
if ( isset($media['id']) && null !== $media['id'] ) {
    $actionUrl .= ('&id='.$media['id']);
}
?>
<div class="col-sm-10">
    <form action="<?php echo $actionUrl;?>" method="post" class="form-horizontal">
        <div class="form-group">
            <label class="col-sm-2 control-label">标题</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="media[name]" value="<?php echo isset($media['name']) ? $media['name'] : '';?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">集数</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="media[episode_count]" value="<?php echo isset($media['episode_count']) ? $media['episode_count'] : '';?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">每集长度</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="media[episode_length]" value="<?php echo isset($media['episode_length']) ? $media['episode_length'] : '';?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">季数</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="media[season_count]" value="<?php echo isset($media['season_count']) ? $media['season_count'] : '';?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">首播</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="media[premiered_at]" value="<?php echo isset($media['premiered_at']) ? $media['premiered_at'] : '';?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">区域</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="media[region]" value="<?php echo isset($media['region']) ? $media['region'] : '';?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">语言</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="media[language]" value="<?php echo isset($media['language']) ? $media['language'] : '';?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">类型</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="media[category]" value="<?php echo isset($media['category']) ? $media['category'] : '';?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">导演</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="media[director]" value="<?php echo isset($media['director']) ? $media['director'] : '';?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">编剧</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="media[writer]" value="<?php echo isset($media['writer']) ? $media['writer'] : '';?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">制片人</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="media[producer]" value="<?php echo isset($media['producer']) ? $media['producer'] : '';?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">监制</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="media[executive]" value="<?php echo isset($media['executive']) ? $media['executive'] : '';?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">主演</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="media[actor]" value="<?php echo isset($media['actor']) ? $media['actor'] : '';?>">
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
                <a href="/index.php?module=backend&action=tv/index" class="btn btn-default">返回</a>
            </div>
        </div>
    </form>
</div>