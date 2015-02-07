<?php 
$vars = get_defined_vars();
$movie = $vars['movie'];
$comments = $vars['comments'];
?>
<h4>
    《<?php echo $movie['name'];?>》的经典台词列表
    &nbsp;&nbsp;&nbsp;
    <small><a href="/?module=backend&action=movie/detail&id=<?php echo $movie['id'];?>">返回</a></small>
</h4>
<table class="table table-striped table-bordered table-hover table-condensed">
    <thead>
        <tr>
            <th>时间</th>
            <th>作者</th>
            <th>内容</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ( $comments as $comment ) : ?>
        <tr>
            <td><?php echo $comment['commented_at'];?></td>
            <td><?php echo $comment['commented_by'];?></td>
            <td><?php echo $comment['content'];?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>