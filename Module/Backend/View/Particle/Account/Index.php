<?php 
use X\Module\Lunome\Model\AccountModel;
/* @var $this X\Service\XView\Core\Handler\Html */
$vars = get_defined_vars();
$currentUser = $vars['currentUser'];
$statusLabels = array(
    AccountModel::ST_NOT_USED   => '未使用',
    AccountModel::ST_IN_USE     => '使用中',
    AccountModel::ST_FREEZE     => '冻结中',
);
?>
<div>
    <ul class="nav nav-tabs nav-justified">
        <li class="active"><a href="/index.php?module=backend&action=account/index">所有帐号</a></li>
        <li><a href="/index.php?module=backend&action=account/admin/index">管理员帐号</a></li>
    </ul>
    
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>帐号</th>
                <th>昵称</th>
                <th>Open Id</th>
                <th>状态</th>
                <th>启用期</th>
                <th>登录历史</th>
                <th>是否为管理员</th>
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
                        <?php if ($currentUser['ID'] === $account['id']): ?>
                            <a href="#" class="btn btn-primary btn-xs disabled">冻结</a>
                        <?php else : ?>
                            <a href="/index.php?module=backend&action=account/freeze&id=<?php echo $account['id']; ?>" class="btn btn-primary btn-xs">冻结</a>
                        <?php endif; ?>
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
                <td>
                    <?php if ( AccountModel::ST_NOT_USED != $account['status']) :?>
                        <?php if (AccountModel::IS_ADMIN_YES == $account['is_admin']): ?>
                            是
                            <?php if ( $currentUser['ID'] === $account['id'] ): ?>
                                <a class="btn btn-default btn-xs disabled" href="#">取消</a>
                            <?php else:?>
                                <a class="btn btn-default btn-xs" href="/index.php?module=backend&action=account/admin/delete&id=<?php echo $account['id'];?>">取消</a>
                            <?php endif;?>
                        <?php else : ?>
                            否
                            <a class="btn btn-primary btn-xs" href="/index.php?module=backend&action=account/admin/add&id=<?php echo $account['id'];?>">设置</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>