<?php 
use X\Core\X;
use X\Module\Movie\Service\Movie\Service as MovieService;
use X\Service\XView\Core\Handler\Html;

$vars = get_defined_vars();
/* @var $notification \X\Module\Account\Service\Account\Core\Instance\Notification */
$notification = $vars['notification'];
$sourceData = $notification->getData();
$requester = $notification->getProducer();
$profile = $requester->getProfileManager();

/* @var $movieService MovieService */
$movieService = X::system()->getServiceManager()->get(MovieService::getServiceName());
$movie = $movieService->get($sourceData['movie_id']);

$elemMark = $notification->getID();
?>
<div id="message-<?php echo $elemMark; ?>" class="clearfix">
    <div class="pull-left">
        <img    class="thumbnail margin-0 padding-0" 
                width="60" 
                height="80" 
                alt="<?php echo Html::HTMLEncode($movie->get('name'));?>" 
                src="<?php echo $movie->getCoverURL();?>"
        >
    </div>
    <div class="pull-left padding-left-5">
        <strong>电影《<?php echo Html::HTMLEncode($movie->get('name'));?>》</strong> 
        <br>
        <div class="user-notification-chat-message">
            <small class="text-muted">
                来自<?php echo Html::HTMLEncode($profile->get('nickname')); ?>的推荐
            </small>
            <br>
        </div>
        <div class="text-right">
            <button id="message-btn-<?php echo $elemMark;?>" class="btn btn-primary btn-xs">查看</button>
            <button id="message-btn-<?php echo $elemMark;?>-close" class="btn btn-primary btn-xs">关闭</button>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('#message-btn-<?php echo $elemMark;?>').click(function() {
        window.open('/?module=movie&action=detail&id=<?php echo $movie->get('id');?>');
        $('#message-<?php echo $elemMark; ?>').parent().remove();
        closeNotificationByID('<?php echo $notification->getID(); ?>', function() {});
    });
    $('#message-btn-<?php echo $elemMark;?>-close').click(function(){
        $('#message-<?php echo $elemMark; ?>').parent().remove();
        closeNotificationByID('<?php echo $notification->getID(); ?>', function() {});
    });
});
</script>