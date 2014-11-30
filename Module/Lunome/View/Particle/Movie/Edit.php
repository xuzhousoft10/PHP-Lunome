<?php 
use X\Util\ArrayDataContainer;

$this->addCssLink('bootstrap-datepicker', '/Assets/library/bootstrap/plugin/bootstrap-datepicker/css/datepicker3.css');
$this->addScriptFile('bootstrap-datepicker', '/Assets/library/bootstrap/plugin/bootstrap-datepicker/js/bootstrap-datepicker.js');
$this->addScriptFile('bootstrap-datepicker-locale', '/Assets/library/bootstrap/plugin/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js');
$this->addScriptFile('movie-editor', '/Assets/js/movie_editor.js');

$data = get_defined_vars();
$movie = new ArrayDataContainer($data['movie']);
$actionUrl = '/?module=lunome&action=movie/edit';
$actionUrl .= (null == $movie->id) ? '' : '&id='.$movie->id;
?>
<form action="<?php echo $actionUrl; ?>" method="post" enctype ="multipart/form-data">
    <h4>编辑电影信息</h4>
    <hr>
    <div class="row">
         <div class="col-md-9">
            <div class="form-group">
                <label>名称</label>
                <input  name="movie[name]" 
                        type="text" 
                        class="form-control" 
                        placeholder="电影名字不可以为空呦～～～" 
                        value="<?php $movie->show('name'); ?>"
                >
            </div>
            
            <div class="clearfix">
                <div class="col-md-6 padding-left-0">
                    <div class="form-group">
                        <label>首映时间</label>
                        <input id="movie-data" name="movie[date]" type="text" class="form-control" value="<?php echo $movie->show('date');?>">
                    </div>
                </div>
                <div class="col-md-6 padding-right-0">
                    <div class="form-group">
                        <label>时长</label>
                        <div class="row">
                            <div class="col-xs-5">
                                <input id="movie-length" name="movie[length]" type="text" class="form-control" value="<?php echo $movie->show('length');?>">
                            </div>
                            <div class="col-xs-3 padding-0 line-height-2">
                                <small>分钟</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label>简介</label>
                <textarea name="movie[introduction]" class="form-control"><?php $movie->show('introduction');?></textarea>
            </div>
            
            <div class="form-group">
                <label>分类</label>
                <div class="thumbnail clearfix">
                    <?php for ( $i=0; $i<17; $i++ ) : ?>
                        <div class="col-xs-2">
                            <label>
                                <?php $checkStatus = (false !== array_search($data['categories'][$i]->id, $data['movieCategories'])) ? 'checked' : ''; ?>
                                <input type="checkbox" name="categories[]" value="<?php echo $data['categories'][$i]->id; ?>" <?php echo $checkStatus; ?>>
                                <small><?php echo $data['categories'][$i]->name;?></small>
                            </label>
                        </div>
                    <?php endfor; ?>
                    <?php $categoryCount = count($data['categories']);?>
                    <?php if (17 < $categoryCount) : ?>
                        <div class="col-xs-2">
                            <a href="#" id="more-categories-container-trigger">更多</a>
                        </div>
                        <div class="col-xs-12">
                            <hr class="margin-5">
                        </div>
                        <div class="clearfix" id="more-categories-container">
                            <?php for ( $i=17; $i<$categoryCount; $i++ ) : ?>
                                <div class="col-xs-2">
                                    <label>
                                        <?php $checkStatus = (false !== array_search($data['categories'][$i]->id, $data['movieCategories'])) ? 'checked' : ''; ?>
                                        <input type="checkbox" name="categories[]" value="<?php echo $data['categories'][$i]->id; ?>" <?php echo $checkStatus; ?>>
                                        <small><?php echo $data['categories'][$i]->name;?></small>
                                    </label>
                                </div>
                            <?php endfor; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="thumbnail">
                <div class="clearfix">
                    <a href="#" class="pull-right" id="more-information-trigger" data-status="closed">
                        <span class="glyphicon glyphicon-chevron-down"></span>
                        展开
                   </a>
                </div>
                <div id="more-information-container">
                    <hr>
                    <div class="clearfix">
                        <div class="col-md-6 padding-left-0">
                            <div class="form-group">
                                <label>地区</label>
                                <?php $selecte = $movie->region_id; ?>
                                <select name="movie[region_id]" class="form-control">
                                    <?php $optionStatus = empty($selecte) ? 'selected' : ''; ?>
                                    <option value="" <?php echo $optionStatus; ?>>未选择</option>
                                    <?php foreach ( $data['regions'] as $region ) : ?>
                                        <?php $optionStatus = $selecte == $region->id ? 'selected' : '';?>
                                        <option value="<?php echo $region->id;?>" <?php echo $optionStatus;?>><?php echo $region->name;?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 padding-right-0">
                            <div class="form-group">
                                <label>语言</label>
                                <?php $selecte = $movie->language_id; ?>
                                <select name="movie[language_id]" class="form-control">
                                    <?php $optionStatus = empty($selecte) ? 'selected' : ''; ?>
                                    <option value="" <?php echo $optionStatus; ?>>未选择</option>
                                    <?php foreach ( $data['languages'] as $language ) : ?>
                                        <?php $optionStatus = $selecte == $language->id ? 'selected' : '';?>
                                        <option value="<?php echo $language->id;?>" <?php echo $optionStatus; ?>><?php echo $language->name;?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="clearfix">
                        <div class="col-md-6 padding-left-0">
                            <div class="form-group">
                                <label>导演</label>
                                <input name="movie[director]" type="text" class="form-control" value="<?php $movie->show('director');?>">
                            </div>
                        </div>
                        <div class="col-md-6 padding-right-0">
                           <div class="form-group">
                                <label>编剧</label>
                                <input name="movie[writer]" type="text" class="form-control" value="<?php $movie->show('writer');?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="clearfix">
                        <div class="col-md-6 padding-left-0">
                            <div class="form-group">
                                <label>制片人</label>
                                <input name="movie[producer]" type="text" class="form-control" value="<?php $movie->show('producer');?>">
                            </div>
                        </div>
                        <div class="col-md-6 padding-right-0">
                            <div class="form-group">
                                <label>监制</label>
                                <input name="movie[executive]" type="text" class="form-control" value="<?php $movie->show('executive');?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>演员</label>
                        <input name="movie[actor]" type="text" class="form-control" placeholder="使用“，”隔开多个人哦～～～" value="<?php $movie->show('actor');?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 thumbnail text-center" id="movie-cover-select-container">
            <div class="clearfix">
                <small class="pull-left"><strong>封面图片选择</strong></small>
                <small class="pull-right text-muted"><strong>宽：200 &nbsp;&nbsp;高：300</strong></small>
            </div>
            <br>
            <div class="thumbnail">
                <img id="movie-cover-previewer" src="<?php echo $data['coverURL'];?>?rand=<?php echo rand(0, 1000);?>" width="200" height="300">
            </div>
            <button id="movie-cover-file-trigger" type="button" class="btn btn-success">选择电影封面</button>
        </div>
    </div>
    <br>
    <button type="submit" class="btn btn-primary">保存</button>
</form>
