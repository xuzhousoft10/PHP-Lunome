<?php 
$vars = get_defined_vars();
$regions = $vars['regions'];
?>
<table class="table table-striped table-bordered table-hover table-condensed">
    <thead>
        <tr>
            <th>名称</th>
            <th>电影计数</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ( $regions as $region ) : ?>
        <tr>
            <td><?php echo $region->name;?></td>
            <td><?php echo $region->count;?></td>
            <td>
                <a class="btn btn-xs" href="/?module=backend&action=movie/region/edit&id=<?php echo $region->id; ?>">编辑</a>
                <a class="btn btn-xs" href="/?module=backend&action=movie/region/operate&id=<?php echo $region->id; ?>">操作</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>