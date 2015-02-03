<?php use X\Module\Lunome\Util\Action\Userinteraction; ?>
<?php 
$vars = get_defined_vars();
$items = $vars['items'];
$parameters = $vars['parameters'];
?>
<div class="col-md-9 text-center">
    <?php foreach ( $items as $itemName => $item ) : ?>
        <?php if (Userinteraction::INTERACTION_MENU_ITEM_INDEX === $itemName): ?>
            <?php continue; ?>
        <?php endif; ?>
        <a href="<?php echo $item['link'].$parameters?>" class="btn btn-default btn-lg btn-block">
            <small><?php echo $item['label'];?></small>
        </a>
        <br>
        <br>
    <?php endforeach; ?>
</div>