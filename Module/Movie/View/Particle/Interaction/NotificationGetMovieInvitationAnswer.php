<?php 
use X\Core\X;
use X\Module\Movie\Service\Movie\Core\Instance\Account;
use X\Module\Account\Service\Account\Service as AccountService;
use X\Module\Movie\Service\Movie\Service as MovieService;

$vars = get_defined_vars();
/* @var $notification \X\Module\Account\Service\Account\Core\Instance\Notification */
$notification = $vars['notification'];
$sourceData = $notification->getData();
/* @var $accountService AccountService */
$accountService = X::system()->getServiceManager()->get(AccountService::getServiceName());
$requester = $accountService->get($sourceData['account_id']);
$profile = $requester->getProfileManager();

/* @var $movieService MovieService */
$movieService = X::system()->getServiceManager()->get(MovieService::getServiceName());
$movie = $movieService->get($sourceData['movie_id']);

$elemMark = $notification->getID();
$isAgreed = (Account::INVITATION_ANSWER_YES===$sourceData['answer']*1) ? true : false;
$hasComment = empty($sourceData['answer_comment']) ? false : true;
?>
<div id="message-<?php echo $elemMark; ?>" class="clearfix">
    <div class="pull-left">
        <img class="thumbnail margin-0 padding-0" width="80" height="80" alt="<?php echo $profile->get('nickname'); ?>" src="<?php echo $profile->get('photo'); ?>">
    </div>
    <div class="pull-left padding-left-5">
        <strong><?php echo $profile->get('nickname'); ?></strong> 
        <br>
        <div class="user-notification-chat-message">
            <small class="text-muted">
                <span class="text-info">
                    <?php if ($isAgreed) : ?>
                        同意和你一起去看《<?php echo $movie->get('name'); ?>》
                    <?php else : ?>
                        不同意和你一起去看《<?php echo $movie->get('name'); ?>》
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
                    <h4 class="modal-title"><?php echo $profile->get('nickname'); ?>对你的邀请说了点其他的</h4>
                </div>
                
                <div class="modal-body">
                    <p><?php echo $sourceData['answer_comment'];?></p>
                </div>
                <div class="modal-footer">
                    <?php if ( $isAgreed ) : ?>
                        <a  href="/?module=account&action=chat/index&friend=<?php echo $requester->getID(); ?>" 
                            class="btn btn-default button-invite-to-watch-movie-detail-dialog-<?php echo $elemMark; ?>-got-it"
                            target="_blank"
                        >与<?php echo $profile->get('nickname');?>聊天</a>
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
            closeNotificationByID('<?php echo $notification->getID(); ?>', function() {});
        });
    });
    
    $('#message-btn-<?php echo $elemMark;?>-close').click(function() {
        $('#message-<?php echo $elemMark; ?>').parent().remove();
        closeNotificationByID('<?php echo $notification->getID(); ?>', function() {});
    });
});
</script>