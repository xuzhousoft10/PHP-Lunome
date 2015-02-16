<?php
$vars = get_defined_vars();
$region = $vars['region'];
?>
<div class="clearfix">
    <div class="col-md-6 padding-0">
        <form class="form-horizontal" action="/?module=backend&action=movie/region/edit<?php if(!empty($region['id'])): ?>&id=<?php echo $region['id'];?><?php endif; ?>" method="post">
            <div class="form-group">
                <label class="col-sm-2 control-label">名称</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="region[name]" value="<?php echo $region['name']; ?>">
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">保存</button>
                </div>
            </div>
        </form>
    </div>
</div>