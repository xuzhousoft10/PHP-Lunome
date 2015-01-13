<?php use X\Core\X; ?>
<?php $vars = get_defined_vars(); ?>
<?php $movies = $vars['movies']; ?>
<?php $assetsURL = X::system()->getConfiguration()->get('assets-base-url'); ?>
<?php $this->addScriptFile('user-home-movie-index', $assetsURL.'/js/user_interaction_movie_invite_to_watch_movie.js'); ?>
<?php $friendInformation = $vars['friendInformation']; ?>
<div class="col-md-9">
    <?php if (empty($movies)) : ?>
        <p>
            额～～～ 怎么说呢， 反正找不到你们都想看的电影， 去<?php echo $friendInformation->nickname; ?>的
            <a href="/?module=lunome&action=movie/home/index&id=<?php echo $friendInformation->account_id; ?>&mark=1">
            主页
            </a>
            看看吧。
        </p>
    <?php else: ?>
        找到几部你和<?php echo $friendInformation->nickname; ?>都想看的电影， 瞅瞅吧。<br>
        <hr>
        <div class="clearfix">
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
                                    data-friend-name="<?php echo $friendInformation->nickname; ?>"
                                    data-friend-id="<?php echo $friendInformation->account_id; ?>"
                                    data-loadding-img = "<?php echo $assetsURL.'/image/loadding.gif';?>"
                            >邀请TA</button>
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
    <?php endif;?>
</div>

<div class="modal fade" id="invite-to-watch-movie-dialog">
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