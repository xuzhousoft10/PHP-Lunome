<?php
$vars = get_defined_vars();
$media = $vars['media'];
$type = $vars['type'];
?>
<div>
    <table class="table table-striped table-hover">
        <tr>
            <td>标题</td>
            <td><?php echo $media['name'];?></td>
        </tr>
    </table>
    <br>
    <a href="/index.php?module=backend&action=<?php echo $type;?>/edit&id=<?php echo $media['id'];?>" class="btn btn-primary">编辑</a>
    <a href="/index.php?module=backend&action=<?php echo $type;?>/index" class="btn btn-primary">返回</a>
</div>