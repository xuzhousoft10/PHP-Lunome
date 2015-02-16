<?php 
$vars = get_defined_vars();
$people = $vars['people'];
?>
<table class="table table-striped table-bordered table-hover table-condensed">
    <thead><tr><td>名称</td><td></td></tr></thead>
    <tbody>
    <?php foreach ( $people as $peopleObj ) : ?>
        <tr>
            <td><?php echo $peopleObj->name; ?></td>
            <td>
                <a class="btn btn-xs" href="/?module=backend&action=people/edit&id=<?php echo $peopleObj->id;?>">编辑</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>