<?php $vars = get_defined_vars(); ?>
<?php $messages = $vars['messages']; ?>
<?php $friendInformation = $vars['friendInformation']; ?>
<?php foreach ( $messages as $message ) : ?>
<?php /* @var $message \X\Module\Account\Service\Account\Core\Instance\ChatMessage */ ?>
<div class="clearfix margin-bottom-10">
    <div class="col-md-1"></div>
    <div class="col-md-10 text-right">
        <small class="text-muted"><?php echo $message->get('wrote_at'); ?></small>
        <div class="well well-sm">
            <?php echo $message->getContent(); ?>
        </div>
    </div>
    <div class="col-md-1">
        <img    class="thumbnail padding-0 margin-0" 
                alt="<?php echo $friendInformation->get('nickname');?>" 
                src="<?php echo $friendInformation->get('photo');?>"
                width="80"
                height="80"
        >
    </div>
</div>
<?php endforeach; ?>