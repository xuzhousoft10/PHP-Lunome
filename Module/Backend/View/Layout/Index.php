<?php 
/* @var $this X\Service\XView\Core\Handler\Html */
$vars = get_defined_vars();
$menu = $vars['menu'];
$activeMenuItem = $vars['activeMenuItem'];
?>
<nav class="navbar navbar-default navbar-fixed-top navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">Lunome后台管理</a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav pull-right">
                <li><a href="/index.php">回到前台</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container-fluid" style="padding-top: 70px; padding-bottom: 70px;">
    <div class="row">
        <div class="col-sm-2">
            <div class="list-group">
            <?php foreach ( $menu as $itemName => $menuItem ) : ?>
                <a href="<?php echo $menuItem['link'];?>" class="list-group-item <?php if ($activeMenuItem===$itemName):?>active<?php endif;?>">
                    <?php echo $menuItem['name'];?>
                </a>
            <?php endforeach; ?>
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
        <div>
            <script type="text/javascript">
            var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");
            document.write(unescape(
                    "%3Cspan id='cnzz_stat_icon_1253474507'%3E%3C/span%3E%3Cscript src='" + 
                    cnzz_protocol + 
                    "s6.cnzz.com/z_stat.php%3Fid%3D1253474507%26online%3D1%26show%3Dline' type='text/javascript'%3E%3C/script%3E"
            ));
            </script>
        </div>
    </div>
</nav>