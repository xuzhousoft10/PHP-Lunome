<?php
$vars = get_defined_vars();
$category = $vars['category'];
?>
<div class="clearfix">
    <div class="col-md-6 padding-0">
        <form class="form-horizontal" action="/?module=backend&action=movie/category/edit<?php if(!empty($category['id'])): ?>&id=<?php echo $category['id'];?><?php endif; ?>" method="post">
            <div class="form-group">
                <label class="col-sm-2 control-label">名称</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="category[name]" value="<?php echo $category['name']; ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-2 control-label">请求被邀请消息模板</label>
                <div class="col-sm-10">
                    <textarea class="form-control" name="category[beg_message]" ><?php echo $category['beg_message']; ?></textarea>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-2 control-label">推荐消息模板</label>
                <div class="col-sm-10">
                    <textarea class="form-control" name="category[recommend_message]"><?php echo $category['recommend_message']; ?></textarea>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-2 control-label">分享消息模板</label>
                <div class="col-sm-10">
                    <textarea class="form-control" name="category[share_message]"><?php echo $category['share_message']; ?></textarea>
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