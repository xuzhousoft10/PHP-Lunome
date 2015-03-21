<?php $vars = get_defined_vars(); ?>
<?php $record = $vars['record']; ?>
<?php $myInformation = $vars['myInformation']; ?>
<div class="clearfix margin-bottom-10">
    <div class="col-md-1">
        <img    class="thumbnail padding-0 margin-0" 
                alt="<?php echo $myInformation->get('nickname');?>" 
                src="<?php echo $myInformation->get('photo');?>"
                width="80"
                height="80"
        >
    </div>
    <div class="col-md-10">
        <small class="text-muted"><?php echo $record->get('wrote_at'); ?></small>
        <div class="well well-sm">
            <?php echo $record->get('content'); ?>
        </div>
    </div>
</div>