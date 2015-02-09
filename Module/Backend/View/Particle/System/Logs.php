<?php 
$vars = get_defined_vars();
$logs = $vars['logs'];
$columns = $vars['columns'];
?>
<table class="table table-striped table-bordered table-hover table-condensed">
    <thead>
        <tr>
        <?php foreach ( $columns as $key => $name ) : ?>
            <th><?php echo $name; ?></th>
        <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
    <?php foreach ( $logs as $log ) : ?>
        <tr>
            <?php foreach ( $columns as $key => $name ) : ?>
            <td><?php echo $log[$key]; ?></td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>