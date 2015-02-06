<?php 
/* @var $this X\Service\XView\Core\Handler\Html */
$vars = get_defined_vars();
$mainMenu = $vars['mainMenu'];
$mainMenuActived = $vars['mainMenuActived'];
?>
<nav class="navbar navbar-default navbar-fixed-top ">
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
                <div class="btn-group-vertical full-width">
                    <?php foreach ( $mainMenu as $mainMenuKey => $mainMenuItem ) : ?>
                        <?php $itemStatus = ($mainMenuActived===$mainMenuKey) ? 'primary' : 'default'; ?>
                        <?php if (isset($mainMenuItem['subitem'])): ?>
                        <div class="btn-group">
                            <button type="button" class="btn btn-<?php echo $itemStatus;?> dropdown-toggle" data-toggle="dropdown">
                                <?php echo $mainMenuItem['name']; ?>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <?php foreach ( $mainMenuItem['subitem'] as $mainMenuItemSubItem ) : ?>
                                    <li>
                                        <a href="<?php echo $mainMenuItemSubItem['link'];?>"
                                        ><?php echo $mainMenuItemSubItem['name']; ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php else: ?>
                            <a href="<?php echo $mainMenuItem['link']; ?>" class="btn btn-<?php echo $itemStatus;?>"
                            ><?php echo $mainMenuItem['name']; ?></a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="col-sm-10">
            <?php foreach ( $this->particles as $particle ) :?>
                <?php echo $particle['content'];?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<nav class="navbar navbar-default navbar-fixed-bottom">
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