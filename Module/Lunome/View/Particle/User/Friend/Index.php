<?php 
/* @var $this \X\Service\XView\Core\Handler\Html */
$vars = get_defined_vars();
$friends = $vars['friends'];
?>
<div>
    <nav class="navbar navbar-default navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">我的好友</a>
            </div>
        </div>
    </nav>
    
    <ul class="list-group">
        <?php foreach ( $friends as $index => $friend ) : ?>
            <li class="list-group-item friend-list-item" style="cursor:pointer">
                <div class="row">
                    <div class="pull-left" style="padding-left:5px;padding-right:5px;">
                        <img class="img-thumbnail" src="<?php echo $friend['photo'];?>" style="height:50px;"/>
                    </div>
                    <div class="pull-left">
                        <div>
                            <strong><?php echo $friend['nickname']; ?></strong>
                        </div>
                        <div style="padding-top:5px">
                        电影128 | 电视4894 | 动漫549 | 图书467 | 游戏48
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
    <script type="text/javascript">
    $('.friend-list-item').on('mouseenter', function() {
        $(this).addClass('active');
    });
    $('.friend-list-item').on('mouseleave', function() {
        $(this).removeClass('active');
    });
    </script>
    

    <div class="text-right">
        <ul class="pagination">
            <li><a href="#">首页</a></li>
            <li><a href="#">上一页</a></li>
            <li><a href="#">1</a></li>
            <li><a href="#">2</a></li>
            <li><a href="#">3</a></li>
            <li><a href="#">4</a></li>
            <li><a href="#">5</a></li>
            <li><a href="#">下一页</a></li>
            <li><a href="#">尾页</a></li>
        </ul>
    </div>
</div>