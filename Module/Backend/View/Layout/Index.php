<?php 
/* @var $this X\Service\XView\Core\Handler\Html */
$vars = get_defined_vars();
$menu = $vars['menu'];
$activeMenuItem = $vars['activeMenuItem'];
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
    </div>
</nav>