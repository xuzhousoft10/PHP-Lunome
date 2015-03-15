<?php use X\Core\X; ?>
<?php use X\Util\UUID; ?>
<?php use X\Module\Lunome\Service\User\Service as UserService; ?>
<?php use X\Module\Lunome\Model\Account\AccountFriendshipRequestModel; ?>
<?php /* @var $userService \X\Module\Lunome\Service\User\Service */ ?>
<?php $userService = X::system()->getServiceManager()->get(UserService::getServiceName()); ?>
<?php $vars = get_defined_vars(); ?>
<?php $notification = $vars['notification']; ?>
<?php $sourceData = $notification['sourceData']; ?>
<?php $requester = $userService->getAccount()->getInformation($sourceData['requester_id']); ?>
<?php $elemMark = UUID::generate(); ?>
<?php $assetsURL = X::system()->getConfiguration()->get('assets-base-url'); ?>
<?php $loaddingImg = $assetsURL.'/image/loadding.gif'; ?>
<?php $resultMessageLength = AccountFriendshipRequestModel::model()->getAttribute('result_message')->getLength(); ?>
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

<!-- add friend dialog -->
<div class="modal fade" id="answer-to-be-friend-dialog-<?php echo $elemMark; ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">何必如此残忍～～～～</h4>
            </div>
            <div class="modal-body">
                <textarea class="width-full" id="result-message" maxlength="<?php echo $resultMessageLength;?>"></textarea>
            </div>
            <div class="modal-footer">
                <button type            = "button" 
                        class           = "btn btn-primary btn-answer-to-be-friend-dialog" 
                        id              = "btn-add-as-friend" 
                >回复</button>
            </div>
        </div>
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
        var answerTheRquest = function(answer, message) {
            answerToBeFriendRequest(answer, message);
            $('#request-<?php echo $elemMark; ?>').addClass('text-center').html($('<img>').attr('src', '<?php echo $loaddingImg; ?>'));
        };
        
        var answer = $(this).attr('data-value');
        var message = '';
        if ( 0 == answer*1 ) {
            $('#answer-to-be-friend-dialog-<?php echo $elemMark; ?>').modal('show');
            $('.btn-answer-to-be-friend-dialog').click(function() {
                answerTheRquest(answer, $('#result-message').val());
            });
            $('#answer-to-be-friend-dialog-<?php echo $elemMark; ?>').on('hidden.bs.modal', function (e) {
                answerTheRquest(answer, $('#result-message').val());
            });
        } else {
            answerTheRquest(answer, '');
        }
    });
});
</script>