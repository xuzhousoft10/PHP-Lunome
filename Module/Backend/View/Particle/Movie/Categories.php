<?php 
$vars = get_defined_vars();
$categories = $vars['categories'];
?>
<table class="table table-striped table-bordered table-hover table-condensed">
    <thead>
        <tr>
            <th>名称</th>
            <th>已标记电影计数</th>
            <th>请求被邀请消息模板</th>
            <th>推荐消息模板</th>
            <th>分享消息模板</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ( $categories as $category ) : ?>
        <tr>
            <td><?php echo $category->name;?></td>
            <td><?php echo $category->count;?></td>
            <td><?php echo $category->beg_message;?></td>
            <td><?php echo $category->recommend_message;?></td>
            <td><?php echo $category->share_message;?></td>
            <td>
                <a class="btn btn-xs" href="/?module=backend&action=movie/category/edit&id=<?php echo $category->id; ?>">编辑</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>