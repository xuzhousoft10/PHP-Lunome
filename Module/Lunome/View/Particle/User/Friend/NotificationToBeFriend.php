<?php use X\Core\X; ?>
<?php use X\Util\UUID; ?>
<?php use X\Module\Lunome\Service\User\Service as UserService; ?>
<?php /* @var $userService \X\Module\Lunome\Service\User\Service */ ?>
<?php $userService = X::system()->getServiceManager()->get(UserService::getServiceName()); ?>
<?php $vars = get_defined_vars(); ?>
<?php $notification = $vars['notification']; ?>
<?php $sourceData = $notification['sourceData']; ?>
<?php $requester = $userService->getAccount()->get($sourceData['requester_id']); ?>
<?php $elemMark = UUID::generate(); ?>
<?php $assetsURL = X::system()->getConfiguration()->get('assets-base-url'); ?>
<?php $loaddingImg = $assetsURL.'/image/loadding.gif'; ?>
<div id="request-<?php echo $elemMark; ?>">
    <strong><?php echo $requester->nickname; ?></strong>请求成为您的好友：<br>
    <?php if ( !empty($sourceData['message']) ) : ?>
    <small><?php echo $sourceData['message'];?></small><br>
    <?php endif; ?>
    <div class="text-right">
        <button type="button" class="btn btn-default btn-xs btn-answer-<?php echo $elemMark; ?>" data-value="0">不同意</button>
        <button type="button" class="btn btn-primary btn-xs btn-answer-<?php echo $elemMark; ?>" data-value="1">同意</button>
    </div>
</div>
<script>
$(document).ready(function() {
    function answerToBeFriendRequest( result, message ) {
        $.post('/?module=lunome&action=user/friend/answerToBeFriendRequest', {
            request         : '<?php echo $sourceData['id'];?>', 
            notification    : '<?php echo $notification['id']; ?>', 
            result          : result,
            message         : message,
        }, function( response ) {
            $('#request-<?php echo $elemMark; ?>').parent().remove();
            fixNotificationCountValue(-1);
            userNotificationChecker(false);
        }, 'text');
    }
    
    $('.btn-answer-<?php echo $elemMark; ?>').click(function() {
        var answer = $(this).attr('data-value');
        var message = '';
        if ( 0 == answer*1 ) {
            message = prompt('额~~~ 拒绝总需要个理由吧～～～');
        }
        answerToBeFriendRequest(answer, message);
        $('#request-<?php echo $elemMark; ?>').addClass('text-center').html($('<img>').attr('src', '<?php echo $loaddingImg; ?>'));
    });
});
</script>