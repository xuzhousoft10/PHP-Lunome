<?php 
$vars = get_defined_vars();
$backendAssetsURL = $vars['backendAssetsURL'];
$mainMenu = $vars['mainMenu'];
?>
<?php foreach ( $mainMenu as $mainMenuItem ) : ?>
    <?php if (isset($mainMenuItem['subitem'])): ?>
        <?php echo $mainMenuItem['name']; ?><br>
        <?php foreach ( $mainMenuItem['subitem'] as $mainMenuItemSubItem ) : ?>
            <a href="<?php echo $mainMenuItemSubItem['link'];?>"
            ><?php echo $mainMenuItemSubItem['name']; ?></a>
        <?php endforeach; ?>
    <?php else: ?>
        <a href="<?php echo $mainMenuItem['link']; ?>"
        ><?php echo $mainMenuItem['name']; ?></a>
    <?php endif; ?>
    <hr>
<?php endforeach; ?>