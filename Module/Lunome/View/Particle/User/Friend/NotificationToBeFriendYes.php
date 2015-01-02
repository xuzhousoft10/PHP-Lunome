<?php use X\Core\X; ?>
<?php use X\Util\UUID; ?>
<?php use X\Module\Lunome\Service\User\Service as UserService; ?>
<?php /* @var $userService \X\Module\Lunome\Service\User\Service */ ?>
<?php $userService = X::system()->getServiceManager()->get(UserService::getServiceName()); ?>
<?php $vars = get_defined_vars(); ?>
<?php $notification = $vars['notification']; ?>
<?php $sourceData = $notification['sourceData']; ?>
<?php $recipient = $userService->getAccount()->get($sourceData['recipient_id']); ?>
<?php $elemMark = UUID::generate(); ?>
<?php $assetsURL = X::system()->getConfiguration()->get('assets-base-url'); ?>
<?php $loaddingImg = $assetsURL.'/image/loadding.gif'; ?>
<div id="request-<?php echo $elemMark; ?>">
    <strong><?php echo $recipient->nickname; ?></strong>同意了您的好友请求。<br>
    <div class="text-right">
        <button type="button" class="btn btn-default btn-xs btn-notice-i-know-<?php echo $elemMark; ?>">我知道了</button>
    </div>
</div>
<script>
$(document).ready(function() {
    $('.btn-notice-i-know-<?php echo $elemMark; ?>').click(function() {
        $('#request-<?php echo $elemMark; ?>').addClass('text-center').html($('<img>').attr('src', '<?php echo $loaddingImg; ?>'));
        closeNotificationByID('<?php echo $notification['id']; ?>', function() {
            $('#request-<?php echo $elemMark; ?>').parent().remove();
            userNotificationChecker(false);
        });
    });
});
</script>