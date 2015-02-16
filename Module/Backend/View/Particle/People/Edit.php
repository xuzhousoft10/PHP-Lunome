<?php
$vars = get_defined_vars();
$people = $vars['people'];
?>
<div class="row">
    <div class="col-md-6">
        <form   class="form-horizontal" 
                method="post" 
                action="/?module=backend&action=people/edit<?php if(!empty($people['id'])): ?>&id=<?php echo $people['id']; ?><?php endif;?>"
        >
            <div class="form-group">
                <label class="col-sm-2 control-label">名称</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="people[name]" value="<?php echo $people['name'];?>">
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 clearfix">
                    <div class="col-md-6 padding-0">
                        <a class="btn btn-default btn-block" href="/?module=backend&action=people/index">返回</a>
                    </div>
                    <div class="col-md-6 padding-0">
                        <button type="submit" class="btn btn-default btn-block">保存</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>