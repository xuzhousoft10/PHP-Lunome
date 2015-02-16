<?php 
$vars = get_defined_vars();
$category = $vars['category'];
$allCategories = $vars['allCategories'];
?>
<h4><?php echo $category->name; ?><small>(<?php echo $category->count;?>被标记)</small> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small><a href="/?module=backend&action=movie/category/index">返回</a></small></h4>
<hr>
<div class="clearfix">
    <div class="col-md-4 well">
        移动该分类下的电影到其他分类：<br>
        <form class="form-inline" action="/?module=backend&action=movie/category/move" method="post">
            <div class="form-group">
                <input type="hidden" name="from" value="<?php echo $category->id?>" >
                <select class="form-control" name="to">
                <?php foreach ( $allCategories as $otherCategory ) : ?>
                    <?php if ( $otherCategory->id === $category->id ) : ?>
                        <?php continue; ?>
                    <?php endif; ?>
                    <option value="<?php echo $otherCategory->id; ?>"><?php echo $otherCategory->name; ?></option>
                <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" class="btn btn-default">转移</button>
        </form>
    </div>
    
    <div class="col-md-4 well margin-left-15">
        删除分类：<br>
        <a class="btn" href="/?module=backend&action=movie/category/delete&id=<?php echo $category->id?>">删除</a>
    </div>
</div>