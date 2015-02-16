<?php 
$vars = get_defined_vars();
$regions = $vars['regions'];
$parent = $vars['parent'];
?>
<?php if (!empty($parent)) : ?>
<h4>
    <?php echo $parent->name; ?> 
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <small><a href="/?module=backend&action=region/index&parent=<?php echo $parent->parent; ?>">返回上级</a></small>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <small><a href="/?module=backend&action=region/edit&parent=<?php echo $parent->id; ?>">添加下级</a></small>
</h4>
<?php endif; ?>
<table class="table table-striped table-bordered table-hover table-condensed">
    <thead><tr><td>名称</td><td></td></tr></thead>
    <tbody>
    <?php foreach ( $regions as $region ) : ?>
        <tr>
            <td><?php echo $region->name; ?></td>
            <td>
                <a class="btn btn-xs" href="/?module=backend&action=region/index&parent=<?php echo $region->id;?>">下级</a>
                <a class="btn btn-xs" href="/?module=backend&action=region/edit&id=<?php echo $region->id;?>">编辑</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>