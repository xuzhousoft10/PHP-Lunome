<?php 
$vars = get_defined_vars();
$menu = $vars['menu'];
?>
<ul data-role="listview" data-inset="true">
    <?php foreach ( $menu as $menuItem ): ?>
    <li>
        <a href="<?php echo $menuItem['link']; ?>">
            <?php echo $menuItem['label'];?>
        </a>
    </li>
    <?php endforeach;?>
</ul>