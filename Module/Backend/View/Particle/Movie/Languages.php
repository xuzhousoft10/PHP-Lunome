<?php 
$vars = get_defined_vars();
$languages = $vars['languages'];
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
        <?php foreach ( $languages as $languag ) : ?>
        <tr>
            <td><?php echo $languag->name;?></td>
            <td><?php echo $languag->count;?></td>
            <td>
                <a class="btn btn-xs" href="/?module=backend&action=movie/language/edit&id=<?php echo $languag->id; ?>">编辑</a>
                <a class="btn btn-xs" href="/?module=backend&action=movie/language/operate&id=<?php echo $languag->id; ?>">操作</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>