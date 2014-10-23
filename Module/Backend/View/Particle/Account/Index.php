<?php 
/* @var $this X\Service\XView\Core\Handler\Html */
$vars = get_defined_vars();
?>
<table class="table table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th>帐号</th>
            <th>昵称</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ( $vars['accounts'] as $account ) :?>
        <tr>
            <td><?php echo $account['account'];?></td>
            <td><?php echo $account['nickname'];?></td>
            <td><?php echo $account['status'];?></td>
            <td></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>