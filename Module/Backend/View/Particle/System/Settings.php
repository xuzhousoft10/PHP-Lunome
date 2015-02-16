<?php 
$vars = get_defined_vars();
$configurations = $vars['configurations'];
?>
<form action="/?module=backend&action=system/setting" method="post">
<table class="table table-striped table-bordered table-hover table-condensed">
    <thead><tr><td>名称</td><td>值</td></tr></thead>
    <tbody>
    <?php foreach ( $configurations as $configuration ) : ?>
        <tr>
            <td><?php echo $configuration->name; ?></td>
            <td class="full-width"><input class="full-width" type="text" name="settings[<?php echo $configuration->name; ?>]" value="<?php echo $configuration->value; ?>"></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<hr>
<button class="btn btn-default">保存</button>
</form>