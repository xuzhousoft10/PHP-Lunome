<?php
$vars = get_defined_vars();
$movie = $vars['movie'];
$regions = $vars['regions'];
$languages = $vars['languages'];
?>
<div class="row">
    <div class="col-md-6">
        <form enctype ="multipart/form-data" class="form-horizontal" method="post" action="/?module=backend&action=movie/edit<?php if(!empty($movie['id'])): ?>&id=<?php echo $movie['id']; ?><?php endif;?>">
            <div class="form-group">
                <label class="col-sm-2 control-label">名称</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="movie[name]" value="<?php echo $movie['name'];?>">
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-2 control-label">长度</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="movie[length]" value="<?php echo $movie['length'];?>">
                </div>
                <label class="control-label">秒</label>
            </div>
            
            <div class="form-group">
                <label class="col-sm-2 control-label">日期</label>
                <div class="col-sm-10">
                    <input data-provide="datepicker" data-date-format="yyyy-mm-dd" type="text" class="form-control" name="movie[date]" value="<?php echo $movie['date'];?>">
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-2 control-label">地区</label>
                <div class="col-sm-10">
                    <select class="form-control" name="movie[region_id]">
                        <?php $optionStatus = empty($movie['region_id']) ? 'selected' : ''; ?>
                        <option value="" <?php echo $optionStatus; ?>></option>
                        <?php foreach ( $regions as $region ) : ?>
                            <?php $optionStatus = ($movie['region_id']===$region->id) ? 'selected' : ''; ?>
                            <option value="<?php echo $region->id; ?>" <?php echo $optionStatus; ?>><?php echo $region->name;?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-2 control-label">语言</label>
                <div class="col-sm-10">
                    <select class="form-control" name="movie[language_id]">
                        <?php $optionStatus = empty($movie['languge_id']) ? 'selected' : ''; ?>
                        <option value="" <?php echo $optionStatus; ?>></option>
                        <?php foreach ( $languages as $language ) : ?>
                            <?php $optionStatus = ($movie['language_id']===$language->id) ? 'selected' : ''; ?>
                            <option value="<?php echo $language->id; ?>" <?php echo $optionStatus; ?>><?php echo $language->name;?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-2 control-label">简介</label>
                <div class="col-sm-10">
                    <textarea class="form-control" name="movie[introduction]"><?php echo $movie['introduction'];?></textarea>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-2 control-label">封面</label>
                <div class="col-sm-10">
                    <input type="file" class="form-control" name="cover">
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 clearfix">
                    <div class="col-md-6 padding-0">
                        <?php if (empty($movie['id'])): ?>
                            <a class="btn btn-default btn-block" href="/?module=backend&action=movie/index">返回</a>
                        <?php else : ?>
                            <a class="btn btn-default btn-block" href="/?module=backend&action=movie/detail&id=<?php echo $movie['id'];?>">返回</a>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6 padding-0">
                        <button type="submit" class="btn btn-default btn-block">保存</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-6 text-center">
        <img src="<?php echo $movie['cover']; ?>" class="img-thumbnail padding-0">
        <br><br>
        <?php if ( 1 === (int)$movie['has_cover'] ): ?>
            <a href="/?module=backend&action=movie/deleteCover&movie=<?php echo $movie['id']; ?>" class="btn">删除封面</a>
        <?php endif; ?>
    </div>
</div>