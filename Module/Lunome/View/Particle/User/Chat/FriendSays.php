<?php $vars = get_defined_vars(); ?>
<?php $messages = $vars['messages']; ?>
<?php $friendInformation = $vars['friendInformation']; ?>
<?php foreach ( $messages as $message ) : ?>
<div class="clearfix margin-bottom-10">
    <div class="col-md-1"></div>
    <div class="col-md-10 text-right">
        <small class="text-muted"><?php echo $message->wrote_at; ?></small>
        <div class="well well-sm">
            <?php echo $message->content; ?>
        </div>
    </div>
    <div class="col-md-1">
        <img    class="thumbnail padding-0 margin-0" 
                alt="<?php echo $friendInformation->nickname;?>" 
                src="<?php echo $friendInformation->photo;?>"
                width="80"
                height="80"
        >
    </div>
</div>
<?php endforeach; ?>