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
        <tr>
            <td>长度</td>
            <td><?php echo $media['length'];?></td>
        </tr>
        <tr>
            <td>年代</td>
            <td><?php echo $media['year'];?></td>
        </tr>
        <tr>
            <td>地区</td>
            <td><?php echo $media['region'];?></td>
        </tr>
        <tr>
            <td>分类</td>
            <td><?php echo $media['category'];?></td>
        </tr>
        <tr>
            <td>语言</td>
            <td><?php echo $media['language'];?></td>
        </tr>
        <tr>
            <td>导演</td>
            <td><?php echo $media['director'];?></td>
        </tr>
        <tr>
            <td>编剧</td>
            <td><?php echo $media['writer'];?></td>
        </tr>
        <tr>
            <td>制片人</td>
            <td><?php echo $media['producer'];?></td>
        </tr>
        <tr>
            <td>监制</td>
            <td><?php echo $media['executive'];?></td>
        </tr>
        <tr>
            <td>简介</td>
            <td><?php echo $media['actor'];?></td>
        </tr>
        <tr>
            <td>标题</td>
            <td><?php echo $media['introduction'];?></td>
        </tr>
    </table>
    <br>
    <a href="/index.php?module=backend&action=<?php echo $type;?>/edit&id=<?php echo $media['id'];?>" class="btn btn-primary">编辑</a>
    <a href="/index.php?module=backend&action=<?php echo $type;?>/index" class="btn btn-primary">返回</a>
</div>