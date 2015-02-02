<?php use X\Core\X; ?>
<?php use X\Module\Lunome\Service\User\Service as UserService; ?>
<?php use X\Module\Lunome\Service\Movie\Service as MovieService; ?>
<?php /* @var $userService \X\Module\Lunome\Service\User\Service */ ?>
<?php $userService = X::system()->getServiceManager()->get(UserService::getServiceName()); ?>
<?php /*@var $movieService \X\Module\Lunome\Service\Movie\Service */ ?>
<?php $movieService = X::system()->getServiceManager()->get(MovieService::getServiceName()); ?>
<?php $vars = get_defined_vars(); ?>
<?php $notification = $vars['notification']; ?>
<?php $sourceData = $notification['sourceData']; ?>
<?php $requester = $userService->getAccount()->getInformation($sourceData['account_id']); ?>
<?php $elemMark = $notification['id']; ?>
<?php $isAgreed = (MovieService::INVITATION_ANSWER_YES===$sourceData['answer']*1) ? true : false; ?>
<?php $movie = $movieService->get($sourceData['movie_id']); ?>
<?php $hasComment = empty($sourceData['answer_comment']) ? false : true; ?>
<div id="message-<?php echo $elemMark; ?>" class="clearfix">
    <div class="pull-left">
        <img class="thumbnail margin-0 padding-0" width="80" height="80" alt="<?php echo $requester->nickname; ?>" src="<?php echo $requester->photo; ?>">
    </div>
    <div class="pull-left padding-left-5">
        <strong><?php echo $requester->nickname; ?></strong> 
        <br>
        <div class="user-notification-chat-message">
            <small class="text-muted">
                <span class="text-info">
                    <?php if ($isAgreed) : ?>
                        同意和你一起去看《<?php echo $movie['name']; ?>》
                    <?php else : ?>
                        不同意和你一起去看《<?php echo $movie['name']; ?>》
                    <?php endif; ?>
                </span><br>
            </small>
        </div>
        <div class="text-right">
            <?php if ($hasComment) : ?>
                <button id="message-btn-<?php echo $elemMark;?>" class="btn btn-primary btn-xs">查看</button>
            <?php else : ?>
                <button id="message-btn-<?php echo $elemMark;?>-close" class="btn btn-primary btn-xs">关闭</button>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php if ($hasComment) : ?>
    <div class="modal fade" id="invite-to-watch-movie-detail-dialog-<?php echo $elemMark; ?>">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"><?php echo $requester->nickname; ?>对你的邀请说了点其他的</h4>
                </div>
                
                <div class="modal-body">
                    <p><?php echo $sourceData['answer_comment'];?></p>
                </div>
                <div class="modal-footer">
                    <?php if ( $isAgreed ) : ?>
                        <a  href="/?module=lunome&action=user/chat/index&friend=<?php echo $requester->account_id; ?>" 
                            class="btn btn-default button-invite-to-watch-movie-detail-dialog-<?php echo $elemMark; ?>-got-it"
                            target="_blank"
                        >与<?php echo $requester->nickname;?>聊天</a>
                    <?php endif; ?>
                    <button type="button" class="btn btn-primary button-invite-to-watch-movie-detail-dialog-<?php echo $elemMark; ?>-got-it">知道了</button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<script>
$(document).ready(function() {
    $('#message-btn-<?php echo $elemMark;?>').click(function() {
        $('#invite-to-watch-movie-detail-dialog-<?php echo $elemMark; ?>').modal('show');
        $('.button-invite-to-watch-movie-detail-dialog-<?php echo $elemMark; ?>-got-it').click(function() {
            $('#invite-to-watch-movie-detail-dialog-<?php echo $elemMark; ?>').modal('hide');
            $('#message-<?php echo $elemMark; ?>').parent().remove();
            closeNotificationByID('<?php echo $notification['id']; ?>', function() {});
        });
    });
    
    $('#message-btn-<?php echo $elemMark;?>-close').click(function() {
        $('#message-<?php echo $elemMark; ?>').parent().remove();
        closeNotificationByID('<?php echo $notification['id']; ?>', function() {});
    });
});
</script>