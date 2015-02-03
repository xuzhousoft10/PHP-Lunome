<?php 
$vars = get_defined_vars(); 
$settingItems = $vars['settingItems'];
?>
<div class="col-md-3">
    <div class="list-group">
        <?php foreach ( $settingItems as $settingItem ) : ?>
            <?php $settingItemStatus = ($settingItem['isActive']) ? 'active' : ''; ?>
            <a  href    = "<?php echo $settingItem['link']?>" 
                class   = "list-group-item <?php echo $settingItemStatus?>"
            >
                <?php echo $settingItem['label'];?>
            </a>
        <?php endforeach; ?>
    </div>
</div>