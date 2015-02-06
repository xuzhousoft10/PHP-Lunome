<?php 
$vars = get_defined_vars();
$account = $vars['account'];
$accountConfigurations = $vars['accountConfigurations'];
?>
<h4>帐号信息</h4>
<table class="table table-striped table-bordered table-hover table-condensed">
    <tr><td>帐号</td><td><?php echo $account['account'];?></td></tr>
    <tr><td>第三方帐号注册</td><td><?php echo empty($account['oauth20_id']) ? '否' : '是';?></td></tr>
    <tr>
        <td>状态</td>
        <td>
            <?php echo $account['status']['name'];?>
            <?php if (!empty($account['status']['actions'])) :?>
            | 
            <?php endif; ?>
            <?php foreach ( $account['status']['actions'] as $statusActionCode => $statusActionName ) : ?>
                <a class="btn btn-warning btn-xs" href="/?module=backend&action=account/updateStatus&account=<?php echo $account['id']?>&status=<?php echo $statusActionCode; ?>"
                ><?php echo $statusActionName; ?></a>
            <?php endforeach; ?>
        </td>
    </tr>
    <tr><td>启用日期</td><td><?php echo $account['enabled_at']; ?></td></tr>
    <tr>
        <td>角色</td>
        <td>
            <?php foreach ( $account['role'] as $roleCode => $roleItem ) : ?>
                <?php if ($roleItem['hasRole']): ?>
                    <a class="btn btn-success btn-xs" href="/?module=backend&action=account/updateRole&account=<?php echo $account['id']?>&role=<?php echo $roleCode; ?>&do=remove"
                    ><?php echo $roleItem['name'];?></a>
                <?php else : ?>
                    <a class="btn btn-primary btn-xs" href="/?module=backend&action=account/updateRole&account=<?php echo $account['id']?>&role=<?php echo $roleCode; ?>&do=add"
                    ><?php echo $roleItem['name'];?></a>
                <?php endif; ?>
            <?php endforeach; ?>
        </td>
    </tr>
</table>

<h4>配置信息</h4>
<table class="table table-striped table-bordered table-hover table-condensed">
    <thead>
        <tr>
            <th>类型</th>
            <th>名称</th>
            <th>值</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ( $accountConfigurations as $accountConfiguration ) : ?>
            <tr>
                <td><?php echo $accountConfiguration->type; ?></td>
                <td><?php echo $accountConfiguration->name; ?></td>
                <td><?php echo $accountConfiguration->value; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>