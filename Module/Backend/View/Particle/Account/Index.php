<?php 
$vars = get_defined_vars();
$accounts = $vars['accounts'];
$statusNames = $vars['statusNames'];
$condition = $vars['condition'];
?>
<div class="well well-sm">
    <form class="form-inline" action="/" method="get">
        <input type="hidden" name="module" value="backend">
        <input type="hidden" name="action" value="account/index">
        <?php if (isset($condition['role']) ) :?>
            <input type="hidden" name="condition[role]" value="<?php echo $condition['role'];?>">
        <?php endif; ?>
        <div class="form-group">
            <label>帐号</label>
            <input 
                type="text" 
                class="form-control" 
                placeholder="帐号查询" 
                name="condition[account]"
                value="<?php echo isset($condition['account']) ? $condition['account'] : '';?>"
            >
        </div>
        <div class="form-group">
            <label>状态</label>
            <select class="form-control" name="condition[status]">
                <?php $optionStatus = (!isset($condition['status']) || empty($condition['status'])) ? 'selected' : ''; ?>
                <option value="" <?php echo $optionStatus; ?>></option>
                <?php foreach ( $statusNames as $statusCode => $statusName ) : ?>
                    <?php $optionStatus = (isset($condition['status']) && ($statusCode === (int)$condition['status'])) ? 'selected' : ''; ?>
                    <option value="<?php echo $statusCode;?>" <?php echo $optionStatus; ?>><?php echo $statusName; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-default">
            <span class="glyphicon glyphicon-search"></span>
        </button>
    </form>
</div>
<table class="table table-striped table-bordered table-hover table-condensed">
    <thead>
        <tr>
            <th>帐号</th>
            <th>状态</th>
            <th>启用时间</th>
            <th>角色</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ( $accounts as $account ) : ?>
            <tr>
                <td><?php echo $account['account']; ?></td>
                <td>
                    <div class="clearfix">
                        <?php echo $account['status']; ?>
                    </div>
                </td>
                <td><?php echo $account['enabled_at']; ?></td>
                <td>
                    <span class="label label-primary">访问</span>
                    <?php foreach ( $account['role'] as $accountRole ) : ?>
                        <?php if ($accountRole['hasRole']): ?>
                            <span class="label label-primary"><?php echo $accountRole['name'];?></span>
                        <?php endif; ?>
                    <?php endforeach;?>
                </td>
                <td>
                    <a class="btn btn-xs" href="/?module=backend&action=account/detail&account=<?php echo $account['id'];?>">查看详细</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>