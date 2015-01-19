<?php use X\Core\X; ?>
<?php $vars = get_defined_vars(); ?>
<?php $movies = $vars['movies']; ?>
<?php $assetsURL = X::system()->getConfiguration()->get('assets-base-url'); ?>
<?php $friends = $vars['friends']; ?>
<?php $selectedFriendIDs = array(); ?>
<?php $selectedFriendNames = array(); ?>
<?php $this->addScriptFile('user-home-movie-index', $assetsURL.'/js/user_interaction_movie_invite_friends_to_watch_movie.js'); ?>
<div class="col-md-9">
    <?php foreach ( $movies as $movie ) : ?>
        <div class="pull-left lunome-movie-item">
            <div data-cover-url="<?php echo $movie['cover']; ?>" class="lunome-movie-poster thumbnail padding-0 margin-bottom-0" >
                <?php printf('<a href="/?module=lunome&action=movie/detail&id=%s" target="_blank">', $movie['id']); ?>
                    <div class="lunome-movie-desc-has-button">
                        <?php echo $movie['introduction']; ?>
                    </div>
                <?php echo '</a>'; ?>
                <div class="lunome-movie-desc-button-group">
                    <button class="btn btn-block btn-default button-invite-to-watch-movie"
                            data-title = "<?php echo $movie['name']; ?>"
                            data-id="<?php echo $movie['id']; ?>"
                    >邀请他们</button>
                </div>
            </div>
            <div class="white-space-nowrap lunome-movie-title-area-short">
                <a href="/?module=lunome&action=movie/detail&id=<?php echo $movie['id']; ?>" target="_blank">
                    <strong><?php echo $movie['name']; ?></strong>
                </a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php foreach ( $friends as $friend ) : ?>
    <?php $selectedFriendIDs[] = $friend->account_id; ?>
    <?php $selectedFriendNames[] = $friend->nickname; ?>
<?php endforeach; ?>
<div    class="modal fade" 
        id="invite-to-watch-movie-dialog" 
        data-id-list="<?php echo implode(',', $selectedFriendIDs);?>"
        data-name-list="<?php echo implode(',', $selectedFriendNames); ?>"
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
            <textarea id="invite-to-watch-movie-dialog-comment" rows="" cols="" class="width-full"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        <button type="button" class="btn btn-primary" id="invite-to-watch-movie-dialog-yes">确定</button>
      </div>
    </div>
  </div>
</div>