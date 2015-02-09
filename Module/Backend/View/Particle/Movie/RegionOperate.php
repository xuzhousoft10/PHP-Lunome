<?php 
$vars = get_defined_vars();
$region = $vars['region'];
$allRegions = $vars['allRegions'];
?>
<h4><?php echo $region->name; ?><small>(<?php echo $region->count;?>被标记)</small> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small><a href="/?module=backend&action=movie/region/index">返回</a></small></h4>
<hr>
<div class="clearfix">
    <div class="col-md-4 well">
        移动该区域下的电影到其他区域：<br>
        <form class="form-inline" action="/?module=backend&action=movie/region/move" method="post">
            <div class="form-group">
                <input type="hidden" name="from" value="<?php echo $region->id?>" >
                <select class="form-control" name="to">
                <?php foreach ( $allRegions as $otherRegion ) : ?>
                    <?php if ( $otherRegion->id === $region->id ) : ?>
                        <?php continue; ?>
                    <?php endif; ?>
                    <option value="<?php echo $otherRegion->id; ?>"><?php echo $otherRegion->name; ?></option>
                <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" class="btn btn-default">转移</button>
        </form>
    </div>
    
    <div class="col-md-4 well margin-left-15">
        删除分类：<br>
        <a class="btn" href="/?module=backend&action=movie/region/delete&id=<?php echo $region->id?>">删除</a>
    </div>
</div>