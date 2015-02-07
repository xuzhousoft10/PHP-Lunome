<?php 
$vars = get_defined_vars();
$movie = $vars['movie'];
$characters = $vars['characters'];
?>
<h4>
    《<?php echo $movie['name'];?>》的角色列表
    &nbsp;&nbsp;&nbsp;
    <small><a href="/?module=backend&action=movie/detail&id=<?php echo $movie['id'];?>">返回</a></small>
</h4>
<table class="table table-striped table-bordered table-hover table-condensed">
    <thead>
        <tr>
            <th>图片</th>
            <th>名称</th>
            <th>描述</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ( $characters as $character ) : ?>
        <tr>
            <td><img class="img-thumbnail padding-0" width="80" height="80" alt="无图片信息" src="<?php echo $character['image'];?>"></td>
            <td><?php echo $character['name'];?></td>
            <td><?php echo $character['description'];?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>