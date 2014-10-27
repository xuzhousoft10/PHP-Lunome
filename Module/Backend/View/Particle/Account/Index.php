<?php 
use X\Module\Lunome\Model\AccountModel;
/* @var $this X\Service\XView\Core\Handler\Html */
$vars = get_defined_vars();
$statusLabels = array(
    AccountModel::ST_NOT_USED   => '未使用',
    AccountModel::ST_IN_USE     => '使用中',
    AccountModel::ST_FREEZE     => '冻结中',
);
?>
<table class="table table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th>帐号</th>
            <th>昵称</th>
            <th>Open Id</th>
            <th>状态</th>
            <th>启用期</th>
            <th>登录历史</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ( $vars['accounts'] as $account ) :?>
        <tr>
            <td><?php echo $account['account'];?></td>
            <td><?php echo $account['nickname'];?></td>
            <td>
                <?php if (!empty($account['oauth20_id'])) :?>
                    <?php $oauthViewURL = sprintf('/index.php?module=backend&action=account/oauth/view&id=%s', $account['oauth20_id']);?>
                    <a href="<?php echo $oauthViewURL; ?>" class="btn btn-default btn-xs" >查看</a>
                <?php endif; ?>
            </td>
            <td>
                <?php echo $statusLabels[$account['status']];?>
                <?php if ( AccountModel::ST_NOT_USED == $account['status'] ) :?>
                <?php elseif ( AccountModel::ST_IN_USE == $account['status'] ) : ?>
                    <a href="/index.php?module=backend&action=account/freeze&id=<?php echo $account['id']; ?>" class="btn btn-primary btn-xs">冻结</a>
                <?php elseif ( AccountModel::ST_FREEZE == $account['status'] ) :?>
                    <a href="/index.php?module=backend&action=account/unfreeze&id=<?php echo $account['id']; ?>" class="btn btn-primary btn-xs">取消</a>
                <?php endif; ?>
            </td>
            <td>
                <?php if ('0000-00-00 00:00:00' !== $account['enabled_at']): ?>
                    <?php echo $account['enabled_at'];?>
                <?php endif;?>
            </td>
            <td>
                <?php if ( AccountModel::ST_NOT_USED != $account['status'] ) :?>
                    <a href="/index.php?module=backend&action=account/loginHistory&id=<?php echo $account['id']; ?>" class="btn btn-primary btn-xs">查看</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>