<?php 
$vars = get_defined_vars();
$movies = $vars['movies'];
$assetsURL = $vars['assetsURL'];
/* @var $friendInformation \X\Module\Account\Service\Account\Core\Instance\Account */
$friendInformation = $vars['friendInformation'];
$profile = $friendInformation->getProfileManager();
?>
<div class="col-md-9">
    <?php if (empty($movies)) : ?>
        <p>
            额～～～ 怎么说呢， 反正找不到你们都想看的电影， 去<?php echo $profile->get('nickname'); ?>的
            <a href="/?module=movie&action=home/index&id=<?php echo $friendInformation->getID(); ?>&mark=1">
            主页
            </a>
            看看吧。
        </p>
    <?php else: ?>
        找到几部你和<?php echo $profile->get('nickname'); ?>都想看的电影， 瞅瞅吧。<br>
        <hr>
        <div class="clearfix">
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
                                    data-friend-name="<?php echo $profile->get('nickname'); ?>"
                                    data-friend-id="<?php echo $friendInformation->getID(); ?>"
                                    data-loadding-img = "<?php echo $assetsURL.'/image/loadding.gif';?>"
                            >邀请TA</button>
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