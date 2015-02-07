<?php 
$vars = get_defined_vars();
$movie = $vars['movie'];
$actors = $vars['actors'];
$categories = $vars['categories'];
$directors = $vars['directors'];
$unselectedCategories = $vars['unselectedCategories'];
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
        <div class="clearfix">
            <div class="col-md-2 padding-0">
                <a class="btn" href="/?module=backend&action=movie/edit&id=<?php echo $movie['id'];?>">编辑</a>
            </div>
            <div class="col-md-10 padding-0 text-right">
                <a class="btn" href="/?module=backend&action=movie/character/index&id=<?php echo $movie['id'];?>">角色</a>
                <a class="btn" href="/?module=backend&action=movie/dialogue/index&id=<?php echo $movie['id'];?>">经典台词</a>
                <a class="btn" href="/?module=backend&action=movie/poster/index&id=<?php echo $movie['id'];?>">海报</a>
                <a class="btn" href="/?module=backend&action=movie/comment/index&id=<?php echo $movie['id'];?>">短评</a>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <img src="<?php echo $movie['cover'];?>" class="img-thumbnail padding-0">
    </div>
</div>
<br>

类型
<hr class="margin-top-5 margin-bottom-5">
<div>
    <?php foreach ( $categories as $category ) : ?>
    <div class="btn-group">
        <button type="button" class="btn btn-default"><?php echo $category->name;?></button>
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li class="divider"></li>
            <li><a href="/?module=backend&action=movie/category/remove&movie=<?php echo $movie['id'];?>&category=<?php echo $category->id; ?>">删除</a></li>
         </ul>
    </div>
    <?php endforeach; ?>
    <br><br>
    <form class="form-inline" method="post" action="/?module=backend&action=movie/category/add&movie=<?php echo $movie['id'];?>">
        <div class="form-group">
            <select name="category" class="form-control">
            <?php foreach ( $unselectedCategories as $unselectedCategory ) : ?>
                <option value="<?php echo $unselectedCategory->id; ?>"><?php echo $unselectedCategory->name; ?></option>
            <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-default">增加</button>
    </form>
</div>
<br>

导演
<hr class="margin-top-5 margin-bottom-5">
<div>
    <?php foreach ( $directors as $director ) : ?>
    <div class="btn-group">
        <button type="button" class="btn btn-default"><?php echo $director->name;?></button>
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li class="divider"></li>
            <li><a href="/?module=backend&action=movie/director/remove&movie=<?php echo $movie['id'];?>&director=<?php echo $director->id; ?>">删除</a></li>
         </ul>
    </div>
    <?php endforeach; ?>
    <br><br>
    <form class="form-inline" method="post" action="/?module=backend&action=movie/director/add&movie=<?php echo $movie['id'];?>">
        <div class="form-group">
            <input type="text" class="form-control" name="name">
        </div>
        <button type="submit" class="btn btn-default">增加</button>
    </form>
</div>
<br>

演员
<hr class="margin-top-5 margin-bottom-5">
<div>
    <?php foreach ( $actors as $actor ) : ?>
    <div class="btn-group">
        <button type="button" class="btn btn-default"><?php echo $actor->name;?></button>
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li class="divider"></li>
            <li><a href="/?module=backend&action=movie/actor/remove&movie=<?php echo $movie['id'];?>&actor=<?php echo $actor->id; ?>">删除</a></li>
         </ul>
    </div>
    <?php endforeach; ?>
    <br><br>
    <form class="form-inline" method="post" action="/?module=backend&action=movie/actor/add&movie=<?php echo $movie['id'];?>">
        <div class="form-group">
            <input type="text" class="form-control" name="name">
        </div>
        <button type="submit" class="btn btn-default">增加</button>
    </form>
</div>
<br>