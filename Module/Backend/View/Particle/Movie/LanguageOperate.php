<?php 
$vars = get_defined_vars();
$language = $vars['language'];
$allLanguages = $vars['allLangeuages'];
?>
<h4><?php echo $language->name; ?><small>(<?php echo $language->count;?>被标记)</small> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small><a href="/?module=backend&action=movie/language/index">返回</a></small></h4>
<hr>
<div class="clearfix">
    <div class="col-md-4 well">
        移动该语言下的电影到其他语言：<br>
        <form class="form-inline" action="/?module=backend&action=movie/language/move" method="post">
            <div class="form-group">
                <input type="hidden" name="from" value="<?php echo $language->id?>" >
                <select class="form-control" name="to">
                <?php foreach ( $allLanguages as $otherLanguage ) : ?>
                    <?php if ( $otherLanguage->id === $language->id ) : ?>
                        <?php continue; ?>
                    <?php endif; ?>
                    <option value="<?php echo $otherLanguage->id; ?>"><?php echo $otherLanguage->name; ?></option>
                <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" class="btn btn-default">转移</button>
        </form>
    </div>
    
    <div class="col-md-4 well margin-left-15">
        删除分类：<br>
        <a class="btn" href="/?module=backend&action=movie/language/delete&id=<?php echo $language->id?>">删除</a>
    </div>
</div>