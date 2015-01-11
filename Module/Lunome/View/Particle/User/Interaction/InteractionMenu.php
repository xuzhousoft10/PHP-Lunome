<?php $vars = get_defined_vars(); ?>
<?php $items = $vars['items']; ?>
<?php $activeItem = $vars['activeItem']; ?>
<?php $parameters = $vars['parameters']; ?>
<?php $parameters = empty($parameters) ? '' : '&'.http_build_query($parameters); ?>
<div class="col-md-3">
    <div class="list-group">
        <?php foreach ( $items as $itemName => $item ) : ?>
            <?php $itemStatus = ($activeItem===$itemName) ? 'active' : ''; ?>
            <a href="<?php echo $item['link'].$parameters?>" class="list-group-item <?php echo $itemStatus;?>"
            ><?php echo $item['label'];?></a>
        <?php endforeach; ?>
    </div>
</div>