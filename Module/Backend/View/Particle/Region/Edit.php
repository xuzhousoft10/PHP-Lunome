<?php
$vars = get_defined_vars();
$region = $vars['region'];
$parent = $vars['parent'];
?>
<div class="row">
    <div class="col-md-6">
        <form   class="form-horizontal" 
                method="post" 
                action="/?module=backend&action=region/edit<?php if(!empty($region['id'])): ?>&id=<?php echo $region['id']; ?><?php endif;?>"
        >
            <input type="hidden" name="region[level]" value="<?php echo $region['level'];?>">
            <div class="form-group">
                <label class="col-sm-2 control-label">上级</label>
                <div class="col-sm-10">
                    <?php if (empty($parent)) : ?>
                        <input type="hidden" name="parent" value="">
                        <input type="hidden" name="region[parent]" value="">
                        <input type="text" class="form-control" value="" disabled>
                    <?php else : ?>
                        <input type="hidden" name="parent" value="<?php echo $parent->id; ?>">
                        <input type="hidden" name="region[parent]" value="<?php echo $parent->id; ?>">
                        <input type="text" class="form-control" value="<?php echo $parent->name;?>" disabled>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-2 control-label">名称</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="region[name]" value="<?php echo $region['name'];?>">
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 clearfix">
                    <div class="col-md-6 padding-0">
                        <a class="btn btn-default btn-block" href="/?module=backend&action=region/index<?php if (!empty($parent)): ?>&parent=<?php echo $parent->id;?><?php endif; ?>">返回</a>
                    </div>
                    <div class="col-md-6 padding-0">
                        <button type="submit" class="btn btn-default btn-block">保存</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>