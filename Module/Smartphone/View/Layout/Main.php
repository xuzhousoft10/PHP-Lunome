<?php 
/* @var $this X\Service\XView\Core\Handler\Html */ 
$vars = get_defined_vars();
$navMenu = $vars['navMenu'];
$activeNavMenuItem = $vars['activeNavMenuItem'];
?>
<div data-role="page" id="pageone">
    <div data-role="header">
        <h1><?php echo $this->title; ?></h1>
    </div>
    
    <div data-role="content">
        <?php foreach ( $this->particles as $particle ) :?>
            <?php echo $particle['content'];?>
        <?php endforeach; ?>
    </div>

    <div data-role="footer" data-position="fixed">
        <div data-role="navbar">
            <ul>
                <?php foreach ( $navMenu as $navMenuItemKey => $navMenuItem ): ?>
                <li>
                    <a  href="<?php echo $navMenuItem['link'];?>" 
                        data-icon="<?php echo $navMenuItem['icon'];?>"
                        class="<?php if ($activeNavMenuItem===$navMenuItemKey): ?>ui-btn-active<?php endif; ?>"
                    ></a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>