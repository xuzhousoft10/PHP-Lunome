<?php use X\Core\X; ?>
<?php use X\Util\UUID; ?>
<?php use X\Module\Lunome\Service\User\Service as UserService; ?>
<?php use X\Module\Lunome\Service\Movie\Service as MovieService; ?>
<?php use X\Module\Lunome\Model\Movie\MovieInvitationModel;?>
<?php /* @var $userService \X\Module\Lunome\Service\User\Service */ ?>
<?php $userService = X::system()->getServiceManager()->get(UserService::getServiceName()); ?>
<?php /*@var $movieService \X\Module\Lunome\Service\Movie\Service */ ?>
<?php $movieService = X::system()->getServiceManager()->get(MovieService::getServiceName()); ?>
<?php $vars = get_defined_vars(); ?>
<?php $notification = $vars['notification']; ?>
<?php $sourceData = $notification['sourceData']; ?>
<?php $requester = $userService->getAccount()->getInformation($sourceData['inviter_id']); ?>
<?php $movie = $movieService->get($sourceData['movie_id']); ?>
<?php $moviCoverURL = $movieService->getCoverURL($movie['id']); ?>
<?php $elemMark = UUID::generate(); ?>
<?php $assetsURL = X::system()->getConfiguration()->get('assets-base-url'); ?>
<?php $loaddingImg = $assetsURL.'/image/loadding.gif'; ?>
<?php $commentLength = MovieInvitationModel::model()->getAttribute('answer_comment')->getLength();?>
<div id="message-<?php echo $elemMark; ?>" class="clearfix">
    <div class="pull-left">
        <img class="thumbnail margin-0 padding-0" width="80" height="80" alt="<?php echo $requester->nickname; ?>" src="<?php echo $requester->photo; ?>">
    </div>
    <div class="pull-left padding-left-5">
        <strong><?php echo $requester->nickname; ?></strong> 
        <br>
        <div class="user-notification-chat-message">
            <small class="text-muted">
                <span class="text-info">我想邀请你去看场电影呦</span><br>
                <?php echo $sourceData['comment']; ?><br>
            </small>
        </div>
        <div class="text-right">
            <button id="message-btn-<?php echo $elemMark;?>" class="btn btn-primary btn-xs">查看</button>
        </div>
    </div>
</div>

<div class="modal fade" id="invite-to-watch-movie-detail-dialog-<?php echo $elemMark; ?>">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title"><?php echo $requester->nickname; ?>邀请你去看《<?php echo $movie['name'];?>》</h4>
      </div>
      <div class="modal-body">
        <p><?php echo $sourceData['comment'];?></p>
        <a  href="/?module=lunome&action=user/chat/index&friend=<?php echo $requester->account_id; ?>" 
            class="btn btn-default btn-sm" 
            target="_blank"
        >与<?php echo $requester->nickname; ?>聊天</a>
        <hr>
        <div class="clearfix">
            <div class="col-md-4">
                <img alt="<?php echo $movie['name']; ?>" src="<?php echo $moviCoverURL;?>" width="150" height="200">
            </div>
            <div class="col-md-8">
                <small class="text-muted">
                    <?php echo $movie['introduction']; ?>
                </small>
                <div class="text-right">
                    <small>
                        <a href="/?module=lunome&action=movie/detail&id=<?php echo $movie['id']; ?>" target="_blank">查看电影详情</a>
                    </small>
                </div>
            </div>
        </div>
        <hr>
        <div>
            备注：<br>
            <textarea   id="invite-to-watch-movie-detail-dialog-<?php echo $elemMark; ?>-answer-comment"
                        class="width-full" 
                        placeholder="拒绝？ 总得有个理由吧～～～ 同意， 说个时间，地点啥的呗～～～"
                        maxlength="<?php echo $commentLength; ?>"
            ></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default button-invite-to-watch-movie-detail-dialog-<?php echo $elemMark; ?>-answer" data-value="<?php echo MovieService::INVITATION_ANSWER_NO; ?>">没兴趣</button>
        <button type="button" class="btn btn-primary button-invite-to-watch-movie-detail-dialog-<?php echo $elemMark; ?>-answer" data-value="<?php echo MovieService::INVITATION_ANSWER_YES; ?>">答应</button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    $('#message-btn-<?php echo $elemMark;?>').click(function() {
        $('#invite-to-watch-movie-detail-dialog-<?php echo $elemMark; ?>').modal('show');
        $('.button-invite-to-watch-movie-detail-dialog-<?php echo $elemMark; ?>-answer').click(function() {
            var comment = $('#invite-to-watch-movie-detail-dialog-<?php echo $elemMark; ?>-answer-comment').val();
            var loaddingImage = $('<img>').attr('src', '<?php echo $loaddingImg;?>' );
            $('#invite-to-watch-movie-detail-dialog-<?php echo $elemMark; ?>').find('.modal-body').html(loaddingImage);
            $.post('/?module=lunome&action=movie/interaction/answerMovieInvitation', {
                notification : '<?php echo $notification['id']; ?>', 
                answer       : $(this).attr('data-value'), 
                comment      : comment,
            }, function(response) {
                $('#invite-to-watch-movie-detail-dialog-<?php echo $elemMark; ?>').modal('hide');
                $('#message-<?php echo $elemMark; ?>').parent().remove();
                fixNotificationCountValue(-1);
            }, 'text');
        });
    });
});
</script>