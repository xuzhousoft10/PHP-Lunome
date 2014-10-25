<?php
$vars = get_defined_vars();
$medias = $vars['medias'];
?>
<div>
    <table class="table table-striped table-hover table-condensed">
        <thead><tr><th>标题</th><th>海报</th><th>操作</th></tr></thead>
        <tbody>
        <?php foreach ( $medias as $media ) :?>
            <tr>
                <td><?php echo $media['name']; ?></td>
                <td>
                    <a href="/index.php?module=backend&action=movie/poster/index&id=<?php echo $media['id'];?>">查看</a>
                </td>
                <td>
                    <a href="/index.php?module=backend&action=movie/edit&id=<?php echo $media['id'];?>">编辑</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <hr/>
    <div>
        <a href="/index.php?module=backend&action=movie/edit" class="btn btn-primary">添加</a>
    </div>
</div>