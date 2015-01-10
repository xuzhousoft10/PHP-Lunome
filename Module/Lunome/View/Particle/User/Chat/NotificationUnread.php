<?php use X\Core\X; ?>
<?php use X\Util\UUID; ?>
<?php use X\Module\Lunome\Service\User\Service as UserService; ?>
<?php /* @var $userService \X\Module\Lunome\Service\User\Service */ ?>
<?php $userService = X::system()->getServiceManager()->get(UserService::getServiceName()); ?>
<?php $vars = get_defined_vars(); ?>
<?php $notification = $vars['notification']; ?>
<?php $sourceData = $notification['sourceData']; ?>
<?php $requester = $userService->getAccount()->getInformation($sourceData['writer_id']); ?>
<?php $unreadCount = $userService->getAccount()->countUnreadChatMessagesByFriendID($sourceData['writer_id']); ?>
<?php $elemMark = UUID::generate(); ?>
<?php $assetsURL = X::system()->getConfiguration()->get('assets-base-url'); ?>
<?php $loaddingImg = $assetsURL.'/image/loadding.gif'; ?>
<div id="message-<?php echo $elemMark; ?>" class="clearfix">
    <div class="pull-left">
        <img class="thumbnail margin-0 padding-0" width="80" height="80" alt="<?php echo $requester->nickname; ?>" src="<?php echo $requester->photo; ?>">
    </div>
    <div class="pull-left padding-left-5">
        <strong><?php echo $requester->nickname; ?></strong> 
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
        window.open('/?module=lunome&action=user/chat/startByNotification&id=<?php echo $notification['id']; ?>');
        return true;
    });
});
</script>