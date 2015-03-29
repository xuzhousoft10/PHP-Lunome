<?php 
$vars = get_defined_vars();
$pager = $vars['pager'];
$assetsURL = $vars['assetsURL'];
$accounts = $vars['accounts'];
$listID = 'marked-user-list-'.uniqid();
$sexNames = $vars['sexNames'];
$sexMarks = $vars['sexMarks'];
$sexualityNames = $vars['sexualityNames'];
$emotionStatuNames = $vars['emotionStatuNames'];
?>
<?php if ( empty($accounts) ): ?>
    <div class="clearfix">
        <div class="pull-left">
            <img src="<?php echo $assetsURL;?>/image/nothing.gif" width="100" height="100">
        </div>
        <div class="margin-top-70 text-muted">
            <small>还没有被标记过～～～</small>
        </div>
    </div>
<?php else : ?>
<div id="<?php echo $listID; ?>">
    <?php foreach ( $accounts as $account ) : ?>
    <?php /* @var $account \X\Module\Account\Service\Account\Core\Instance\Account */ ?>
    <?php $profile = $account->getProfileManager(); ?>
        <?php printf('<a href="/?module=account&action=home/index&id=%s" target="_blank">', $account->getID()); ?>
        <div class="thumbnail margin-bottom-5 clearfix">
            <div class="pull-left">
                <img class="thumbnail padding-0" src="<?php echo $profile->get('photo'); ?>" width="60" height="60">
            </div>
            <div class="pull-left padding-left-10">
                <span class="text-info" title="性别:<?php echo $sexNames[intval($profile->get('sex'))]; ?>">
                    <?php echo $sexMarks[intval($profile->get('sex'))];?>
                </span>
                <strong><?php echo $profile->get('nickname'); ?></strong><br><br>
                <small>
                    <span class="glyphicon glyphicon-heart"></span>
                    <?php echo $sexualityNames[intval($profile->get('sexuality'))];?>
                    <span class="glyphicon glyphicon-user"></span>
                    <?php echo $emotionStatuNames[intval($profile->get('emotion_status'))];?>
                </small>
            </div>
        </div>
        <?php echo '</a>'; ?>
    <?php endforeach; ?>
    
    <?php $vars['pager']->setPrevPageButtonClass('marked-user-list-pager'); ?>
    <?php $vars['pager']->setNextPageButtonClass('marked-user-list-pager'); ?>
    <?php $vars['pager']->show(); ?>
</div>

<script>
$(document).ready(function() {
    $('.marked-user-list-pager').click(function() {
        $('#<?php echo $listID;?>').addClass('text-center').empty()
        .html('<img src="<?php echo $assetsURL;?>/image/loadding.gif">');

        var $this = $(this);
        $.get($this.attr('href'), {}, function( response ) {
            var container = $('#<?php echo $listID;?>').parent();
            console.log(container);
            container.removeClass('text-center').html(response);
            container.parent().css('padding','0');
        }, 'text');
        return false;
    });
});
</script>
<?php endif; ?>