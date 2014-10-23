<?php 
/* @var $this X\Service\XView\Core\Handler\Html */
?>
<nav class="navbar navbar-default navbar-fixed-top navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">Lunome Backend</a>
        </div>
        
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        </div>
    </div>
</nav>
<div class="container-fluid" style="padding-top: 70px">
    <div class="row">
        <div class="col-sm-2">
            <div class="list-group">
                <a href="#" class="list-group-item active">会员帐号管理</a>
                <a href="#" class="list-group-item">电影资源管理</a>
                <a href="#" class="list-group-item">电视资源管理</a>
                <a href="#" class="list-group-item">动漫资源管理</a>
                <a href="#" class="list-group-item">书籍资源管理</a>
                <a href="#" class="list-group-item">游戏资源管理</a>
            </div>
        </div>
        <div class="col-sm-10">
            <?php foreach ( $this->particles as $particle ) :?>
                <?php echo $particle['content'];?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<nav class="navbar navbar-default navbar-fixed-bottom navbar-inverse">
    <div class="container-fluid text-center" style="color: white">
        Lunome Management System
    </div>
</nav>