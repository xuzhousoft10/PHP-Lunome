<?php
$vars = get_defined_vars();
$language = $vars['language'];
?>
<div class="clearfix">
    <div class="col-md-6 padding-0">
        <form class="form-horizontal" action="/?module=backend&action=movie/language/edit<?php if(!empty($language['id'])): ?>&id=<?php echo $language['id'];?><?php endif; ?>" method="post">
            <div class="form-group">
                <label class="col-sm-2 control-label">名称</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="language[name]" value="<?php echo $language['name']; ?>">
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