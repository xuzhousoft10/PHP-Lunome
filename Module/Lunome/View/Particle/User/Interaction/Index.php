<?php use X\Module\Lunome\Util\Action\Userinteraction; ?>
<?php $vars = get_defined_vars(); ?>
<?php $items = $vars['items']; ?>
<?php $parameters = $vars['parameters']; ?>
<?php $parameters = empty($parameters) ? '' : '&'.http_build_query($parameters); ?>
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