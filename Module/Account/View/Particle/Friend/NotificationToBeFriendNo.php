<?php use X\Core\X; ?>
<?php use X\Util\UUID; ?>
<?php use X\Module\Account\Service\Account\Service as AccountService; ?>
<?php /* @var $accountService AccountService */ ?>
<?php $accountService = X::system()->getServiceManager()->get(AccountService::getServiceName()); ?>
<?php $vars = get_defined_vars(); ?>
<?php /* @var $notification \X\Module\Account\Service\Account\Core\Instance\Notification */ ?>
<?php $notification = $vars['notification']; ?>
<?php $sourceData = $notification->getData(); ?>
<?php $recipientProfile = $accountService->get($sourceData['recipient_id'])->getProfileManager(); ?>
<?php $elemMark = UUID::generate(); ?>
<?php $assetsURL = X::system()->getConfiguration()->get('assets-base-url'); ?>
<?php $loaddingImg = $assetsURL.'/image/loadding.gif'; ?>
<div id="request-<?php echo $elemMark; ?>">
    <strong><?php echo $recipientProfile->get('nickname'); ?></strong>拒绝了您的好友请求。<br>
    <?php if ( !empty($sourceData['result_message']) ) : ?>
    <small><?php echo $sourceData['result_message'];?></small><br>
    <?php endif; ?>
    <div class="text-right">
        <button type="button" class="btn btn-default btn-xs btn-notice-i-know-<?php echo $elemMark; ?>">我知道了</button>
    </div>
</div>
<script>
$(document).ready(function() {
    $('.btn-notice-i-know-<?php echo $elemMark; ?>').click(function() {
        $('#request-<?php echo $elemMark; ?>').addClass('text-center').html($('<img>').attr('src', '<?php echo $loaddingImg; ?>'));
        closeNotificationByID('<?php echo $notification->getID(); ?>', function() {
            $('#request-<?php echo $elemMark; ?>').parent().remove();
            userNotificationChecker(false);
        });
    });
});
</script>