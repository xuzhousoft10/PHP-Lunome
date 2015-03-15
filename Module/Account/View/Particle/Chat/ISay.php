<?php $vars = get_defined_vars(); ?>
<?php $record = $vars['record']; ?>
<?php $myInformation = $vars['myInformation']; ?>
<div class="clearfix margin-bottom-10">
    <div class="col-md-1">
        <img    class="thumbnail padding-0 margin-0" 
                alt="<?php echo $myInformation->nickname;?>" 
                src="<?php echo $myInformation->photo;?>"
                width="80"
                height="80"
        >
    </div>
    <div class="col-md-10">
        <small class="text-muted"><?php echo $record->wrote_at; ?></small>
        <div class="well well-sm">
            <?php echo $record->content; ?>
        </div>
    </div>
</div>