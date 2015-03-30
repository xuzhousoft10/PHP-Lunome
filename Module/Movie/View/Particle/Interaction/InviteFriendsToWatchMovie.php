<?php 
$vars = get_defined_vars();
$movies = $vars['movies'];
$assetsURL = $vars['assetsURL'];
$selectedFriendIDs = $vars['selectedFriendIDs'];
$selectedFriendNames = $vars['selectedFriendNames'];
$commentLength = $vars['commentLength'];

/* @var $this \X\Service\XView\Core\Util\HtmlView\ParticleView */
$this->getManager()->getHost()->getScriptManager()->add('invite-friends')->setSource('js/movie/invite_friends.js');
?>
<div class="col-md-9">
    <?php foreach ( $movies as $movie ) : ?>
        <?php /* @var $movie \X\Module\Movie\Service\Movie\Core\Instance\Movie */ ?>
        <div class="pull-left lunome-movie-item">
            <div data-cover-url="<?php echo $movie->getCoverURL(); ?>" class="lunome-movie-poster thumbnail padding-0 margin-bottom-0" >
                <?php printf('<a href="/?module=movie&action=detail&id=%s" target="_blank">', $movie->get('id')); ?>
                    <div class="lunome-movie-desc-has-button">
                        <?php echo $movie->get('introduction'); ?>
                    </div>
                <?php echo '</a>'; ?>
                <div class="lunome-movie-desc-button-group">
                    <button class="btn btn-block btn-default button-invite-to-watch-movie"
                            data-title = "<?php echo $movie->get('name'); ?>"
                            data-id="<?php echo $movie->get('id'); ?>"
                    >邀请他们</button>
                </div>
            </div>
            <div class="white-space-nowrap lunome-movie-title-area-short">
                <a href="/?module=movie&action=detail&id=<?php echo $movie->get('id'); ?>" target="_blank">
                    <strong><?php echo $movie->get('name'); ?></strong>
                </a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div    class="modal fade" 
        id="invite-to-watch-movie-dialog" 
        data-id-list="<?php echo $selectedFriendIDs;?>"
        data-name-list="<?php echo $selectedFriendNames; ?>"
>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">邀请好友</h4>
      </div>
      <div class="modal-body">
        <p id="invite-to-watch-movie-dialog-message"></p>
        <div>
            备注：<br>
            <textarea   id="invite-to-watch-movie-dialog-comment" 
                        rows="" 
                        cols="" 
                        class="width-full"
                        maxlength="<?php echo $commentLength; ?>"
            ></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        <button type="button" class="btn btn-primary" id="invite-to-watch-movie-dialog-yes">确定</button>
      </div>
    </div>
  </div>
</div>