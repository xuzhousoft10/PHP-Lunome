<?php 
$vars = get_defined_vars();
$friends = $vars['friends']; 
$peopleCount = $vars['peopleCount'];
?>
<div class="col-md-9">
    <form action="/?module=movie" method="post">
        <div class="thumbnail clearfix lunome-friends-interaction-friends-container" data-people-count="<?php echo $peopleCount; ?>">
        <?php foreach ( $friends as $index => $friend ) : ?>
            <?php /* @var $friend \X\Module\Account\Service\Account\Core\Instance\Account */ ?>
            <?php $profile = $friend->getProfileManager(); ?>
            <div class="thumbnail text-center col-md-2 pointer-hand friend-list-item">
                <img class="thumbnail padding-0 margin-bottom-0" alt="<?php echo $profile->get('nickname'); ?>" src="<?php echo $profile->get('photo');?>" width="80" height="80">
                <input name="friends[]" type="checkbox" value="<?php echo $profile->get('account_id');?>" class="friend-selector"><br>
                <?php echo $profile->get('nickname');?>
            </div>
        <?php endforeach; ?>
        </div>
        
        <button class="btn btn-default btn-block" name="action" value="interaction/friends" type="submit">一起去看电影</button>
    </form>
</div>