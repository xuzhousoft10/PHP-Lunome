<?php use X\Core\X; ?>
<?php use X\Util\UUID; ?>
<?php use X\Module\Account\Service\Account\Service as AccountService; ?>
<?php /* @var $accountService AccountService */ ?>
<?php $accountService = X::system()->getServiceManager()->get(AccountService::getServiceName()); ?>
<?php $vars = get_defined_vars(); ?>
<?php /* @var $notification \X\Module\Account\Service\Account\Core\Instance\Notification */ ?>
<?php $notification = $vars['notification']; ?>
<?php $sourceData = $notification->getData(); ?>
<?php $writerAccount = $accountService->get($sourceData['writer_id']); ?>
<?php $writerAccountProfile = $writerAccount->getProfileManager(); ?>
<?php $unreadCount = $writerAccount->getFriendManager()->get($sourceData['writer_id'])->getChatManager()->countUnread(); ?>
<?php $elemMark = UUID::generate(); ?>
<?php $assetsURL = X::system()->getConfiguration()->get('assets-base-url'); ?>
<?php $loaddingImg = $assetsURL.'/image/loadding.gif'; ?>
<div id="message-<?php echo $elemMark; ?>" class="clearfix">
    <div class="pull-left">
        <img    class="thumbnail margin-0 padding-0" width="80" height="80" 
                alt="<?php echo $writerAccountProfile->get('nickname'); ?>" 
                src="<?php echo $writerAccountProfile->get('photo'); ?>">
    </div>
    <div class="pull-left padding-left-5">
        <strong><?php echo $writerAccountProfile->get('nickname'); ?></strong> 
        <small class="text-info">(+<?php echo $unreadCount; ?>)</small>
        <br>
        <div class="user-notification-chat-message">
            <small class="text-muted">
                <?php echo $sourceData['content']; ?><br>
            </small>
        </div>
        <div class="text-right">
            <button id="message-btn-<?php echo $elemMark;?>" class="btn btn-primary btn-xs">查看</button>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('#message-btn-<?php echo $elemMark;?>').click(function() {
        $('#message-<?php echo $elemMark; ?>').parent().remove();
        fixNotificationCountValue(-1);
        window.open('/?module=account&action=chat/startByNotification&id=<?php echo $notification->getID(); ?>');
        return true;
    });
});
</script>