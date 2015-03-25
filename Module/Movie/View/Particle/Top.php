<?php 
$vars = get_defined_vars();
$list = $vars['list'];
$length = $vars['length'];
?>
<div>
    <ol class="breadcrumb">
        <li>
            <a href="/?module=lunome&action=movie/index">电影</a>
        </li>
        <li class="active">排行榜</li>
    </ol>
</div>
<div class="row">
    <?php foreach ( $list as $item ):?>
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading"><?php echo $item['label'];?> (TOP <?php echo $length;?>)</div>
            <div class="panel-body padding-0">
                <table class="table table-striped table-bordered table-hover table-condensed margin-bottom-0">
                    <tbody>
                        <?php foreach ( $item['list'] as $media ) : ?>
                            <tr>
                                <td>
                                    <a href="/?module=movie&action=detail&id=<?php echo $media['id'];?>">
                                        <?php echo $media['name'];?>
                                    </a>
                                </td>
                                <td><?php echo $media['mark_count']?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endforeach;?>
</div>