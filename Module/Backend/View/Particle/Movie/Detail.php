<?php 
$vars = get_defined_vars();
$movie = $vars['movie'];
$actors = $vars['actors'];
?>
<div class="clearfix">
    <div class="col-md-10 padding-0">
        <table class="table table-striped table-bordered table-hover">
            <tr><th>名称</th><th><?php echo $movie['name'];?></th></tr>
            <tr><th>长度</th><th><?php echo $movie['length'];?></th></tr>
            <tr><th>日期</th><th><?php echo $movie['date'];?></th></tr>
            <tr><th>区域</th><th><?php echo $movie['region'];?></th></tr>
            <tr><th>语言</th><th><?php echo $movie['language'];?></th></tr>
        </table>
        <br>
        <a class="btn" href="/?module=backend&action=movie/edit&id=<?php echo $movie['id'];?>">编辑</a>
    </div>
    <div class="col-md-2">
        <img src="<?php echo $movie['cover'];?>" class="img-thumbnail padding-0">
    </div>
</div>
<br>
演员
<hr>
<div>
<?php foreach ( $actors as $actor ) : ?>
<div class="btn-group">
    <button type="button" class="btn btn-default"><?php echo $actor->name;?></button>
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
        <li class="divider"></li>
        <li><a href="/?module=backend&action=movie/deleteActor&movie=<?php echo $movie['id'];?>&actor=<?php echo $actor->id; ?>">删除</a></li>
     </ul>
</div>
<?php endforeach; ?>
<br><br>
<form class="form-inline">
    <div class="form-group">
        <input type="text" class="form-control">
    </div>
    <button type="submit" class="btn btn-default">增加</button>
</form>
</div>