<?php
/* @var $this \X\Service\XView\Core\Handler\Html */
$vars = get_defined_vars();
?>
<div>
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>时间</th>
                <th>IP</th>
                <th>登录方式</th>
                <th>国家</th>
                <th>省份</th>
                <th>城市</th>
                <th>服务商</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ( $vars['history'] as $index => $history ):?>
                <tr>
                    <td><?php echo $history['time'];?></td>
                    <td><?php echo $history['ip'];?></td>
                    <td><?php echo $history['logined_by'];?></td>
                    <td><?php echo $history['country'];?></td>
                    <td><?php echo $history['province'];?></td>
                    <td><?php echo $history['city'];?></td>
                    <td><?php echo $history['isp'];?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <br>
    <a class="btn btn-primary" href="index.php?module=backend&action=account/index">返回</a>
</div>